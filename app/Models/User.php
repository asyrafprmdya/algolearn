<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'level',
        'completed_contents',
        'has_completed_pretest',
        'is_active', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'completed_contents' => 'array',
            'has_completed_pretest' => 'boolean',
        ];
    }

    public function getLevel()
    {
        if ($this->level === 'Lanjutan') return 'LEVEL 3 - LANJUTAN';
        if ($this->level === 'Menengah') return 'LEVEL 2 - MENENGAH';
        return 'LEVEL 1 - PEMULA';
    }

    public function getRawLevel()
    {
        return $this->level ?? 'Pemula';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isLecturer()
    {
        return $this->role === 'lecturer';
    }

    public function hasCompletedPretest()
    {
        return $this->has_completed_pretest ?? false;
    }
}