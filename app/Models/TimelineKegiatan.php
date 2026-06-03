<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineKegiatan extends Model
{
    protected $table = 'timeline_kegiatan';
    
    protected $fillable = [
        'periode_id',
        'kegiatan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'jenis',
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Constants for jenis
    const JENIS_PENDAFTARAN = 'Pendaftaran';
    const JENIS_PEMBEKALAN = 'Pembekalan';
    const JENIS_PELAKSANAAN = 'Pelaksanaan';
    const JENIS_PELAPORAN = 'Pelaporan';
    const JENIS_EVALUASI = 'Evaluasi';
    
    const JENIS_OPTIONS = [
        self::JENIS_PENDAFTARAN,
        self::JENIS_PEMBEKALAN,
        self::JENIS_PELAKSANAAN,
        self::JENIS_PELAPORAN,
        self::JENIS_EVALUASI,
    ];
    
    // Constants for status
    const STATUS_AKTIF = 'AKTIF';
    const STATUS_BERAKHIR = 'berakhir';
    
    const STATUS_OPTIONS = [
        self::STATUS_AKTIF,
        self::STATUS_BERAKHIR,
    ];
    
    // Relations
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
    
    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    
    // Accessors for Status
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AKTIF => 'AKTIF',
            self::STATUS_BERAKHIR => 'Berakhir',
            default => 'Tidak Diketahui',
        };
    }
    
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AKTIF => 'success',
            self::STATUS_BERAKHIR => 'danger',
            default => 'secondary',
        };
    }
    
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AKTIF => 'fa-play-circle',
            self::STATUS_BERAKHIR => 'fa-flag-checkered',
            default => 'fa-question-circle',
        };
    }
    
    // Accessors for Jenis
    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            self::JENIS_PENDAFTARAN => 'Pendaftaran',
            self::JENIS_PEMBEKALAN => 'Pembekalan',
            self::JENIS_PELAKSANAAN => 'Pelaksanaan',
            self::JENIS_PELAPORAN => 'Pelaporan',
            self::JENIS_EVALUASI => 'Evaluasi',
            default => 'Tidak Diketahui',
        };
    }
    
    public function getJenisColorAttribute(): string
    {
        return match($this->jenis) {
            self::JENIS_PENDAFTARAN => 'primary',
            self::JENIS_PEMBEKALAN => 'info',
            self::JENIS_PELAKSANAAN => 'success',
            self::JENIS_PELAPORAN => 'warning',
            self::JENIS_EVALUASI => 'danger',
            default => 'secondary',
        };
    }
    
    public function getJenisIconAttribute(): string
    {
        return match($this->jenis) {
            self::JENIS_PENDAFTARAN => 'fa-edit',
            self::JENIS_PEMBEKALAN => 'fa-chalkboard-user',
            self::JENIS_PELAKSANAAN => 'fa-hiking',
            self::JENIS_PELAPORAN => 'fa-file-alt',
            self::JENIS_EVALUASI => 'fa-star',
            default => 'fa-tag',
        };
    }
    
    // Accessors for Duration
    public function getDurationInDaysAttribute(): int
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return 0;
        }
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
    
    public function getDurationTextAttribute(): string
    {
        $days = $this->duration_in_days;
        if ($days == 0) return '-';
        return $days . ' hari';
    }
    
    // Accessors for Date
    public function getTanggalMulaiFormattedAttribute(): string
    {
        return $this->tanggal_mulai ? $this->tanggal_mulai->translatedFormat('d F Y') : '-';
    }
    
    public function getTanggalSelesaiFormattedAttribute(): string
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->translatedFormat('d F Y') : '-';
    }
    
    public function getDateRangeAttribute(): string
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return '-';
        }
        return $this->tanggal_mulai_formatted . ' - ' . $this->tanggal_selesai_formatted;
    }
    
    // Check if timeline is active
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }
    
    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }
    
    public function scopeBerakhir($query)
    {
        return $query->where('status', self::STATUS_BERAKHIR);
    }
    
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
    
    public function scopeByPeriode($query, $periodeId)
    {
        return $query->where('periode_id', $periodeId);
    }
    
    public function scopeByKegiatan($query, $kegiatanId)
    {
        return $query->where('kegiatan_id', $kegiatanId);
    }
    
    // Helper methods
    public static function getJenisOptions(): array
    {
        return self::JENIS_OPTIONS;
    }
    
    public static function getStatusOptions(): array
    {
        return self::STATUS_OPTIONS;
    }
    
    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = self::STATUS_AKTIF;
            }
        });
    }
}