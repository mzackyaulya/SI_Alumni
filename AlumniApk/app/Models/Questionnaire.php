<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'is_mandatory',
    ];

    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionnaireAnswer::class);
    }
}
