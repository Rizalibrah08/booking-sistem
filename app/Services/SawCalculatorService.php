<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * SawCalculatorService
 *
 * Implements the Simple Additive Weighting (SAW) algorithm to resolve
 * scheduling conflicts for the MBS Facility Booking System.
 *
 * ┌─────────────────────────────────────────────────────────────────┐
 * │  SAW CRITERIA DEFINITION                                       │
 * ├────────┬──────────┬───────────────────────────────────────────  │
 * │ Code   │ Type     │ Description                                │
 * ├────────┼──────────┼───────────────────────────────────────────  │
 * │ C1     │ Benefit  │ Jabatan/Role Weight (Kepsek=4..Staf=2)     │
 * │ C2     │ Benefit  │ Urgensi (Ujian=4..Ekskul=1)                │
 * │ C3     │ Cost     │ Lead Time (H-7=3, H-3=2, Hari H=1)        │
 * └────────┴──────────┴───────────────────────────────────────────  │
 * └─────────────────────────────────────────────────────────────────┘
 *
 * SAW Steps:
 * 1. Build the decision matrix from conflicting requests.
 * 2. Normalize each criterion:
 *    - Benefit: value / max(column)
 *    - Cost:    min(column) / value
 * 3. Calculate weighted score: Σ (normalized_value × weight)
 * 4. Rank alternatives by final score (highest wins).
 */
class SawCalculatorService
{
    /**
     * Criterion weights (must sum to 1.0 for proper SAW).
     * Adjust these to change priority balance.
     */
    protected const WEIGHTS = [
        'C1' => 0.40, // Jabatan / Role
        'C2' => 0.35, // Urgensi
        'C3' => 0.25, // Lead Time (Cost – lower is better, i.e. submitted earlier)
    ];

    /**
     * Criterion types: 'benefit' = higher is better, 'cost' = lower is better.
     */
    protected const TYPES = [
        'C1' => 'benefit',
        'C2' => 'benefit',
        'C3' => 'cost',
    ];

    /**
     * Resolve scheduling conflicts for a given asset, date, and time range.
     *
     * This is the main entry point. It:
     * 1. Detects all conflicting peminjaman records.
     * 2. Applies Hak Veto (Kepsek auto-approve).
     * 3. Runs the SAW algorithm if multiple non-veto conflicts exist.
     * 4. Approves the winner and rejects all others.
     *
     * @param int    $assetId
     * @param string $tglPakai   Format: Y-m-d
     * @param string $jamMulai   Format: H:i or H:i:s
     * @param string $jamSelesai Format: H:i or H:i:s
     * @return void
     */
    public function resolveConflicts(
        int $assetId,
        string $tglPakai,
        string $jamMulai,
        string $jamSelesai
    ): void {
        // Fetch all active (pending/approved) conflicting requests, eager load relations
        $conflicts = Peminjaman::conflictsWith($assetId, $tglPakai, $jamMulai, $jamSelesai)
            ->with(['user', 'guarantor'])
            ->get();

        // No conflict or single request → nothing to resolve
        if ($conflicts->count() <= 1) {
            // If there's exactly one pending request, auto-approve it
            if ($conflicts->count() === 1 && $conflicts->first()->isPending()) {
                $conflicts->first()->update(['status' => Peminjaman::STATUS_APPROVED]);
            }
            return;
        }

        // ── Step 1: Check for Hak Veto (Kepsek override) ────────────
        $vetoRequest = $this->findVetoRequest($conflicts);

        if ($vetoRequest) {
            $this->applyVeto($vetoRequest, $conflicts);
            return;
        }

        // ── Step 2: Run SAW algorithm ────────────────────────────────
        $this->runSaw($conflicts);
    }

    /**
     * Find a Kepsek request among the conflicts (Hak Veto).
     *
     * @param Collection<int, Peminjaman> $conflicts
     * @return Peminjaman|null
     */
    protected function findVetoRequest(Collection $conflicts): ?Peminjaman
    {
        return $conflicts->first(function (Peminjaman $p) {
            // Direct Kepsek/Admin request (not a student borrower)
            return ! $p->is_student_borrower && ($p->user->isKepsek() || $p->user->isAdmin());
        });
    }

