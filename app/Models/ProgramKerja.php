<?php
// app/Models/ProgramKerja.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramKerja extends Model
{
    protected $table = 'program_kerja';
    
    protected $fillable = [
        'kelompok_id',
        'periode_id',
        'kegiatan_id',
        'nama_program',
        'deskripsi',
        'lokasi',
        'tanggal_pelaksanaan',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'dokumentasi_path',
        'keterangan',
        'target_peserta',
        'realisasi_peserta',
        'catatan_pembimbing',
        'nilai',
    ];
    
    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'target_peserta' => 'integer',
        'realisasi_peserta' => 'integer',
        'nilai' => 'integer',
    ];
    
    // Status constants
    const STATUS_RENCANA = 'rencana';
    const STATUS_PROSES = 'proses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_BATAL = 'batal';
    
    /**
     * Get all statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_RENCANA => 'Rencana',
            self::STATUS_PROSES => 'Proses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_BATAL => 'Batal',
        ];
    }
    
    // ========== RELATIONS ==========
    
    /**
     * Relasi ke tabel kelompoks
     */
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }
    
    /**
     * Relasi ke tabel periodes
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
    
    /**
     * Relasi ke tabel kegiatans
     */
    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    
    // ========== SCOPES ==========
    
    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope program selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }
    
    /**
     * Scope program aktif (rencana atau proses)
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', [self::STATUS_RENCANA, self::STATUS_PROSES]);
    }
    
    /**
     * Scope by kelompok
     */
    public function scopeByKelompok($query, $kelompokId)
    {
        return $query->where('kelompok_id', $kelompokId);
    }
    
    /**
     * Scope by periode
     */
    public function scopeByPeriode($query, $periodeId)
    {
        return $query->where('periode_id', $periodeId);
    }
    
    // ========== ACCESSORS ==========
    
    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? ucfirst($this->status);
    }
    
    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            self::STATUS_RENCANA => 'warning',
            self::STATUS_PROSES => 'info',
            self::STATUS_SELESAI => 'success',
            self::STATUS_BATAL => 'danger',
        ];
        
        $color = $badges[$this->status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$this->status_label}</span>";
    }
    
    /**
     * Get persentase realisasi peserta
     */
    public function getRealisasiPersentaseAttribute(): int
    {
        if ($this->target_peserta <= 0) {
            return 0;
        }
        return round(($this->realisasi_peserta / $this->target_peserta) * 100);
    }
    
    /**
     * Get formatted waktu pelaksanaan
     */
    public function getWaktuPelaksanaanAttribute(): string
    {
        if (!$this->tanggal_pelaksanaan) {
            return '-';
        }
        
        $format = $this->tanggal_pelaksanaan->format('d F Y');
        
        if ($this->waktu_mulai && $this->waktu_selesai) {
            $format .= ' (' . date('H:i', strtotime($this->waktu_mulai)) . ' - ' . date('H:i', strtotime($this->waktu_selesai)) . ')';
        } elseif ($this->waktu_mulai) {
            $format .= ' (' . date('H:i', strtotime($this->waktu_mulai)) . ' WIB)';
        }
        
        return $format;
    }
    
    /**
     * Get formatted tanggal pelaksanaan
     */
    public function getTanggalPelaksanaanFormattedAttribute(): string
    {
        if (!$this->tanggal_pelaksanaan) {
            return '-';
        }
        return $this->tanggal_pelaksanaan->format('l, d F Y');
    }
    
    // ========== HELPER METHODS ==========
    
    /**
     * Check if program is selesai
     */
    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }
    
    /**
     * Check if program is aktif (rencana or proses)
     */
    public function isAktif(): bool
    {
        return in_array($this->status, [self::STATUS_RENCANA, self::STATUS_PROSES]);
    }
    
    /**
     * Check if program is batal
     */
    public function isBatal(): bool
    {
        return $this->status === self::STATUS_BATAL;
    }
    
    /**
     * Update status based on date
     */
    public function updateStatusByDate(): void
    {
        if ($this->status === self::STATUS_BATAL || $this->status === self::STATUS_SELESAI) {
            return;
        }
        
        if ($this->tanggal_pelaksanaan && $this->tanggal_pelaksanaan < now()) {
            $this->status = self::STATUS_SELESAI;
            $this->save();
        }
    }
}