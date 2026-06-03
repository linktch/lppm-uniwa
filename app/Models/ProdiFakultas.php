<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdiFakultas extends Model
{
    protected $table = 'prodi_fakultas';

    protected $fillable = [
        'nama_program_studi',
    ];

    /**
     * Relasi ke tabel pejabat_signatur
     * HasMany karena satu prodi_fakultas bisa memiliki banyak pejabat signatur
     */
    public function pejabatSignaturs()
    {
        return $this->hasMany(PejabatSignatur::class, 'prodi_fakultas_id');
    }
}