<?php

namespace App\Models;

use App\Models\Alumni;
use App\Models\QuestionnaireQuestion;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireAnswer extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'question_id',
        'alumni_id',
        'answer',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function question()
    {
        return $this->belongsTo(QuestionnaireQuestion::class, 'question_id');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
