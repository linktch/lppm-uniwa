<?php

namespace App\Livewire\Kegiatan\Pam\Kehadiran;

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

    public $role;

    public $periodeFilter = '';
    public $kelompok_id;
    public $nama_kelompok;
    public $periode_id;
    public $kegiatan_id;

    public $isModalOpen = false;

    public $search = '';
    public $jenis = 'PAM';

    public $kegiatanID;

    /**
     * MOUNT (WAJIB SESUAI ROUTE)
     */
    public function mount($role)
    {
        if (!in_array($role, ['superadmin', 'prodi', 'mitra'])) {
            abort(403);
        }

        $this->role = $role;

        // ambil ID kegiatan PAM
        $this->kegiatanID = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');
    }

    /**
     * RENDER
     */
    public function render()
    {
        $periodes = Periode::latest()->get();
        $kegiatan = Kegiatan::all();

        // =========================
        // SUPERADMIN
        // =========================
        if ($this->role === 'superadmin') {

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

            $kelompoks = Kelompok::with(['periode', 'kegiatan'])

                ->whereHas('users', function ($q) {
                    $q->where('user_id', auth()->id())
                      ->where('kelompok_user.role', $this->role);
                })

                ->when($this->search, fn($q) =>
                    $q->where('nama_kelompok', 'like', '%' . $this->search . '%')
                )

                ->when($this->periodeFilter, fn($q) =>
                    $q->where('periode_id', $this->periodeFilter)
                )

                ->latest()
                ->paginate(10);

             
                
        }

        return view('livewire.kegiatan.pam.kehadiran.index', [
            'kelompoks' => $kelompoks,
            
            'periodes' => $periodes,
            'kegiatan' => $kegiatan,
            'jenis' => $this->jenis,
        ]);
    }

    /**
     * RESET PAGINATION
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
     * MODAL CREATE / EDIT
     */
    public function openModal($id = null)
    {
        if ($this->role !== 'superadmin') {
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
            $this->kegiatan_id = $this->kegiatanID;
        }

        $this->isModalOpen = true;
    }

    /**
     * CLOSE MODAL
     */
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    /**
     * RESET FORM
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
     * STORE / UPDATE
     */
    public function store()
    {
        if ($this->role !== 'superadmin') {
            return;
        }

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
            $this->kelompok_id
                ? 'Kelompok berhasil diperbarui'
                : 'Kelompok berhasil dibuat'
        );

        $this->closeModal();
        $this->resetForm();
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        if ($this->role !== 'superadmin') {
            return;
        }

        Kelompok::where('id', $id)->delete();

        session()->flash('message', 'Kelompok berhasil dihapus');
    }
}