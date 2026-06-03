<?php

namespace App\Livewire\Kegiatan\Pam\CapaianPembelajaran;

use App\Models\IndikatorPencapaian;
use App\Models\Kegiatan;
use App\Models\KelompokUser;
use App\Models\Periode;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $role;
    public $periodeFilter = '';
    public $kegiatanID;
    public $jenis = 'PAM';
    public $search = '';
    public $kelompokId = '';
    // Modal
    public $showModal = false;
    // Input
    public $indikatorText;

    protected $listeners = ['deleteAction'];
    protected $paginationTheme = 'bootstrap';

    /**
     * ALERT (Reusable)
     */
    protected function alert($icon, $title, $text)
    {
        $this->dispatch('swal',
            icon: $icon,
            title: $title,
            text: $text);
    }

    /**
     * MOUNT
     */
    public function mount($role)
    {
        if (!in_array($role, ['superadmin', 'prodi', 'mitra'])) {
            abort(403);
        }

        $this->role = $role;

        $this->kegiatanID = Kegiatan::where('nama_kegiatan', $this->jenis)->value('id');

        $active = Periode::where('status', 'AKTIF')->first();
        $this->periodeFilter = $active?->id;

        $data = KelompokUser::where('user_id', auth()->id())
            ->whereHas('kelompok', function ($q) {
                $q
                    ->where('periode_id', $this->periodeFilter)
                    ->where('kegiatan_id', $this->kegiatanID);
            })
            ->first();

        $this->kelompokId = $data?->kelompok_id;
        
        // dd($kelompokId);
    }

    /**
     * MODAL
     */
    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('indikatorText');
    }

    /**
     * SIMPAN
     */
    public function simpan()
    {
        $this->validate([
            'indikatorText' => 'required|min:5'
        ]);

        $rows = explode("\n", $this->indikatorText);

        foreach ($rows as $row) {
            if (trim($row)) {
                IndikatorPencapaian::create([
                    'indikator' => trim($row),
                    'periode_id' => $this->periodeFilter,
                    'kegiatan_id' => $this->kegiatanID,
                    'created_by' => auth()->id(),
                    'role' => auth()->user()->role,
                    'id_prodi' => auth()->user()->id_prodi ?? null,
                    'kelompok_id' => $this->kelompokId ?? null,
                ]);
            }
        }

        $this->closeModal();
        $this->alert('success', 'Berhasil', 'Indikator berhasil ditambahkan');
    }

    /**
     * CONFIRM DELETE
     */
    public function confirmDelete($id)
    {
        $this->dispatch('confirmDelete',
            type: 'indikator',
            id: $id);
    }

    /**
     * HANDLE DELETE (EVENT)
     */
    public function deleteAction($type, $id)
    {
        if ($type === 'indikator') {
            $this->deleteIndikator($id);
        }
    }

    /**
     * DELETE
     */
    public function deleteIndikator($id)
    {
        IndikatorPencapaian::where('id', $id)
            ->where('id_prodi', auth()->user()->id_prodi)
            ->delete();

        $this->alert('success', 'Berhasil!', 'Indikator berhasil dihapus');
    }

    /**
     * RENDER
     */
    public function render()
    {
        $query = IndikatorPencapaian::query();

        if (auth()->user()->role === 'prodi') {
            $query->where('id_prodi', auth()->user()->id_prodi);
        } elseif (auth()->user()->role === 'mitra') {
            $query->where('kelompok_id', $this->kelompokId);
            
        }

        if ($this->periodeFilter) {
            $query->where('periode_id', $this->periodeFilter);
        }

        if ($this->kegiatanID) {
            $query->where('kegiatan_id', $this->kegiatanID);
        }

        // 🔎 LIVE SEARCH (INI YANG KURANG)
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('indikator', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.kegiatan.pam.capaian-pembelajaran.index', [
            'indikators' => $query->latest()->paginate(10),
            'periodes' => Periode::all(),
        ]);
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'periodeFilter'])) {
            $this->resetPage();
        }
    }

    public function updatedPeriodeFilter()
    {
        $this->resetPage();
    }
}
