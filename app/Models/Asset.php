<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    // ── Status Constants ────────────────────────────────────────────
    public const STATUS_TERSEDIA    = 'tersedia';
    public const STATUS_RUSAK       = 'rusak';
    public const STATUS_MAINTENANCE = 'maintenance';

    // ── Kategori Constants ──────────────────────────────────────────
    public const KATEGORI_RUANGAN = 'ruangan';
    public const KATEGORI_ALAT    = 'alat';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_aset',
        'kategori',
        'status',
        'is_restricted_for_student',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_restricted_for_student' => 'boolean',
        ];
    }

    // ── Relationships ───────────────────────────────────────────────

    /**
     * All booking requests for this asset.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'asset_id');
    }

    // ── Helper Methods ──────────────────────────────────────────────

    /**
     * Check if this asset is currently available for booking.
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_TERSEDIA;
    }

    /**
     * Check if this asset is restricted from student borrowing.
     */
    public function isRestrictedForStudent(): bool
    {
        return (bool) $this->is_restricted_for_student;
    }
}
