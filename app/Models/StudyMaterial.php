<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    protected $fillable = [
        'study_set_id',
        'original_name',
        'file_path',
        'source_text',
        'mime_type',
        'file_size',
    ];

    public function studySet()
    {
        return $this->belongsTo(StudySet::class);
    }
}