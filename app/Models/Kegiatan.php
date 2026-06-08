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

    // 🔗 LAPORAN HARIAN
    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'kegiatan_id');
    }

    // 🔗 KELOMPOK
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'kegiatan_id');
    }

    // 🔗 SERTIFIKAT
    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class, 'kegiatan_id');
    }
}