<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'pretest_completed',
        'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isLecturer(): bool { return $this->role === 'lecturer'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    public function pretests(): HasMany { return $this->hasMany(Pretest::class); }
    public function latestPretest(): HasOne { return $this->hasOne(Pretest::class)->latestOfMany(); }

    public function hasCompletedPretest(): bool
    {
        return $this->pretest_completed == 1;
    }

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