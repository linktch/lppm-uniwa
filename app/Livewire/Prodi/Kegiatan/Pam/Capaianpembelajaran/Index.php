<?php

namespace App\Livewire\Prodi\Kegiatan\Pam\Capaianpembelajaran;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Kelompok;
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

    public $kegiatanID; // 🔥 cache ID kegiatan

    /**
     * Mount (ambil data sekali saja)
     */
    public function mount()
    {
        // dd($this->periodeFilter);
        $this->kegiatanID = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');
    }

    /**
     * Render data
     */
    public function render()
    {
        $periodes = Periode::orderBy('created_at', 'desc')->get();
        $kegiatan = Kegiatan::all();

        $user = auth()->user();

        // =========================
        // SUPERADMIN
        // =========================
        if (auth()->check() && $user->hasRole('superadmin')) {

            $kelompoks = Kelompok::with(['periode', 'kegiatan'])

                ->when($this->kegiatanID, function ($q) {
                    $q->where('kegiatan_id', $this->kegiatanID);
                })

                ->when($this->search, function ($q) {
                    $q->where('nama_kelompok', 'like', '%' . $this->search . '%');
                })

                ->when($this->periodeFilter, function ($q) {
                    $q->where('periode_id', $this->periodeFilter);
                })

                ->orderBy('created_at', 'desc')
                ->paginate(10);

        }

        // =========================
        // NON SUPERADMIN (PRODI)
        // =========================
        else {

            $kelompoks = Kelompok::with(['periode', 'kegiatan'])

                ->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                    ->where('kelompok_user.role', 'prodi');
                })

                ->when($this->search, function ($q) {
                    $q->where('nama_kelompok', 'like', '%' . $this->search . '%');
                })

                ->when($this->periodeFilter, function ($q) {
                    $q->where('periode_id', $this->periodeFilter);
                })

                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.prodi.kegiatan.pam.capaianpembelajaran.index', [
            'kelompoks' => $kelompoks,
            'periodes' => $periodes,
            'kegiatan' => $kegiatan,
            'jenis' => $this->jenis,
        ]);
    }

    /**
     * Reset pagination saat search/filter
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPeriodeFilter()
    {
        $this->resetPage();
    }

    /**
     * Open modal (create/edit)
     */
    public function openModal($id = null)
    {
        // 🔥 security check
        if (!auth()->check() || !auth()->user()->hasRole('superadmin')) {
            return;
        }

        $this->resetForm();

        if ($id) {
            $kel = Kelompok::find($id);

            if ($kel) {
                $this->kelompok_id = $kel->id;
                $this->nama_kelompok = $kel->nama_kelompok;
                $this->periode_id = $kel->periode_id;
                $this->kegiatan_id = $kel->kegiatan_id;
            }
        } else {
            // default kegiatan PAM
            $this->kegiatan_id = $this->kegiatanID;
        }

        $this->isModalOpen = true;
    }

    /**
     * Close modal
     */
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    /**
     * Reset form
     */
    private function resetForm()
    {
        $this->reset([
            'kelompok_id',
            'nama_kelompok',
            'periode_id',
            'kegiatan_id'
        ]);
    }

    /**
     * Store / Update
     */
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

    /**
     * Delete
     */
    public function delete($id)
    {
        if (!auth()->check() || !auth()->user()->hasRole('superadmin')) {
            return;
        }

        Kelompok::where('id', $id)->delete();

        session()->flash('message', 'Kelompok berhasil dihapus');
    }
}

