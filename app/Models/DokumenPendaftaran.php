<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'dokumen_pendaftaran';

    protected $fillable = [
        'user_id',
        'periode_id',
        'kegiatan_id',
        'jenis_dokumen',
        'label',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'status',
        'keterangan',
        'verified_by',
        'verified_at',
        'is_required',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_required' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_VERIFIED => 'bg-green-100 text-green-700',
            self::STATUS_REJECTED => 'bg-red-100 text-red-700',
            default => 'bg-yellow-100 text-yellow-700',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_REJECTED => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getFormattedFileSizeAttribute()
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