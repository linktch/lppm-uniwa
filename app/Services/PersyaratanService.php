<?php

namespace App\Services;

class PersyaratanService
{
    /**
     * Get persyaratan dokumen berdasarkan jenis kegiatan
     */
    public static function getPersyaratan($jenisKegiatan)
    {
        $persyaratan = [
            'KKN' => [
                'ktp' => [
                    'label' => 'KTP',
                    'icon' => 'fa-id-card',
                    'required' => true,
                    'description' => 'Scan KTP (max 2MB, PDF/JPG/PNG)',
                ],
                'surat_sehat' => [
                    'label' => 'Surat Keterangan Sehat',
                    'icon' => 'fa-heartbeat',
                    'required' => true,
                    'description' => 'Surat sehat dari dokter (max 2MB)',
                ],
                'bukti_pembayaran' => [
                    'label' => 'Bukti Pembayaran',
                    'icon' => 'fa-money-bill-wave',
                    'required' => true,
                    'description' => 'Bukti transfer/pembayaran (max 2MB)',
                ],
            ],
            'PKM' => [
                'transkrip_nilai' => [
                    'label' => 'Transkrip Nilai',
                    'icon' => 'fa-graduation-cap',
                    'required' => true,
                    'description' => 'Transkrip nilai semester terakhir (max 2MB)',
                ],
                'sk_yudisium' => [
                    'label' => 'SK Yudisium',
                    'icon' => 'fa-scroll',
                    'required' => true,
                    'description' => 'SK Yudisium (max 2MB)',
                ],
                'surat_pernyataan' => [
                    'label' => 'Surat Pernyataan Bebas Tunggakan',
                    'icon' => 'fa-file-signature',
                    'required' => true,
                    'description' => 'Surat pernyataan bebas tunggakan (max 2MB)',
                ],
                'proposal' => [
                    'label' => 'Proposal PKM',
                    'icon' => 'fa-file-alt',
                    'required' => false,
                    'description' => 'Proposal PKM (opsional, max 2MB)',
                ],
            ],
            'PAM' => [
                'foto' => [
                    'label' => 'Foto Terbaru',
                    'icon' => 'fa-camera',
                    'required' => true,
                    'description' => 'Foto berwarna terbaru (max 2MB, JPG/PNG)',
                ],
                'cv' => [
                    'label' => 'CV / Daftar Riwayat Hidup',
                    'icon' => 'fa-file-pdf',
                    'required' => true,
                    'description' => 'CV atau daftar riwayat hidup (max 2MB)',
                ],
                'surat_rekomendasi' => [
                    'label' => 'Surat Rekomendasi',
                    'icon' => 'fa-envelope',
                    'required' => false,
                    'description' => 'Surat rekomendasi dari dosen (opsional)',
                ],
            ],
        ];
        
        return $persyaratan[$jenisKegiatan] ?? $persyaratan['KKN'];
    }
    
    /**
     * Get required documents only
     */
    public static function getRequiredPersyaratan($jenisKegiatan)
    {
        $all = self::getPersyaratan($jenisKegiatan);
        return array_filter($all, function ($item) {
            return $item['required'] === true;
        });
    }
    
    /**
     * Get optional documents only
     */
    public static function getOptionalPersyaratan($jenisKegiatan)
    {
        $all = self::getPersyaratan($jenisKegiatan);
        return array_filter($all, function ($item) {
            return $item['required'] === false;
        });
    }
}