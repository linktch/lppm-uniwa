<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningQuestion extends Model
{
    protected $fillable = ['pertanyaan'];

    public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class, 'question_id');
    }
}
