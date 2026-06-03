<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    protected $table = 'laporan_harian';

    protected $fillable = [
        'user_id',
        'kelompok_id',
        'periode_id',
        'kegiatan_id',
        'tanggal',
        'aktivitas',
        'status',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Mahasiswa (user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    // 🔥 nanti dipakai untuk review
    public function reviews()
    {
        return $this->hasMany(Review::class, 'laporan_id');
    }

        public function periode()
    {
        return $this->belongsTo(\App\Models\Periode::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(\App\Models\Kegiatan::class);
    }
}