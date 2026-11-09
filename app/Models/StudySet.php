<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudySet extends Model
{
    protected $fillable = [
        'title',
        'source_text',
        'summary',
        'image_path',
    ];

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function materials()
    {
        return $this->hasMany(StudyMaterial::class);
    }
}