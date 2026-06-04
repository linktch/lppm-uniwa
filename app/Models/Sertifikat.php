<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikats';
    
    protected $fillable = [
        'user_id',
        'periode_id',
        'kegiatan_id',
        'nomor_sertifikat',
        'nama_mahasiswa',
        'nim',
        'prodi',
        'kelompok',
        'kabupaten',
        'provinsi',
        'predikat',
        'total_nilai',
        'persentase',
        'capaian_hafalan',
        'data_penanda_tangan',
        'file_path',
        'tanggal_terbit',
    ];
    
    protected $casts = [
        'capaian_hafalan' => 'array',
        'data_penanda_tangan' => 'array',
        'tanggal_terbit' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
    
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}