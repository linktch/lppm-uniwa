<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKelompok extends Model
{
    use HasFactory;

    protected $table = 'dokumen_kelompok';

    protected $fillable = [
        'kelompok_id',
        'periode_id',
        'kegiatan_id',
        'user_id',
        'jenis',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'judul',
        'konten',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    // Jenis dokumen constants (opsional, untuk referensi)
    const JENIS_PROGRAM_KERJA = 'program_kerja';
    const JENIS_LAPORAN_AKHIR = 'laporan_akhir';
    const JENIS_ARTIKEL = 'artikel';
    const JENIS_BERKAS_PENDUKUNG = 'berkas_pendukung';

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    /**
     * Get jenis dokumen options
     */
    public static function getJenisOptions()
    {
        return [
            self::JENIS_PROGRAM_KERJA => 'Program Kerja',
            self::JENIS_LAPORAN_AKHIR => 'Laporan Akhir',
            self::JENIS_ARTIKEL => 'Artikel',
            self::JENIS_BERKAS_PENDUKUNG => 'Berkas Pendukung',
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'bg-yellow-100 text-yellow-700',
            self::STATUS_PUBLISHED => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get jenis label
     */
    public function getJenisLabel()
    {
        return match ($this->jenis) {
            self::JENIS_PROGRAM_KERJA => 'Program Kerja',
            self::JENIS_LAPORAN_AKHIR => 'Laporan Akhir',
            self::JENIS_ARTIKEL => 'Artikel',
            self::JENIS_BERKAS_PENDUKUNG => 'Berkas Pendukung',
            default => ucfirst(str_replace('_', ' ', $this->jenis)),
        };
    }

    /**
     * Get jenis icon
     */
    public function getJenisIcon()
    {
        return match ($this->jenis) {
            self::JENIS_PROGRAM_KERJA => 'fa-tasks',
            self::JENIS_LAPORAN_AKHIR => 'fa-file-alt',
            self::JENIS_ARTIKEL => 'fa-newspaper',
            self::JENIS_BERKAS_PENDUKUNG => 'fa-paperclip',
            default => 'fa-file',
        };
    }

    /**
     * Get jenis color
     */
    public function getJenisColor()
    {
        return match ($this->jenis) {
            self::JENIS_PROGRAM_KERJA => 'blue',
            self::JENIS_LAPORAN_AKHIR => 'green',
            self::JENIS_ARTIKEL => 'purple',
            self::JENIS_BERKAS_PENDUKUNG => 'pink',
            default => 'gray',
        };
    }

    // ==================== RELATIONS ====================

    /**
     * Relasi ke Kelompok
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    /**
     * Relasi ke Periode
     */
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    /**
     * Relasi ke Kegiatan
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    /**
     * Relasi ke User (uploader)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk filter berdasarkan jenis
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan kelompok
     */
    public function scopeByKelompok($query, $kelompokId)
    {
        return $query->where('kelompok_id', $kelompokId);
    }

    /**
     * Scope untuk filter berdasarkan status published
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope untuk filter berdasarkan status draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Cek apakah dokumen sudah dipublish
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Cek apakah dokumen masih draft
     */
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Get file url
     */
    public function getFileUrl()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSize()
    {
        if (!$this->file_size) return '-';
        
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}