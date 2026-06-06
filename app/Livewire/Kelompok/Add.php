<?php

namespace App\Livewire\Kelompok;

use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\User;
use App\Services\KegiatanService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Add extends Component
{
    use WithPagination;

    // Data utama
    public $kelompok;
    public $kelompokID;
    public $jenisKegiatan;
    public $role;
    public $periode_id;
    public $kegiatan_id;
    
    // UI state
    public $selectedMahasiswa = [];
    public $selectAll = false;
    public $searchMahasiswa = '';
    public $filterAngkatan = '';

    /*
     * |--------------------------------------------------------------------------
     * | MOUNT
     * |--------------------------------------------------------------------------
     */
    public function mount($role, $jenisKegiatan, $kelompokID)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
        $this->kelompokID = $kelompokID;

        $this->kelompok = Kelompok::findOrFail($kelompokID);
        $this->periode_id = $this->kelompok->periode_id;
        $this->kegiatan_id = $this->kelompok->kegiatan_id;
    }

    /*
     * |--------------------------------------------------------------------------
     * | RESET PAGINATION
     * |--------------------------------------------------------------------------
     */
    public function updatedSearchMahasiswa()
    {
        $this->resetPage();
    }

    public function updatedFilterAngkatan()
    {
        $this->resetPage();
    }

    /*
     * |--------------------------------------------------------------------------
     * | SELECT ALL
     * |--------------------------------------------------------------------------
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Ambil semua mahasiswa yang memenuhi kriteria
            $allMahasiswa = $this->getFilteredMahasiswaQuery()->get();
            $this->selectedMahasiswa = $allMahasiswa->pluck('id')->toArray();
        } else {
            $this->selectedMahasiswa = [];
        }
    }

    /*
     * |--------------------------------------------------------------------------
     * | GET FILTERED MAHASISWA QUERY
     * |--------------------------------------------------------------------------
     */
    private function getFilteredMahasiswaQuery()
    {
        $query = User::where('role', 'mahasiswa')
            ->whereDoesntHave('kelompokUser', function ($q) {
                $q->whereHas('kelompok', function ($qq) {
                    $qq->where('periode_id', $this->periode_id)
                        ->where('kegiatan_id', $this->kegiatan_id);
                });
            });

        // Filter angkatan
        if ($this->filterAngkatan) {
            $query->where(function ($q) {
                $q->where('data_mahasiswa', 'like', '%' . $this->filterAngkatan . '%');
            });
        }

        // Search
        if ($this->searchMahasiswa) {
            $query->where(function ($q) {
                $q->where('data_mahasiswa', 'like', '%' . $this->searchMahasiswa . '%')
                    ->orWhere('data_mahasiswa', 'like', '%' . $this->searchMahasiswa . '%');
            });
        }

        return $query;
    }

    /*
     * |--------------------------------------------------------------------------
     * | SAVE DATA
     * |--------------------------------------------------------------------------
     */
    public function saveMahasiswa()
    {
        if (empty($this->selectedMahasiswa)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Pilih dulu',
                'text' => 'Belum ada mahasiswa dipilih'
            ]);
            return;
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($this->selectedMahasiswa as $userId) {
            try {
                KelompokUser::firstOrCreate([
                    'kelompok_id' => $this->kelompokID,
                    'user_id' => $userId,
                    'role' => 'mahasiswa',
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
            }
        }

        $message = "Berhasil menambahkan {$successCount} mahasiswa";
        if ($failedCount > 0) {
            $message .= ", {$failedCount} gagal";
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => $message
        ]);

        // Redirect ke detail kelompok
        return redirect()->route('kegiatan.kelompok.detail', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'kelompokID' => $this->kelompokID
        ]);
    }

    /*
     * |--------------------------------------------------------------------------
     * | BACK BUTTON
     * |--------------------------------------------------------------------------
     */
    public function backToDetail()
    {
        return redirect()->route('kegiatan.kelompok.detail', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'kelompokID' => $this->kelompokID
        ]);
    }

    /*
     * |--------------------------------------------------------------------------
     * | RENDER
     * |--------------------------------------------------------------------------
     */
    public function render()
    {
        // Query mahasiswa
        $query = User::where('role', 'mahasiswa')
            ->whereDoesntHave('kelompokUser', function ($q) {
                $q->whereHas('kelompok', function ($qq) {
                    $qq->where('periode_id', $this->periode_id)
                        ->where('kegiatan_id', $this->kegiatan_id);
                });
            });

        // Filter angkatan
        if ($this->filterAngkatan) {
            $query->where('data_mahasiswa', 'like', '%' . $this->filterAngkatan . '%');
        }

        // Search
        if ($this->searchMahasiswa) {
            $query->where(function ($q) {
                $q->where('data_mahasiswa', 'like', '%' . $this->searchMahasiswa . '%')
                    ->orWhere('data_mahasiswa', 'like', '%' . $this->searchMahasiswa . '%');
            });
        }

        $mahasiswas = $query->paginate(10);

        return view('livewire.kelompok.add', [
            'mahasiswas' => $mahasiswas,
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'kelompok' => $this->kelompok,
        ]);
    }
}