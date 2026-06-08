<?php

namespace App\Livewire\Laporanharian;

use App\Models\LaporanHarian;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Update extends Component
{
    use WithFileUploads;

    public $laporanId;
    public $laporan;
    public $role;
    public $jenisKegiatan;
    public $tanggal;
    public $jam;
    public $aktivitas;
    public $foto;
    public $oldFoto;
    public $status;
    public $reviews = [];
    
    // Batas waktu revisi
    public $batasWaktuRevisi;
    public $isExpired = false;
    public $sisaJam = 0;
    public $sisaMenit = 0;
    public $canEdit = true;

    public function mount($role, $jenisKegiatan, $id)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->laporanId = $id;
        $this->laporan = LaporanHarian::with(['user', 'kelompok'])
            ->findOrFail($id);

        // Cek apakah user adalah pemilik laporan
        $isOwner = (Auth::id() == $this->laporan->user_id);
        
        // Cek status revisi
        $isRevisi = ($this->laporan->status == 'revisi');
        
        // Cek batas waktu revisi
        $this->checkBatasWaktuRevisi();
        
        // 🔥 JIKA EXPIRED, LANGSUNG REDIRECT KE HALAMAN VIEW
        if ($this->isExpired) {
            session()->flash('error', '⚠️ Batas waktu revisi 24 jam telah berakhir. Anda tidak dapat merevisi laporan ini.');
            return redirect()->route('kegiatan.laporanharian.view', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan,
                'id' => $this->laporanId
            ]);
        }
        
        // Jika bukan owner, redirect
        if (!$isOwner) {
            session()->flash('error', 'Anda tidak memiliki akses untuk merevisi laporan ini');
            return redirect()->route('kegiatan.laporanharian.view', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan,
                'id' => $this->laporanId
            ]);
        }
        
        // Jika status bukan revisi, redirect
        if (!$isRevisi) {
            session()->flash('error', 'Laporan tidak dalam status revisi');
            return redirect()->route('kegiatan.laporanharian.view', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan,
                'id' => $this->laporanId
            ]);
        }

        // Set data form (hanya jika belum expired)
        $this->tanggal = $this->laporan->tanggal ? Carbon::parse($this->laporan->tanggal)->format('Y-m-d') : '';
        $this->jam = $this->laporan->jam ?? date('H:i');
        $this->aktivitas = $this->laporan->aktivitas ?? '';
        $this->oldFoto = $this->laporan->foto;
        $this->status = $this->laporan->status;

        $this->reviews = Review::where('laporan_id', $id)
            ->where('status', 'revisi')
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Cek batas waktu revisi (24 jam setelah review terakhir)
     */
    public function checkBatasWaktuRevisi()
    {
        // Ambil review terakhir dengan status revisi
        $lastReview = Review::where('laporan_id', $this->laporanId)
            ->where('status', 'revisi')
            ->latest()
            ->first();

        if ($lastReview && $lastReview->created_at) {
            $batasWaktu = Carbon::parse($lastReview->created_at)->addHours(24);
            $now = Carbon::now();
            
            $this->batasWaktuRevisi = $batasWaktu;
            
            if ($now->greaterThan($batasWaktu)) {
                $this->isExpired = true;
            } else {
                $this->isExpired = false;
                $diffInMinutes = $now->diffInMinutes($batasWaktu);
                $this->sisaJam = floor($diffInMinutes / 60);
                $this->sisaMenit = $diffInMinutes % 60;
            }
        } else {
            // Jika tidak ada review, beri batas 24 jam dari sekarang
            $batasWaktu = Carbon::now()->addHours(24);
            $this->batasWaktuRevisi = $batasWaktu;
            $this->isExpired = false;
            $this->sisaJam = 24;
            $this->sisaMenit = 0;
        }
    }

    public function updateLaporan()
    {
        // VALIDASI EXPIRED (jaga-jaga)
        if ($this->isExpired) {
            session()->flash('error', '⚠️ Batas waktu revisi 24 jam telah berakhir.');
            return redirect()->route('kegiatan.laporanharian.view', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan,
                'id' => $this->laporanId
            ]);
        }

        $this->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'aktivitas' => 'required|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'jam.required' => 'Jam wajib diisi',
            'aktivitas.required' => 'Aktivitas wajib diisi',
            'aktivitas.min' => 'Aktivitas minimal 10 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            $fotoPath = $this->oldFoto;

            if ($this->foto) {
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $this->foto->store('laporan-harian', 'public');
            }
            
            $dateTime = Carbon::parse($this->tanggal . ' ' . $this->jam);
            $this->laporan->update([
                'tanggal' => $dateTime,
                'jam' => $this->jam,
                'aktivitas' => $this->aktivitas,
                'foto' => $fotoPath,
                'status' => 'submitted',
            ]);

            session()->flash('success', '✅ Laporan berhasil direvisi dan disubmit kembali');
            return redirect()->route('kegiatan.laporanharian.view', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan,
                'id' => $this->laporanId
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function removeFoto()
    {
        if ($this->oldFoto && Storage::disk('public')->exists($this->oldFoto)) {
            Storage::disk('public')->delete($this->oldFoto);
        }
        $this->oldFoto = null;
        $this->foto = null;
        session()->flash('success', 'Foto berhasil dihapus');
    }

    public function back()
    {
        return redirect()->route('kegiatan.laporanharian.view', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'id' => $this->laporanId
        ]);
    }

    public function render()
    {
        return view('livewire.laporanharian.update', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'isExpired' => $this->isExpired,
            'sisaJam' => $this->sisaJam,
            'sisaMenit' => $this->sisaMenit,
            'batasWaktuRevisi' => $this->batasWaktuRevisi,
        ]);
    }
}