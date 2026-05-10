<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Quiz;

class Material extends Model
{
    protected $fillable = [
        'title', 
        'content', 
        'level',
        'video_url',
        'code_visualization',
        'is_published'
    ];

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}