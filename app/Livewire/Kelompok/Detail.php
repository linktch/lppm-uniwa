<?php

namespace App\Livewire\Kelompok;

use App\Models\Dosen;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\LokasiKkn;
use App\Models\Periode;
use App\Models\Prodi;
use App\Models\User;  // Mahasiswa
use App\Services\KKNService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Detail extends Component
{
    use WithPagination;

    public $kelompok;
    public $kelompokID;
    public $kegiatanID;
    public $periodeFilter;
    public $selectedKegiatan;
    public $selectedPeriode;
    public $jenisKegiatan; // TAMBAHKAN PROPERTY JENIS KEGIATAN
    public $role; // TAMBAHKAN PROPERTY ROLE
    
    // Data relationships
    public $dosenPembimbing;
    public $lokasiKkn;
    public $prodi;
    public $mahasiswas;
    
    // Modal properties
    public $showTimModal = false;
    public $isEditingTim = false;
    public $timId = null;
    public $selected_user_id = '';
    public $keterangan = '';
    public $availableUsers = [];

    public function mount($role, $jenisKegiatan, $kelompokID) // TAMBAHKAN PARAMETER role DAN jenisKegiatan
    {
        $this->role = $role; // SIMPAN ROLE
        $this->jenisKegiatan = $jenisKegiatan; // SIMPAN JENIS KEGIATAN
        $this->kelompokID = $kelompokID;

        // Tentukan kegiatan ID berdasarkan jenis kegiatan
        $this->kegiatanID = $this->getKegiatanId($jenisKegiatan);
        $this->periodeFilter = $this->getPeriodeId($jenisKegiatan);

        // Set default values
        $this->selectedKegiatan = $this->kegiatanID;
        $this->selectedPeriode = $this->periodeFilter;

        // Load kelompok with all relationships
        $this->kelompok = Kelompok::with([
            'periode',
            'kegiatan',
        ])->findOrFail($kelompokID);
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

    /**
     * Back to list page
     */
    public function backToList()
    {
        return redirect()->route('kegiatan.kelompok.index', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan
        ]);
    }

    /**
     * Edit kelompok
     */
    public function editKelompok()
    {
        return redirect()->route('kegiatan.kelompok.edit', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
            'kelompokID' => $this->kelompokID
        ]);
    }

    /**
     * Open modal to add new tim member
     */
    public function openTimModal()
    {
        $this->reset(['selected_user_id', 'role', 'keterangan', 'timId', 'isEditingTim']);
        
        // Get available users (non-mahasiswa and not already in this kelompok)
        $this->availableUsers = User::where('role', '!=', 'mahasiswa')
            ->whereDoesntHave('kelompokUser', function($query) {
                $query->where('kelompok_id', $this->kelompokID);
            })
            ->orderBy('name')
            ->get();
        
        $this->showTimModal = true;
    }

    /**
     * Open modal to edit tim member
     */
    public function editTim($id)
    {
        $timMember = KelompokUser::findOrFail($id);
        
        $this->timId = $timMember->id;
        $this->selected_user_id = $timMember->user_id;
        $this->role = $timMember->role;
        $this->keterangan = $timMember->keterangan ?? '';
        $this->isEditingTim = true;
        
        // Get available users including the current one
        $this->availableUsers = User::where('role', '!=', 'mahasiswa')
            ->where(function($query) {
                $query->whereDoesntHave('kelompokUser', function($q) {
                        $q->where('kelompok_id', $this->kelompokID);
                    })
                    ->orWhere('id', $this->selected_user_id);
            })
            ->orderBy('name')
            ->get();
        
        $this->showTimModal = true;
    }

    /**
     * Close modal
     */
    public function closeTimModal()
    {
        $this->showTimModal = false;
        $this->reset(['selected_user_id', 'role', 'keterangan', 'timId', 'isEditingTim']);
    }

    /**
     * Save tim member (create or update)
     */
    public function saveTim()
    {
        $this->validate([
            'selected_user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:dospem,tim,korlap',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            if ($this->isEditingTim) {
                // Update existing tim member
                $timMember = KelompokUser::findOrFail($this->timId);
                $timMember->update([
                    'role' => $this->role,
                    'keterangan' => $this->keterangan,
                ]);
                
                $this->dispatch('swal', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Tim berhasil diperbarui',
                ]);
            } else {
                // Check if user already in this kelompok
                $exists = KelompokUser::where('kelompok_id', $this->kelompokID)
                    ->where('user_id', $this->selected_user_id)
                    ->exists();
                
                if ($exists) {
                    throw new \Exception('User sudah tergabung dalam kelompok ini');
                }
                
                // Create new tim member
                KelompokUser::create([
                    'kelompok_id' => $this->kelompokID,
                    'user_id' => $this->selected_user_id,
                    'role' => $this->role,
                    'keterangan' => $this->keterangan,
                ]);
                
                $this->dispatch('swal', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Tim berhasil ditambahkan ke kelompok',
                ]);
            }
            
            $this->closeTimModal();
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove tim member from kelompok
     */
    public function removeTim($id)
    {
        try {
            $timMember = KelompokUser::findOrFail($id);
            $timMember->delete();
            
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Tim berhasil dihapus dari kelompok',
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menghapus tim: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * View tim member detail
     */
    public function viewTim($id)
    {
        $timMember = KelompokUser::with('user')->findOrFail($id);
        
        $this->dispatch('swal', [
            'type' => 'info',
            'title' => 'Detail Tim',
            'text' => "Nama: " . $timMember->user->name . "\nEmail: " . $timMember->user->email . "\nRole: " . ucfirst(str_replace('_', ' ', $timMember->role)),
        ]);
    }

    /**
     * View anggota (mahasiswa) detail
     */
    public function viewAnggota($id)
    {
        $anggota = KelompokUser::with('user')->findOrFail($id);
        $data = json_decode($anggota->user->data_mahasiswa ?? '{}', true);
        
        $nim = isset($data['nim']) ? $data['nim'] : '-';
        $nama = isset($data['nama_mahasiswa']) ? $data['nama_mahasiswa'] : '-';
        $prodi = isset($data['nama_program_studi']) ? $data['nama_program_studi'] : '-';
        
        $this->dispatch('swal', [
            'type' => 'info',
            'title' => 'Detail Mahasiswa',
            'text' => "NIM: " . $nim . "\nNama: " . $nama . "\nProdi: " . $prodi,
        ]);
    }

    /**
     * Remove anggota (mahasiswa) from kelompok
     */
    public function removeAnggota($id)
    {
        try {
            $anggota = KelompokUser::where('kelompok_id', $this->kelompokID)
                ->where('user_id', $id)
                ->first();
            
            if ($anggota) {
                $anggota->delete();
                
                $this->dispatch('swal', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Anggota berhasil dikeluarkan dari kelompok',
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengeluarkan anggota: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get prodi count from anggota
     */
    public function getProdiCountProperty()
    {
        $anggota = KelompokUser::with('user')
            ->where('kelompok_id', $this->kelompokID)
            ->where('role', 'mahasiswa')
            ->get();
        
        if (is_null($anggota) || $anggota->count() == 0) {
            return 0;
        }
        
        $prodiIds = [];
        foreach ($anggota as $member) {
            $data = json_decode($member->user->data_mahasiswa, true);
            if (is_array($data) && !empty($data['id_prodi'])) {
                $prodiIds[$data['id_prodi']] = true;
            }
        }
        return count($prodiIds);
    }

    /**
     * Get rata-rata semester from anggota
     */
    public function getRataSemesterProperty()
    {
        $anggota = KelompokUser::with('user')
            ->where('kelompok_id', $this->kelompokID)
            ->where('role', 'mahasiswa')
            ->get();
        
        if (is_null($anggota) || $anggota->count() == 0) {
            return 0;
        }
        
        $totalSemester = 0;
        $count = 0;
        
        foreach ($anggota as $member) {
            if (isset($member->semester) && $member->semester) {
                $totalSemester += $member->semester;
                $count++;
            }
        }
        
        return $count > 0 ? round($totalSemester / $count, 1) : 0;
    }

    public function render()
    {
        // Get anggota (mahasiswa)
        $anggota = KelompokUser::with('user')
            ->where('kelompok_id', $this->kelompokID)
            ->where('role', 'mahasiswa')
            ->get();
        
        // Get tim (non-mahasiswa)
        $tim = KelompokUser::with('user')
            ->where('kelompok_id', $this->kelompokID)
            ->where('role', '!=', 'mahasiswa')
            ->get();
        
        return view('livewire.kelompok.detail', [
            'kelompok' => $this->kelompok,
            'anggota' => $anggota,
            'tim' => $tim,
            'prodiCount' => $this->prodiCount,
            'rataSemester' => $this->rataSemester,
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
        ]);
    }
}