<?php

namespace App\Livewire\Dashboard;

use App\Models\Kegiatan;
use App\Models\KelompokUser;
use App\Models\LaporanHarian;
use App\Models\Periode;
use App\Models\Sertifikat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public $role;
    public $periode_id;
    public $kegiatan_id;
    public $namaKegiatan;
    
    // Data mahasiswa
    public $kelompokUser = null;
    public $kelompok = null;
    public $userData = [];
    
    // Statistik mahasiswa
    public $totalLaporan = 0;
    public $laporanDisetujui = 0;
    public $laporanRevisi = 0;
    public $laporanPending = 0;
    public $sertifikat = null;
    public $laporanTerbaru = [];
    public $aktivitasTerbaru = [];
    
    // Untuk Superadmin
    public $totalMahasiswa = 0;
    public $totalDosen = 0;
    public $totalKelompok = 0;
    public $totalKegiatan = 0;
    public $statistikPerKegiatan = [];
    public $laporanPerHari = [];

    public function mount()
    {
        $this->role = Auth::user()->role;
        
        if ($this->role == 'superadmin') {
            $this->loadSuperadminData();
        } else {
            $this->loadData();
        }
    }

    /**
     * Load data untuk Superadmin
     */
    public function loadSuperadminData()
    {
        // Statistik utama
        $this->totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $this->totalDosen = User::where('role', 'dosen')->count();
        $this->totalKelompok = KelompokUser::where('role', 'mahasiswa')->distinct('kelompok_id')->count('kelompok_id');
        $this->totalKegiatan = Kegiatan::count();
        
        // Statistik laporan keseluruhan
        $this->totalLaporan = LaporanHarian::count();
        $this->laporanDisetujui = LaporanHarian::where('status', 'approved')->count();
        $this->laporanRevisi = LaporanHarian::where('status', 'revisi')->count();
        $this->laporanPending = LaporanHarian::where('status', 'submitted')->count();
        
        // Statistik per kegiatan
        $kegiatans = Kegiatan::all();
        $this->statistikPerKegiatan = $kegiatans->map(function($kegiatan) {
            return [
                'nama' => $kegiatan->nama_kegiatan,
                'total' => LaporanHarian::where('kegiatan_id', $kegiatan->id)->count(),
                'approved' => LaporanHarian::where('kegiatan_id', $kegiatan->id)->where('status', 'approved')->count(),
                'revisi' => LaporanHarian::where('kegiatan_id', $kegiatan->id)->where('status', 'revisi')->count(),
                'pending' => LaporanHarian::where('kegiatan_id', $kegiatan->id)->where('status', 'submitted')->count(),
            ];
        });
        
        // Aktivitas terbaru
        $this->aktivitasTerbaru = LaporanHarian::with(['user', 'kegiatan'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($laporan) {
                $userData = is_string($laporan->user->data_mahasiswa) 
                    ? json_decode($laporan->user->data_mahasiswa, true) 
                    : ($laporan->user->data_mahasiswa ?? []);
                $nama = $userData['nama_mahasiswa'] ?? $laporan->user->name ?? 'Mahasiswa';
                
                return [
                    'pesan' => "{$nama} mengirim laporan - " . ($laporan->kegiatan->nama_kegiatan ?? 'KKN'),
                    'waktu' => $laporan->created_at->diffForHumans(),
                    'icon' => 'fa-file-alt',
                    'status' => $laporan->status
                ];
            })->toArray();
        
        // Laporan terbaru untuk tabel
        $this->laporanTerbaru = LaporanHarian::with(['user', 'kelompok', 'kegiatan'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($laporan) {
                $userData = is_string($laporan->user->data_mahasiswa) 
                    ? json_decode($laporan->user->data_mahasiswa, true) 
                    : ($laporan->user->data_mahasiswa ?? []);
                    
                return [
                    'id' => $laporan->id,
                    'tanggal' => $laporan->tanggal ? Carbon::parse($laporan->tanggal)->format('d/m/Y') : '-',
                    'mahasiswa' => $userData['nama_mahasiswa'] ?? $laporan->user->name ?? '-',
                    'kelompok' => $laporan->kelompok->nama_kelompok ?? '-',
                    'kegiatan' => $laporan->kegiatan->nama_kegiatan ?? '-',
                    'status' => $laporan->status,
                ];
            })->toArray();
        
        // Laporan per hari (untuk chart)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = LaporanHarian::whereDate('tanggal', $date)->count();
            $this->laporanPerHari[] = [
                'tanggal' => $date->format('D'),
                'jumlah' => $count
            ];
        }
    }

    /**
     * Load data untuk mahasiswa
     */
    public function loadData()
    {
        // Ambil periode aktif
        $periodeAktif = Periode::where('status', 'AKTIF')->first();

        if ($periodeAktif) {
            $this->kelompokUser = KelompokUser::with(['kelompok', 'kelompok.kegiatan', 'user'])
                ->where('user_id', Auth::id())
                ->where('role', 'mahasiswa')
                ->whereHas('kelompok', function ($query) use ($periodeAktif) {
                    $query->where('periode_id', $periodeAktif->id);
                })
                ->first();
        }

        if ($this->kelompokUser && $this->kelompokUser->kelompok) {
            $this->kelompok = $this->kelompokUser->kelompok;

            // Ambil kegiatan dari kelompok
            $kegiatan = $this->kelompok->kegiatan;
            if ($kegiatan) {
                $this->kegiatan_id = $kegiatan->id;
                $this->namaKegiatan = $kegiatan->nama_kegiatan;
            }

            // Ambil periode dari kelompok
            $this->periode_id = $this->kelompok->periode_id;

            // Parse data mahasiswa
            $user = $this->kelompokUser->user;
            $this->userData = is_string($user->data_mahasiswa)
                ? json_decode($user->data_mahasiswa, true)
                : ($user->data_mahasiswa ?? []);

            // Load statistik laporan
            $this->loadStatistik();

            // Load laporan terbaru
            $this->loadLaporanTerbaru();

            // Load sertifikat
            $this->loadSertifikat();
        }
    }

    public function loadStatistik()
    {
        $userId = Auth::id();

        $this->totalLaporan = LaporanHarian::where('user_id', $userId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->count();

        $this->laporanDisetujui = LaporanHarian::where('user_id', $userId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('status', 'approved')
            ->count();

        $this->laporanRevisi = LaporanHarian::where('user_id', $userId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('status', 'revisi')
            ->count();

        $this->laporanPending = LaporanHarian::where('user_id', $userId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->where('status', 'submitted')
            ->count();
    }

    public function loadLaporanTerbaru()
    {
        $userId = Auth::id();

        $this->laporanTerbaru = LaporanHarian::where('user_id', $userId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($laporan) {
                return [
                    'id' => $laporan->id,
                    'tanggal' => $laporan->tanggal ? Carbon::parse($laporan->tanggal)->format('d/m/Y') : '-',
                    'aktivitas' => substr($laporan->aktivitas, 0, 60) . (strlen($laporan->aktivitas) > 60 ? '...' : ''),
                    'status' => $laporan->status,
                ];
            })
            ->toArray();

        $this->aktivitasTerbaru = LaporanHarian::where('user_id', $userId)
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($laporan) {
                $statusText = $laporan->status == 'approved' ? 'Disetujui' : ($laporan->status == 'revisi' ? 'Perlu Revisi' : 'Menunggu Review');
                return [
                    'pesan' => 'Laporan tanggal ' . Carbon::parse($laporan->tanggal)->format('d/m/Y') . ' - ' . $statusText,
                    'waktu' => $laporan->created_at->diffForHumans(),
                    'icon' => $laporan->status == 'approved' ? 'fa-check-circle' : ($laporan->status == 'revisi' ? 'fa-undo-alt' : 'fa-clock'),
                    'status' => $laporan->status
                ];
            })
            ->toArray();
    }

    public function loadSertifikat()
    {
        $this->sertifikat = Sertifikat::where('user_id', Auth::id())
            ->where('periode_id', $this->periode_id)
            ->where('kegiatan_id', $this->kegiatan_id)
            ->first();
    }

    public function render()
    {
        return view('livewire.dashboard.index', [
            'role' => $this->role,
        ]);
    }
}