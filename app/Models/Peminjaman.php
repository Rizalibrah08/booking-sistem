<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel secara eksplisit.
     * Laravel otomatis meng-pluralize 'Peminjaman' menjadi 'peminjamen' (aturan Inggris),
     * padahal tabel kita bernama 'peminjamans'.
     */
    protected $table = 'peminjamans';

    // ── Status Constants ────────────────────────────────────────────
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELED = 'canceled';

    // ── Urgensi Score Constants ──────────────────────────────────────
    public const URGENSI_UJIAN  = 4;
    public const URGENSI_KBM    = 3;
    public const URGENSI_RAPAT  = 2;
    public const URGENSI_EKSKUL = 1;

    public const URGENSI_LABELS = [
        self::URGENSI_UJIAN  => 'Ujian',
        self::URGENSI_KBM    => 'KBM',
        self::URGENSI_RAPAT  => 'Rapat',
        self::URGENSI_EKSKUL => 'Ekskul',
    ];

    // ── Lead Time Score Constants ────────────────────────────────────
    public const LEAD_TIME_H7    = 3; // Diajukan ≥ 7 hari sebelumnya
    public const LEAD_TIME_H3    = 2; // Diajukan 3-6 hari sebelumnya
    public const LEAD_TIME_HARIH = 1; // Diajukan di hari H

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'asset_id',
        'tgl_pakai',
        'jam_mulai',
        'jam_selesai',
        'tujuan',
        'is_student_borrower',
        'nama_siswa',
        'guarantor_id',
        'urgensi_score',
        'lead_time_score',
        'saw_final_score',
        'status',
        'cancel_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_pakai'           => 'date',
            'is_student_borrower' => 'boolean',
            'urgensi_score'       => 'integer',
            'lead_time_score'     => 'integer',
            'saw_final_score'     => 'float',
        ];
    }

    // ── Relationships ───────────────────────────────────────────────

    /**
     * The user who created this booking request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The asset being requested.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    /**
     * The guarantor teacher (only for student borrowers).
     */
    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guarantor_id');
    }

    // ── Scopes ──────────────────────────────────────────────────────

    /**
     * Scope: find bookings that conflict with a given schedule.
     *
     * A conflict exists when another booking is for the same asset
     * on the same date AND the time ranges overlap.
     *
     * Overlap condition: existing.jam_mulai < new.jam_selesai
     *               AND  existing.jam_selesai > new.jam_mulai
     */
    public function scopeConflictsWith(
        Builder $query,
        int $assetId,
        string $tglPakai,
        string $jamMulai,
        string $jamSelesai
    ): Builder {
        return $query->where('asset_id', $assetId)
                     ->where('tgl_pakai', $tglPakai)
                     ->where('jam_mulai', '<', $jamSelesai)
                     ->where('jam_selesai', '>', $jamMulai)
                     ->whereNotIn('status', [self::STATUS_REJECTED, self::STATUS_CANCELED]);
    }

    /**
     * Scope: only active/relevant bookings (pending or approved).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    // ── Helper Methods ──────────────────────────────────────────────

    /**
     * Get the effective jabatan score for SAW calculation.
     * If this is a student borrower, use the guarantor's score.
     * Otherwise, use the requesting user's score.
     */
    public function getEffectiveJabatanScore(): int
    {
        if ($this->is_student_borrower && $this->guarantor) {
            return $this->guarantor->resolveJabatanScore();
        }

        return $this->user->resolveJabatanScore();
    }

    /**
     * Get the human-readable urgensi label.
     */
    public function getUrgensiLabel(): string
    {
        return self::URGENSI_LABELS[$this->urgensi_score] ?? 'Tidak Diketahui';
    }

    /**
     * Check if this booking is still in a decidable state (pending).
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if this booking has been approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
