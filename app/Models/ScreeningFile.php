<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'periode_id',
        'kegiatan_id',

        // file
        'file_surat',
        'file_ktp',
        'file_spp',

        // status per file
        'status_surat',
        'status_ktp',
        'status_spp',

        // keterangan per file
        'keterangan_surat',
        'keterangan_ktp',
        'keterangan_spp',
    ];

    protected $casts = [
        'status_surat' => 'string',
        'status_ktp'   => 'string',
        'status_spp'   => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // 🔥 cek semua file valid
    public function isAllValid()
    {
        return
            $this->status_surat === 'valid' &&
            $this->status_ktp === 'valid' &&
            $this->status_spp === 'valid';
    }

    // 🔥 cek ada yang ditolak
    public function hasRejected()
    {
        return
            $this->status_surat === 'ditolak' ||
            $this->status_ktp === 'ditolak' ||
            $this->status_spp === 'ditolak';
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO AKTIVASI USER
    |--------------------------------------------------------------------------
    */

    // protected static function booted()
    // {
    //     static::updated(function ($model) {
    //         if ($model->isAllValid()) {
    //             $model->user()->update([
    //                 'is_active' => 1
    //             ]);
    //         }
    //     });
    // }
}