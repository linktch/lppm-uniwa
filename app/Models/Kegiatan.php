<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'periode_id'
    ];

    // 🔗 PERIODE
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    // 🔗 INDIKATOR
    public function indikatorPencapaian()
    {
        return $this->hasMany(IndikatorPencapaian::class, 'kegiatan_id');
    }

        public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class, 'kegiatan_id');
    }
}