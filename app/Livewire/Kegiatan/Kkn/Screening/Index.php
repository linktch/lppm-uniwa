<?php

namespace App\Livewire\Kegiatan\Kkn\Screening;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\Periode;
use App\Models\ScreeningQuestion;
use App\Services\KKNService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Filter properties
    public $search = '';
    
    // Modal properties
    public $showModal = false;
    public $isEditing = false;
    public $screeningId = null;
    
    // Form properties
    public $pertanyaan = '';
    public $deskripsi = '';
    public $tipe_jawaban = '';
    public $pilihan_jawaban = '';
    public $is_required = false;
    public $is_active = true;
    
    // Default values
    public $kegiatanID;
    public $periodeFilter;
    
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    
    protected $rules = [
        'pertanyaan' => 'required|string|max:500',
        
    ];
    
    protected $messages = [
        'pertanyaan.required' => 'Pertanyaan wajib diisi',
        
    ];
    
    public function mount()
    {
        // Get KKN kegiatan ID from service
        $this->kegiatanID = KKNService::kegiatanId('KKN');
        $this->periodeFilter = KKNService::periodeId();
    }
    
    /**
     * Reset form values
     */
    public function resetForm()
    {
        $this->reset([
            'pertanyaan',
           
        ]);
        $this->is_active = true;
        $this->is_required = false;
        $this->resetValidation();
    }
    
    /**
     * Open modal to add new screening question
     */
    public function openScreeningModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    /**
     * Open modal to edit screening question
     */
    public function editScreening($id)
    {
        $screening = ScreeningQuestion::findOrFail($id);
        
        $this->screeningId = $screening->id;
        $this->pertanyaan = $screening->pertanyaan;
        $this->deskripsi = $screening->deskripsi;
        $this->tipe_jawaban = $screening->tipe_jawaban;
        $this->pilihan_jawaban = $screening->pilihan_jawaban;
        $this->is_required = (bool) $screening->is_required;
        $this->is_active = (bool) $screening->is_active;
        $this->isEditing = true;
        
        $this->showModal = true;
    }
    
    /**
     * Close modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    /**
     * Save screening question (create or update)
     */
    public function save()
    {
        $this->validate();
        
        // Additional validation for pilihan_jawaban when tipe_jawaban requires options
        if (in_array($this->tipe_jawaban, ['select', 'radio', 'checkbox'])) {
            if (empty($this->pilihan_jawaban)) {
                $this->addError('pilihan_jawaban', 'Pilihan jawaban wajib diisi untuk tipe jawaban ini');
                return;
            }
        }
        
        try {
            $data = [
                'pertanyaan' => $this->pertanyaan,
                
            ];
            
            if ($this->isEditing) {
                // Update existing question
                $screening = ScreeningQuestion::findOrFail($this->screeningId);
                $screening->update($data);
                
                $this->dispatch('swal', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Pertanyaan screening berhasil diperbarui',
                ]);
            } else {
                // Create new question
                ScreeningQuestion::create($data);
                
                $this->dispatch('swal', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Pertanyaan screening berhasil ditambahkan',
                ]);
            }
            
            $this->closeModal();
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Toggle question status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $screening = ScreeningQuestion::findOrFail($id);
            $screening->is_active = !$screening->is_active;
            $screening->save();
            
            $status = $screening->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => "Pertanyaan berhasil {$status}",
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengubah status: ' . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Delete screening question
     */
    public function deleteScreening($id)
    {
        try {
            $screening = ScreeningQuestion::findOrFail($id);
            $screening->delete();
            
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pertanyaan screening berhasil dihapus',
            ]);
            
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menghapus pertanyaan: ' . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Get screening questions query with filters
     */
    protected function getScreeningQuery()
    {
        $query = ScreeningQuestion::orderBy('created_at', 'desc');
        
        // Search by pertanyaan
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('pertanyaan', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query;
    }
    
    public function render()
    {
        $screenings = $this->getScreeningQuery()->paginate(10);
        
        return view('livewire.kegiatan.kkn.screening.index', [
            'screenings' => $screenings,
        ]);
    }
}