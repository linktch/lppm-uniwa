<?php

namespace App\Services;

use App\Models\Periode;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class KKNService
{
    public static function periode()
    {
        return Cache::remember('periode_aktif', 300, function () {
            return Periode::whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_selesai', '>=', now())
                ->first();
        });
    }

    public static function kegiatan($nama = 'KKN')
    {
        return Cache::remember("kegiatan_{$nama}", 300, function () use ($nama) {
            return Kegiatan::where('nama_kegiatan', $nama)->first();
        });
    }

    public static function periodeId()
    {
        return self::periode()?->id;
    }

    public static function kegiatanId($nama = 'KKN')
    {
        return self::kegiatan($nama)?->id;
    }

    public static function weekRange($week = 1)
    {
        $periode = self::periode();

        if (!$periode) return [null, null];

        $start = Carbon::parse($periode->tanggal_mulai);

        $monday = $start->copy()->addWeeks($week - 1);
        $thursday = $monday->copy()->addDays(3);

        return [
            $monday->format('Y-m-d'),
            $thursday->format('Y-m-d'),
        ];
    }
}