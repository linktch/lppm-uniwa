<?php

namespace App\Livewire\Kelompok;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\Periode;
use App\Services\KKNService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // 🔥 Data dari Service
    public $kegiatanID;
    public $periodeFilter;
    public $jenisKegiatan; // TAMBAHKAN PROPERTY JENIS KEGIATAN
    public $role; // TAMBAHKAN PROPERTY ROLE
    
    // 🔥 Filter & Search
    public $search = '';
    public $selectedPeriode = '';
    public $selectedKegiatan = '';
    
    // 🔥 Modal Properties
    public $showModal = false;
    public $isEditing = false;
    public $kelompok_id;
    public $nama_kelompok;
    public $periode_id;
    public $kegiatan_id;
    public $lokasi;

    protected $listeners = ['deleteConfirmed' => 'deleteKelompok'];

    protected function alert($icon, $title, $text)
    {
        $this->dispatch('swal', [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
        ]);
    }

    public function mount($role, $jenisKegiatan = 'KKN') // TAMBAHKAN PARAMETER
    {
        $this->role = $role; // SIMPAN ROLE
        $this->jenisKegiatan = $jenisKegiatan; // SIMPAN JENIS KEGIATAN
        
        // Tentukan kegiatan ID berdasarkan jenis kegiatan
        $this->kegiatanID = $this->getKegiatanId($jenisKegiatan);
        $this->periodeFilter = $this->getPeriodeId($jenisKegiatan);
        
        $aktif = Periode::where('status', 'AKTIF')->first();
        $this->selectedPeriode = $aktif?->id;
        $this->selectedKegiatan = $this->kegiatanID;
    }
    
    // TAMBAHKAN METHOD untuk mendapatkan kegiatan ID berdasarkan jenis
    private function getKegiatanId($jenisKegiatan)
    {
        switch ($jenisKegiatan) {
            case 'KKN':
                return KKNService::kegiatanId('KKN');
            case 'PKL':
                return KKNService::kegiatanId('PKL');
            case 'PMM':
                return KKNService::kegiatanId('PMM');
            default:
                return KKNService::kegiatanId('KKN');
        }
    }
    
    // TAMBAHKAN METHOD untuk mendapatkan periode ID berdasarkan jenis
    private function getPeriodeId($jenisKegiatan)
    {
        switch ($jenisKegiatan) {
            case 'KKN':
                return KKNService::periodeId();
            case 'PKL':
                return KKNService::periodeIdPkl();
            default:
                return KKNService::periodeId();
        }
    }

    public function render()
    {
        // 🔥 Ambil data untuk dropdown filter
        $periodes = Periode::all();
        $kegiatans = Kegiatan::all();

        // 🔥 Query kelompok dengan filter
        $kelompoks = Kelompok::with(['periode', 'kegiatan'])
            ->when($this->kegiatanID, function ($query) {
                $query->where('kegiatan_id', $this->kegiatanID);
            })
            ->when($this->search, function ($query) {
                $query->where('nama_kelompok', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedPeriode, function ($query) {
                $query->where('periode_id', $this->selectedPeriode);
            })
            ->when($this->selectedKegiatan, function ($query) {
                $query->where('kegiatan_id', $this->selectedKegiatan);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.kelompok.index', [
            'periodes' => $periodes,
            'kegiatans' => $kegiatans,
            'kelompoks' => $kelompoks,
            'role' => $this->role, // KIRIM KE VIEW
            'jenisKegiatan' => $this->jenisKegiatan, // KIRIM KE VIEW
        ]);
    }

    // 🔥 Buka modal tambah
    public function openModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->kegiatan_id = $this->kegiatanID;
        $this->showModal = true;
    }

    // 🔥 Edit kelompok
    public function editKelompok($id)
    {
        $kelompok = Kelompok::findOrFail($id);

        $this->kelompok_id = $kelompok->id;
        $this->nama_kelompok = $kelompok->nama_kelompok;
        $this->periode_id = $kelompok->periode_id;
        $this->kegiatan_id = $kelompok->kegiatan_id;
        $this->lokasi = $kelompok->lokasi; // TAMBAHKAN
        $this->isEditing = true;
        $this->showModal = true;
    }

    // 🔥 Simpan (create/update)
    public function save()
    {
        $this->validate([
            'nama_kelompok' => 'required|string|max:255',
            'periode_id' => 'required|exists:periode,id',
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'lokasi' => 'required|string|max:500',
        ]);

        try {
            if ($this->isEditing) {
                $kelompok = Kelompok::findOrFail($this->kelompok_id);
                $kelompok->update([
                    'nama_kelompok' => $this->nama_kelompok,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'lokasi' => $this->lokasi,
                ]);

                $this->alert('success', 'Berhasil', 'Kelompok berhasil diupdate');
            } else {
                Kelompok::create([
                    'nama_kelompok' => $this->nama_kelompok,
                    'periode_id' => $this->periode_id,
                    'kegiatan_id' => $this->kegiatan_id,
                    'lokasi' => $this->lokasi,
                ]);

                $this->alert('success', 'Berhasil', 'Kelompok berhasil ditambahkan');
            }

            $this->closeModal();
            $this->dispatch('kelompok-saved');
        } catch (\Exception $e) {
            $this->alert('error', 'Error', $e->getMessage());
        }
    }

    // 🔥 Hapus kelompok
    public function deleteKelompok($id)
    {
        try {
            $kelompok = Kelompok::findOrFail($id);

            if ($kelompok->anggota()->count() > 0) {
                $this->alert('error', 'Gagal', 'Kelompok masih memiliki anggota');
                return;
            }

            $kelompok->delete();

            $this->alert('success', 'Berhasil', 'Kelompok berhasil dihapus');
        } catch (\Exception $e) {
            $this->alert('error', 'Error', 'Terjadi kesalahan saat menghapus');
        }
    }

    // 🔥 Reset form
    public function resetForm()
    {
        $this->kelompok_id = null;
        $this->nama_kelompok = '';
        $this->periode_id = '';
        $this->kegiatan_id = $this->kegiatanID;
        $this->lokasi = '';
        $this->isEditing = false;
    }

    // 🔥 Close modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // 🔥 Reset filter
    public function resetFilter()
    {
        $this->search = '';
        $this->selectedPeriode = $this->periodeFilter;
        $this->selectedKegiatan = $this->kegiatanID;
    }
}