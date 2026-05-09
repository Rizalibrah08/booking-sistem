<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the `assets` table for storing facility/equipment data.
     * - kategori: 'ruangan' (room) or 'alat' (equipment)
     * - status: availability state of the asset
     * - is_restricted_for_student: if true, students cannot borrow this asset
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->enum('kategori', ['ruangan', 'alat']);
            $table->enum('status', ['tersedia', 'rusak', 'maintenance'])->default('tersedia');
            $table->boolean('is_restricted_for_student')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
