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
        'nis',
        'kelas',
        'jurusan',
        'no_hp',
        'nip',
        'spesialisasi_kategori',
        'is_ready',
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
            'password' => 'hashed',
            'is_ready' => 'boolean',
        ];
    }

    /**
     * Role checks
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isGuruBk(): bool
    {
        return $this->role === 'guru_bk';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Sessions requested by this student.
     */
    public function studentSessions(): HasMany
    {
        return $this->hasMany(CounselingSession::class, 'student_id');
    }

    /**
     * Sessions assigned to this counselor.
     */
    public function counselorSessions(): HasMany
    {
        return $this->hasMany(CounselingSession::class, 'counselor_id');
    }
}
