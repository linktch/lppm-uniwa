<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    // 🔗 KELOMPOK
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'periode_id');
    }

    // 🔗 KEGIATAN (PENTING)
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'periode_id');
    }

    // 🔗 INDIKATOR
    public function indikatorPencapaian()
    {
        return $this->hasMany(IndikatorPencapaian::class, 'periode_id');
    }

     public function answers()
    {
        return $this->hasMany(ScreeningAnswer::class, 'periode_id');
    }
}