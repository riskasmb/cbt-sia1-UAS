<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResultAnswer extends Model
{
    protected $guarded = [];

    protected $casts = ['is_correct' => 'boolean'];

    public function result()
    {
        return $this->belongsTo(ExamResult::class, 'exam_result_id');
    }
}