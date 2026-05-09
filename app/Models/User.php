<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ── Role Constants ──────────────────────────────────────────────
    public const ROLE_ADMIN  = 'admin';
    public const ROLE_KEPSEK = 'kepsek';
    public const ROLE_GURU   = 'guru';
    public const ROLE_STAF   = 'staf';

    /**
     * Jabatan (role) weight mapping used in SAW C1 criterion.
     * Higher value = higher priority (Benefit criterion).
     */
    public const JABATAN_SCORES = [
        self::ROLE_KEPSEK => 4,
        self::ROLE_GURU   => 3,
        self::ROLE_STAF   => 2,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jabatan_score',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'jabatan_score'     => 'integer',
        ];
    }

    // ── Relationships ───────────────────────────────────────────────

    /**
     * Booking requests submitted by this user.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    /**
     * Booking requests where this user acts as a guarantor (guru penanggung jawab).
     */
    public function guaranteedPeminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'guarantor_id');
    }

    // ── Helper Methods ──────────────────────────────────────────────

    /**
     * Check if this user has the Kepsek (Principal) role — eligible for Hak Veto.
     */
    public function isKepsek(): bool
    {
        return $this->role === self::ROLE_KEPSEK;
    }

    /**
     * Check if this user is an Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if this user is a Guru (Teacher).
     */
    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }

    /**
     * Resolve the jabatan score for SAW calculation.
     * Falls back to the JABATAN_SCORES constant if jabatan_score is 0.
     */
    public function resolveJabatanScore(): int
    {
        if ($this->jabatan_score > 0) {
            return $this->jabatan_score;
        }

        return self::JABATAN_SCORES[$this->role] ?? 0;
    }
}
