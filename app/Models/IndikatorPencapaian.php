<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorPencapaian extends Model
{
    use HasFactory;

    protected $table = 'indikator_pencapaian';

    protected $fillable = [
        'periode_id',
        'kegiatan_id',
        'kelompok_id',
        'id_prodi',
        'indikator',
        'created_by',
        'role',
    ];

    // 🔗 KELOMPOK
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    // 🔗 PERIODE
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    // 🔗 KEGIATAN
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    // 🔗 USER PEMBUAT
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🔗 PENILAIAN
    public function penilaians()
    {
        return $this->hasMany(PenilaianCp::class, 'indikator_penilaian_id');
    }

    // 🔎 SCOPE
    public function scopeByKelompok($query, $kelompokId)
    {
        return $query->where('kelompok_id', $kelompokId);
    }
}