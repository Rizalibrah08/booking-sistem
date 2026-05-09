<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the `peminjamans` table — the core booking/loan request table.
     *
     * Key design decisions:
     * - user_id: the authenticated user who created the request
     * - is_student_borrower: flags that this is a student request (input by Admin)
     * - nama_siswa: the student's name (nullable, only filled for student borrowers)
     * - guarantor_id: FK to users table — the responsible teacher for student requests
     * - urgensi_score & lead_time_score: pre-calculated SAW criteria values
     * - saw_final_score: computed by SawCalculatorService when conflicts exist
     * - Composite index on (asset_id, tgl_pakai, jam_mulai, jam_selesai) for fast conflict detection
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();

            // ── Requester ─────────────────────────────────────────────
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // ── Asset & Schedule ──────────────────────────────────────
            $table->foreignId('asset_id')
                  ->constrained('assets')
                  ->cascadeOnDelete();

            $table->date('tgl_pakai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('tujuan'); // Purpose of the booking

            // ── Student Borrower Fields ───────────────────────────────
            $table->boolean('is_student_borrower')->default(false);
            $table->string('nama_siswa')->nullable();
            $table->foreignId('guarantor_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // ── SAW Criteria Scores ───────────────────────────────────
            $table->unsignedTinyInteger('urgensi_score')->default(1);   // Ujian=4, KBM=3, Rapat=2, Ekskul=1
            $table->unsignedTinyInteger('lead_time_score')->default(1); // H-7=3, H-3=2, Hari H=1
            $table->float('saw_final_score')->nullable();               // Computed by SAW engine

            // ── Status & Cancellation ─────────────────────────────────
            $table->enum('status', ['pending', 'approved', 'rejected', 'canceled'])->default('pending');
            $table->text('cancel_reason')->nullable();

            $table->timestamps();

            // ── Composite index for fast conflict detection ───────────
            $table->index(['asset_id', 'tgl_pakai', 'jam_mulai', 'jam_selesai'], 'idx_conflict_check');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
