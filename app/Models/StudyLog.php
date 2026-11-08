<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyLog extends Model
{
    protected $fillable = [
        'study_set_id',
        'type',
        'correct',
        'points',
    ];

    protected $casts = [
        'correct' => 'boolean',
    ];

    public function studySet()
    {
        return $this->belongsTo(StudySet::class);
    }
}