    /**
     * Apply Hak Veto: approve the Kepsek request and force-cancel all others.
     *
     * @param Peminjaman $vetoRequest
     * @param Collection<int, Peminjaman> $conflicts
     * @return void
     */
    protected function applyVeto(Peminjaman $vetoRequest, Collection $conflicts): void
    {
        DB::transaction(function () use ($vetoRequest, $conflicts) {
            // Approve the Kepsek's request
            $vetoRequest->update([
                'status'          => Peminjaman::STATUS_APPROVED,
                'saw_final_score' => null, // SAW not used for veto
            ]);

            // Force-cancel all other conflicting requests
            $conflicts
                ->where('id', '!=', $vetoRequest->id)
                ->each(function (Peminjaman $p) {
                    $p->update([
                        'status'        => 'rejected',
                        'cancel_reason' => 'Otomatis ditolak: Hak Veto Admin / Kepala Sekolah.',
                    ]);
                });

            Log::info("Hak Veto applied: Peminjaman #{$vetoRequest->id} approved, others force-canceled.");
        });
    }

    /**
     * Run the full SAW algorithm on a set of conflicting requests.
     *
     * @param Collection<int, Peminjaman> $conflicts  Must have >= 2 items
     * @return void
     */
    protected function runSaw(Collection $conflicts): void
    {
        // ── Step 1: Build the decision matrix ────────────────────────
        $matrix = $this->buildDecisionMatrix($conflicts);

        // ── Step 2: Normalize the matrix ─────────────────────────────
        $normalized = $this->normalizeMatrix($matrix);

        // ── Step 3: Calculate weighted scores ────────────────────────
        $scores = $this->calculateWeightedScores($normalized);

        // ── Step 4: Apply results ────────────────────────────────────
        $this->applyResults($conflicts, $scores);
    }

    /**
     * Build the raw decision matrix from conflicting peminjaman records.
     *
     * Returns an associative array keyed by peminjaman ID:
     * [
     *   peminjaman_id => ['C1' => jabatan_score, 'C2' => urgensi_score, 'C3' => lead_time_score],
     *   ...
     * ]
     *
     * @param Collection<int, Peminjaman> $conflicts
     * @return array<int, array<string, int>>
     */
    protected function buildDecisionMatrix(Collection $conflicts): array
    {
        $matrix = [];

        foreach ($conflicts as $peminjaman) {
            $matrix[$peminjaman->id] = [
                'C1' => $peminjaman->getEffectiveJabatanScore(), // Jabatan (Benefit)
                'C2' => $peminjaman->urgensi_score,              // Urgensi (Benefit)
                'C3' => $peminjaman->lead_time_score,            // Lead Time (Cost)
            ];
        }

        return $matrix;
    }

    /**
     * Normalize the decision matrix using SAW normalization rules.
     *
     * For BENEFIT criteria: r_ij = x_ij / max(x_j)
     * For COST criteria:    r_ij = min(x_j) / x_ij
     *
     * @param array<int, array<string, int>> $matrix
     * @return array<int, array<string, float>>
     */
    protected function normalizeMatrix(array $matrix): array
    {
        if (empty($matrix)) {
            return [];
        }

        $criteria = array_keys(self::TYPES); // ['C1', 'C2', 'C3']

        // Calculate max and min for each criterion column
        $columnStats = [];
        foreach ($criteria as $c) {
            $values = array_column($matrix, $c);
            $columnStats[$c] = [
                'max' => max($values),
                'min' => min($values),
            ];
        }

        // Normalize each cell
        $normalized = [];
        foreach ($matrix as $id => $row) {
            foreach ($criteria as $c) {
                $value = $row[$c];
                $max   = $columnStats[$c]['max'];
                $min   = $columnStats[$c]['min'];

                if (self::TYPES[$c] === 'benefit') {
                    // Benefit: r_ij = x_ij / max(x_j)
                    // Guard against division by zero (all values are 0)
                    $normalized[$id][$c] = $max > 0 ? $value / $max : 0;
                } else {
                    // Cost: r_ij = min(x_j) / x_ij
                    // Guard against division by zero
                    $normalized[$id][$c] = $value > 0 ? $min / $value : 0;
                }
            }
        }

        return $normalized;
    }

