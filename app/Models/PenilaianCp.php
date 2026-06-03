<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianCp extends Model
{
    use HasFactory;

    protected $table = 'penilaian_cp';

    protected $fillable = [
        'indikator_pencapaian_id',
        'user_id',
        'progres',
        'kelompok_id',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    /**
     * RELASI INDIKATOR
     */
    public function indikator()
    {
        return $this->belongsTo(IndikatorPencapaian::class, 'indikator_pencapaian_id');
    }

    /**
     * Relasi ke mahasiswa (user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter berdasarkan indikator
     */
    public function scopeByIndikator($query, $indikatorId)
    {
        return $query->where('indikator_penilaian_id', $indikatorId);
    }

    /**
     * Helper: cek apakah tercapai
     */
    public function isTercapai()
    {
        return $this->progres === 'tercapai';
    }
}