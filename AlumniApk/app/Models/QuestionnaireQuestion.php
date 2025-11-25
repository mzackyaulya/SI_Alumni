<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuestion extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'question_type',
        'options',
    ];

    protected $casts = [
        'options' => 'array', // karena kolom options tipe JSON
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionnaireAnswer::class, 'question_id');
    }
}
