<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pretest extends Model
{
    protected $fillable = ['user_id', 'score', 'level', 'completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}