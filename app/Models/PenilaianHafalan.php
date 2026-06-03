<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianHafalan extends Model
{
    protected $table = 'penilaian_hafalan';
    
    protected $fillable = [
        'user_id',
        'indikator_id',
        'nilai',
        'periode_id',
        'kegiatan_id',
        'penilai_id',
        'tanggal_penilaian',
        'keterangan'
    ];
    
    protected $casts = [
        'tanggal_penilaian' => 'datetime',
        'nilai' => 'string' // Karena nilai berupa 'sangat_lancar' atau 'cukup_lancar'
    ];
    
    /**
     * Relasi ke User (Mahasiswa yang dinilai)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke IndikatorHafalan
     */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(IndikatorHafalan::class, 'indikator_id');
    }
    
    /**
     * Relasi ke Periode
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
    
    /**
     * Relasi ke Kegiatan
     */
    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    
    /**
     * Relasi ke Penilai (User yang menilai)
     */
    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }
    
    /**
     * Get label nilai
     */
    public function getLabelNilaiAttribute(): string
    {
        return match($this->nilai) {
            'sangat_lancar' => 'Sangat Lancar',
            'cukup_lancar' => 'Cukup Lancar',
            default => 'Belum Dinilai'
        };
    }
    
    /**
     * Get nilai dalam bentuk angka (untuk perhitungan)
     */
    public function getNilaiAngkaAttribute(): int
    {
        return match($this->nilai) {
            'sangat_lancar' => 100,
            'cukup_lancar' => 70,
            default => 0
        };
    }
}