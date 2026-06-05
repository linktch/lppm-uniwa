<?php

namespace App\Livewire\Kelompok;

use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Add extends Component
{
    use WithPagination;

    // protected $paginationTheme = 'bootstrap';

    // 🔥 Data utama
    public $kelompok;
    public $kelompokID;
    public $jenisKegiatan; // TAMBAHKAN PROPERTY JENIS KEGIATAN
    public $role; // TAMBAHKAN PROPERTY ROLE
    
    // 🔥 UI state
    public $selectedMahasiswa = [];
    public $selectAll = false;
    public $searchMahasiswa = '';
    public $filterAngkatan = '';

    /*
     * |--------------------------------------------------------------------------
     * | MOUNT
     * |--------------------------------------------------------------------------
     */
    public function mount($role, $jenisKegiatan, $kelompokID) // TAMBAHKAN PARAMETER role DAN jenisKegiatan
    {
        $this->role = $role; // SIMPAN ROLE
        $this->jenisKegiatan = $jenisKegiatan; // SIMPAN JENIS KEGIATAN
        $this->kelompokID = $kelompokID;

        $this->kelompok = Kelompok::findOrFail($kelompokID);
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
            $this->selectedMahasiswa = User::where('role', 'mahasiswa')
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedMahasiswa = [];
        }
    }

    /*
     * |--------------------------------------------------------------------------
     * | SAVE DATA
     * |--------------------------------------------------------------------------
     */
    public function saveMahasiswa()
    {
        if (empty($this->selectedMahasiswa)) {
            $this->dispatch('swal', icon: 'warning', title: 'Pilih dulu', text: 'Belum ada mahasiswa dipilih');
            return;
        }

        foreach ($this->selectedMahasiswa as $userId) {
            KelompokUser::firstOrCreate([
                'kelompok_id' => $this->kelompokID,
                'user_id' => $userId,
                'role' => 'mahasiswa',
            ]);
        }

        $this->dispatch('swal', icon: 'success', title: 'Berhasil', text: 'Mahasiswa berhasil ditambahkan');

        // PERBAIKI redirect dengan parameter lengkap
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
        // PERBAIKI redirect dengan parameter lengkap
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
        // 🔥 1. QUERY
        $query = User::where('role', 'mahasiswa')
            ->whereDoesntHave('kelompokUser', function ($q) {
                $q->whereHas('kelompok', function ($qq) {
                    $qq
                        ->where('periode_id', $this->kelompok->periode_id)
                        ->where('kegiatan_id', $this->kelompok->kegiatan_id);
                });
            });

        // 🔥 2. AMBIL DATA JADI COLLECTION
        $collection = $query->get();

        // 🔥 3. FILTER ANGKATAN
        if ($this->filterAngkatan) {
            $collection = $collection->filter(function ($mhs) {
                $data = is_array($mhs->data_mahasiswa)
                    ? $mhs->data_mahasiswa
                    : json_decode($mhs->data_mahasiswa ?? '{}', true);

                return str_contains(
                    $data['nama_periode_masuk'] ?? '',
                    $this->filterAngkatan
                );
            });
        }

        // 🔍 4. SEARCH
        if ($this->searchMahasiswa) {
            $collection = $collection->filter(function ($mhs) {
                $data = is_array($mhs->data_mahasiswa)
                    ? $mhs->data_mahasiswa
                    : json_decode($mhs->data_mahasiswa ?? '{}', true);

                return str_contains(strtoupper($data['nama_mahasiswa'] ?? ''), strtoupper($this->searchMahasiswa)) ||
                    str_contains($data['nim'] ?? '', $this->searchMahasiswa);
            });
        }

        // 🔥 5. PAGINATION
        $page = $this->getPage();
        $perPage = 10;

        $mahasiswas = new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('livewire.kelompok.add', [
            'mahasiswas' => $mahasiswas,
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
        ]);
    }
}