<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Kelompok extends Model
{
    protected $table = "kelompok";

    protected $fillable = [
        'nama_kelompok',
        'periode_id',
        'kegiatan_id',
        'lokasi',
    ];

    // ================= RELASI UTAMA =================

    public function periode()
    {
        return $this->belongsTo(\App\Models\Periode::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(\App\Models\Kegiatan::class);
    }

    // ================= RELASI USERS (PIVOT) =================

    public function users()
    {
        return $this->belongsToMany(User::class, 'kelompok_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // ================= FILTER BERDASARKAN ROLE =================

    public function tim()
    {
        return $this->users()->wherePivot('role', 'tim');
    }

    public function mahasiswa()
    {
        return $this->users()->wherePivot('role', 'mahasiswa');
    }

    public function kemahasiswaan()
    {
        return $this->users()->wherePivot('role', 'kemahasiswaan');
    }

    public function prodi()
    {
        return $this->users()->wherePivot('role', 'prodi');
    }
    public function mitra()
    {
        return $this->users()->wherePivot('role', 'mitra');
    }
    public function indikatorPencapaian()
    {
        return $this->hasMany(IndikatorPencapaian::class, 'kelompok_id');
    }
}