<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'study_set_id',
        'question',
        'type',
        'options',
        'correct_index',
        'explanation',
        'missed',
    ];

    protected $casts = [
        'options' => 'array',
        'missed' => 'boolean',
        'correct_index' => 'integer',
    ];

    public function studySet()
    {
        return $this->belongsTo(StudySet::class);
    }
}