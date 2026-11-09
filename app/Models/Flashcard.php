<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $fillable = [
        'study_set_id',
        'front',
        'back',
        'known',
        'reviewed',
    ];

    protected $casts = [
        'known' => 'boolean',
        'reviewed' => 'boolean',
    ];
}