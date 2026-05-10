<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'avatar', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isLecturer(): bool { return $this->role === 'lecturer'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    public function pretests(): HasMany { return $this->hasMany(Pretest::class); }
    public function latestPretest(): HasOne { return $this->hasOne(Pretest::class)->latestOfMany(); }

    public function hasCompletedPretest(): bool
    {
        return $this->pretests()->exists();
    }

    public function getLevel(): string
    {
        return $this->latestPretest->level ?? 'Level 1 - Pemula';
    }

    public function accessedMaterials()
    {
        return $this->belongsToMany(Material::class)->withTimestamps();
    }
}