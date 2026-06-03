<?php

namespace App\Livewire\Kegiatan\Pam;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\Periode;
use App\Models\Kegiatan;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $periodeFilter = '';
    public $kelompok_id;
    public $nama_kelompok;
    public $periode_id;
    public $kegiatan_id;

    public $isModalOpen = false;

    public $search = '';
    public $jenis = 'PAM';

    public $kegiatanID;
    public $kelompokMitra;

    public function mount()
    {
        $this->kegiatanID = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');
    }

    public function render()
    {
        
        $user = auth()->user();

        $periodes = Periode::latest()->get();
        $kegiatan = Kegiatan::all();

        // =========================
        // SUPERADMIN
        // =========================
        if ($user->hasRole('superadmin')) {

            $kelompoks = Kelompok::with(['periode', 'kegiatan'])
                ->when($this->kegiatanID, fn($q) =>
                    $q->where('kegiatan_id', $this->kegiatanID)
                )
                ->when($this->search, fn($q) =>
                    $q->where('nama_kelompok', 'like', '%' . $this->search . '%')
                )
                ->when($this->periodeFilter, fn($q) =>
                    $q->where('periode_id', $this->periodeFilter)
                )
                ->latest()
                ->paginate(10);
        }

        // =========================
        // PRODI / MITRA
        // =========================
        else {

            // 🔹 Kelompok yang dimiliki user
            $kelompoks = Kelompok::with(['periode', 'kegiatan', 'mahasiswa'])
                ->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereIn('kelompok_user.role', ['prodi', 'mitra', 'mahasiswa']);
                })
                ->when($this->search, fn($q) =>
                    $q->where('nama_kelompok', 'like', '%' . $this->search . '%')
                )
                ->when($this->periodeFilter, fn($q) =>
                    $q->where('periode_id', $this->periodeFilter)
                )
                ->latest()
                ->paginate(10);

            // 🔹 Kelompok aktif user
            $periodeAktifId = Periode::where('status', 'AKTIF')->value('id');

            $kelompokIds = Kelompok::where('periode_id', $periodeAktifId)
                ->where('kegiatan_id', $this->kegiatanID)
                ->pluck('id');

            $kelompokMitraIds = KelompokUser::where('user_id', $user->id)
                ->whereIn('role', ['mitra', 'prodi', 'mahasiswa' ]) // 🔥 FIX orWhere bug
                ->whereIn('kelompok_id', $kelompokIds)
                ->pluck('kelompok_id');
                

            $this->kelompokMitra = Kelompok::with('periode')
                ->whereIn('id', $kelompokMitraIds)
                ->first();
        }

        return view('livewire.kegiatan.pam.index', [
            'kelompoks'   => $kelompoks,
            'kelompokID'  => $this->kelompokMitra,
            'periodes'    => $periodes,
            'kegiatan'    => $kegiatan,
            'jenis'       => $this->jenis,
        ]);
    }

    // =========================
    // HELPER
    // =========================

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPeriodeFilter()
    {
        $this->resetPage();
    }

    // =========================
    // MODAL
    // =========================

    public function openModal($id = null)
    {
        if (!auth()->user()->hasRole('superadmin')) return;

        $this->resetForm();

        if ($id) {
            $kel = Kelompok::find($id);

            if ($kel) {
                $this->kelompok_id   = $kel->id;
                $this->nama_kelompok = $kel->nama_kelompok;
                $this->periode_id    = $kel->periode_id;
                $this->kegiatan_id   = $kel->kegiatan_id;
            }
        } else {
            $this->kegiatan_id = $this->kegiatanID;
        }

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetForm()
    {
        $this->reset([
            'kelompok_id',
            'nama_kelompok',
            'periode_id',
            'kegiatan_id'
        ]);
    }

    // =========================
    // CRUD
    // =========================

    public function store()
    {
        $this->validate([
            'nama_kelompok' => 'required|string|max:255',
            'periode_id'    => 'required|exists:periodes,id',
            'kegiatan_id'   => 'required|exists:kegiatans,id',
        ]);

        Kelompok::updateOrCreate(
            ['id' => $this->kelompok_id],
            [
                'nama_kelompok' => $this->nama_kelompok,
                'periode_id'    => $this->periode_id,
                'kegiatan_id'   => $this->kegiatan_id,
            ]
        );

        session()->flash(
            'message',
            $this->kelompok_id ? 'Kelompok berhasil diperbarui' : 'Kelompok berhasil dibuat'
        );

        $this->closeModal();
        $this->resetForm();
    }

    public function delete($id)
    {
        if (!auth()->user()->hasRole('superadmin')) return;

        Kelompok::where('id', $id)->delete();

        session()->flash('message', 'Kelompok berhasil dihapus');
    }
}