<?php

namespace App\Services;

use App\Models\Kegiatan;
use App\Models\Periode;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KegiatanService
{
    /**
     * Get kegiatan ID by nama kegiatan
     */
    public static function getKegiatanId($jenisKegiatan)
    {
        $cacheKey = "kegiatan_id_{$jenisKegiatan}";
        
        return Cache::remember($cacheKey, 3600, function () use ($jenisKegiatan) {
            $kegiatan = Kegiatan::where('nama_kegiatan', $jenisKegiatan)->first();
            
            if (!$kegiatan) {
                Log::warning("Kegiatan not found: {$jenisKegiatan}");
                return null;
            }
            
            return $kegiatan->id;
        });
    }
    
    /**
     * Get kegiatan by nama kegiatan
     */
    public static function getKegiatan($jenisKegiatan)
    {
        return Kegiatan::where('nama_kegiatan', $jenisKegiatan)->first();
    }
    
    /**
     * Get all kegiatan
     */
    public static function getAllKegiatan()
    {
        return Kegiatan::all();
    }
    
    /**
     * Get periode aktif (status = AKTIF)
     */
    public static function getPeriodeAktif()
    {
        $cacheKey = "periode_aktif";
        
        return Cache::remember($cacheKey, 3600, function () {
            return Periode::where('status', 'AKTIF')->first();
        });
    }
    
    /**
     * Get periode ID aktif
     */
    public static function getPeriodeId()
    {
        $periode = self::getPeriodeAktif();
        return $periode ? $periode->id : null;
    }
    
    /**
     * Get kelompok by user (mahasiswa)
     */
    public static function getKelompokByUser($userId, $periodeId, $kegiatanId)
    {
        // Cari melalui tabel kelompok_user
        $kelompokUser = KelompokUser::where('user_id', $userId)
            ->where('role', 'mahasiswa')
            ->first();
        
        if (!$kelompokUser) {
            return null;
        }
        
        // Cek kelompok sesuai periode dan kegiatan
        return Kelompok::where('id', $kelompokUser->kelompok_id)
            ->where('periode_id', $periodeId)
            ->where('kegiatan_id', $kegiatanId)
            ->first();
    }
    
    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget("periode_aktif");
        Cache::forget("kegiatan_id_KKN");
        Cache::forget("kegiatan_id_PKM");
        Cache::forget("kegiatan_id_PAM");
    }
}