<?php

namespace App\Livewire\Kegiatan\Pam\Kelompok;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Kelompok;
use App\Models\User;

#[Layout('layouts.app')]
class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $kelompok;
    public $jenis = "PAM";

    public $modalActive = null;
    public $search = '';
    public $showDropdown = false;
    public $selectedId = null;
    public $listItems = [];
    public $perPage = 10;
    public $modalActivePAM = null;

    // ✅ TIDAK DIUBAH
    protected $listeners = ['deleteAction' => 'deleteAction'];

    // =====================
    // SWEET ALERT HELPER (TAMBAHAN)
    // =====================
    protected function alert($icon, $title, $text)
    {
        $this->dispatch('swal', [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
        ]);
    }

    // =====================
    // ROLE CHECK (TAMBAHAN)
    // =====================
    private function isSuperAdmin()
    {
        return auth()->user()->role === 'superadmin';
    }

    // =====================
    // INIT
    // =====================
    public function mount($kelompokID)
    {
        $this->kelompok = Kelompok::with([
            'periode',
            'kegiatan',
            'tim',
            'kemahasiswaan',
            'mahasiswa',
            'prodi',
            'mitra'
        ])->findOrFail($kelompokID);
    }

    // =====================
    // MODAL CONTROL
    // =====================
    public function openModal($type)
    {
        $this->reset(['search', 'selectedId', 'listItems', 'showDropdown']);
        $this->modalActive = $type;
    }

    public function closeModal()
    {
        $this->modalActive = null;
        $this->reset(['search', 'selectedId', 'listItems', 'showDropdown']);
    }

    // =====================
    // PILIH ITEM
    // =====================
    public function pilihItem($kelompokID, $modalActive, $item = null)
    {
        $this->selectedId = $kelompokID;

        if ($modalActive === 'mahasiswa' && $item) {
            $data = json_decode($item['data_mahasiswa'] ?? null, true);
            $this->search = ($data['nama_mahasiswa'] ?? '-') 
                        . ' - ' 
                        . ($data['nim'] ?? '-') 
                        . ' (' 
                        . ($data['nama_program_studi'] ?? '-') 
                        . ')';
        } else {
            $this->search = ($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? '') 
                  . ' - ' . ($item['username'] ?? '-') 
                  . ' - ' . ($item['role'] ?? '-');
        }

        $this->showDropdown = false;
    }

    // =====================
    // TAMBAH ITEM
    // =====================
    public function tambahItem()
    {
        if (!$this->selectedId || !$this->modalActive) return;

        $relationMap = [
            'mahasiswa' => 'mahasiswa',
            'kemahasiswaan' => 'kemahasiswaan',
            'tim' => 'tim',
            'prodi' => 'prodi',
            'mitra' => 'mitra',
        ];

        $relation = $relationMap[$this->modalActive] ?? null;

        if (!$relation) return;

        if (!$this->isSuperAdmin()) {
            $this->alert('error', 'Akses Ditolak', 'Anda tidak punya akses Simpan Role ini');
            $this->closeModal();
            return;
        }
        $this->kelompok->{$relation}()->syncWithoutDetaching([
            $this->selectedId => ['role' => $relation]
        ]);

        $this->kelompok->load($relation);
        
        $this->alert('success', 'Berhasil', ucfirst($relation).' berhasil ditambahkan');
        $this->closeModal();

    }

    // =====================
    // DELETE ACTION (FIXED FULL)
    // =====================
    public function deleteAction($type, $id)
    {
        // ❌ hanya superadmin
        if (!$this->isSuperAdmin()) {
            $this->alert('error', 'Akses Ditolak', 'Anda tidak punya akses delete');
            return;
        }

        switch ($type) {
            case 'mahasiswa':
                $this->kelompok->mahasiswa()->detach($id);
                break;

            case 'tim':
                $this->kelompok->tim()->detach($id);
                break;

            case 'kemahasiswaan':
                $this->kelompok->kemahasiswaan()->detach($id);
                break;

            case 'prodi':
                $this->kelompok->prodi()->detach($id);
                break;

            case 'mitra':
                $this->kelompok->mitra()->detach($id);
                break;
        }

        $this->kelompok->load($type);

        $this->alert('success', 'Berhasil', ucfirst($type).' berhasil dihapus');
    }

    // =====================
    // LIVE SEARCH
    // =====================
    public function updatedSearch()
    {
        $this->showDropdown = true;

        if (!$this->modalActive) return;

        $this->listItems = User::where('role', $this->modalActive)
            ->where('first_name', 'like', "%{$this->search}%")
            ->get();
    }

    // =====================
    // RENDER
    // =====================
    public function render()
    {
        return view('livewire.kegiatan.pam.kelompok.detail', [
            'mahasiswa' => $this->kelompok->mahasiswa()->paginate($this->perPage),
            'kemahasiswaan' => $this->kelompok->kemahasiswaan()->paginate($this->perPage),
            'teams' => $this->kelompok->tim()->paginate($this->perPage),
            'prodi' => $this->kelompok->prodi()->paginate($this->perPage),
            'mitra' => $this->kelompok->mitra()->paginate($this->perPage),
        ]);
    }
}