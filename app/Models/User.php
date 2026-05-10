<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'pretest_completed',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isLecturer(): bool { return $this->role === 'lecturer'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    // Relasi ke kuis/tugas (biarin aja kalau nanti dipake)
    public function pretests(): HasMany { return $this->hasMany(Pretest::class); }

    // KODINGAN MUTLAK 1: Cek langsung ke kolom 'pretest_completed' di tabel users
    public function hasCompletedPretest(): bool
    {
        return $this->pretest_completed == 1;
    }

    // KODINGAN MUTLAK 2: Ambil level langsung dari kolom 'level' di tabel users
    public function getLevel(): string
    {
        if ($this->level === 'Lanjutan') {
            return 'LEVEL 3 - LANJUTAN';
        } elseif ($this->level === 'Menengah') {
            return 'LEVEL 2 - MENENGAH';
        }
        
        return 'LEVEL 1 - PEMULA';
    }

    public function accessedMaterials()
    {
        return $this->belongsToMany(Material::class)->withTimestamps();
    }
}