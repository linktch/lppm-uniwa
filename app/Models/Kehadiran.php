<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'tanggal',
        'status',
        'periode_id',
        'kegiatan_id',
    ];

    // RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI KE KELOMPOK
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}