    /**
     * Calculate the final weighted score for each alternative.
     *
     * Formula: V_i = Σ (w_j × r_ij)
     *
     * @param array<int, array<string, float>> $normalized
     * @return array<int, float>  Keyed by peminjaman ID, sorted descending by score
     */
    protected function calculateWeightedScores(array $normalized): array
    {
        $scores = [];

        foreach ($normalized as $id => $row) {
            $score = 0;
            foreach (self::WEIGHTS as $c => $weight) {
                $score += $weight * ($row[$c] ?? 0);
            }
            $scores[$id] = round($score, 4);
        }

        // Sort by score descending (highest priority first)
        arsort($scores);

        return $scores;
    }

    /**
     * Apply SAW results: approve the winner, reject the rest.
     * Updates saw_final_score for all conflicting requests.
     *
     * @param Collection<int, Peminjaman> $conflicts
     * @param array<int, float> $scores  Sorted descending
     * @return void
     */
    protected function applyResults(Collection $conflicts, array $scores): void
    {
        DB::transaction(function () use ($conflicts, $scores) {
            $winnerId = array_key_first($scores); // Highest score

            foreach ($conflicts as $peminjaman) {
                $finalScore = $scores[$peminjaman->id] ?? 0;
                $isWinner   = $peminjaman->id === $winnerId;

                $peminjaman->update([
                    'saw_final_score' => $finalScore,
                    'status'          => $isWinner
                        ? Peminjaman::STATUS_APPROVED
                        : Peminjaman::STATUS_REJECTED,
                    'cancel_reason'   => $isWinner
                        ? null
                        : "Ditolak oleh SAW: skor akhir {$finalScore} (kalah dari #{$winnerId}).",
                ]);
            }

            Log::info("SAW resolved: Winner is Peminjaman #{$winnerId} with score {$scores[$winnerId]}.", [
                'scores' => $scores,
            ]);
        });
    }

    /**
     * Calculate the lead time score based on how many days in advance
     * the request was submitted relative to the usage date.
     *
     * @param string $tglPakai     The usage date (Y-m-d)
     * @param string|null $tglSubmit  The submission date (Y-m-d), defaults to today
     * @return int  Lead time score: H-7+=3, H-3 to H-6=2, <H-3=1
     */
    public static function calculateLeadTimeScore(string $tglPakai, ?string $tglSubmit = null): int
    {
        $submit  = $tglSubmit ? \Carbon\Carbon::parse($tglSubmit) : now();
        $pakai   = \Carbon\Carbon::parse($tglPakai);
        $dayDiff = (int) $submit->startOfDay()->diffInDays($pakai->startOfDay(), false);

        if ($dayDiff >= 7) {
            return Peminjaman::LEAD_TIME_H7;    // 3 — submitted ≥ 7 days early
        }

        if ($dayDiff >= 3) {
            return Peminjaman::LEAD_TIME_H3;    // 2 — submitted 3-6 days early
        }

        return Peminjaman::LEAD_TIME_HARIH;     // 1 — submitted same day or < 3 days
    }

    /**
     * Get the SAW weights for external reference (e.g., admin dashboard).
     *
     * @return array<string, float>
     */
    public static function getWeights(): array
    {
        return self::WEIGHTS;
    }

    /**
     * Get the criteria type definitions.
     *
     * @return array<string, string>
     */
    public static function getCriteriaTypes(): array
    {
        return self::TYPES;
    }
}
