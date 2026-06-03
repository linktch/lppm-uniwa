<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PejabatSignatur extends Model
{
    protected $table = 'pejabat_signatur';

    protected $fillable = [
        'user_id',           // Ditambahkan
        'prodi_fakultas_id',
        'nama',
        'jabatan',
        'signatur_path',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel prodi_fakultas
     */
    public function prodiFakultas()
    {
        return $this->belongsTo(ProdiFakultas::class, 'prodi_fakultas_id');
    }
}