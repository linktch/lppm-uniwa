<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'periode_id',
        'kegiatan_id',
        'question_id',
        'jawaban',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function question()
    {
        return $this->belongsTo(ScreeningQuestion::class, 'question_id');
    }
}
