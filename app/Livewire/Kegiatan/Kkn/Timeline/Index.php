<?php

namespace App\Livewire\Kegiatan\Kkn\Timeline;

use App\Models\TimelineKegiatan;
use App\Models\Periode;
use App\Services\KKNService;
use App\Models\Kegiatan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    
    public $role;
    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $kegiatanID;
    public $periodeFilter;
    
    // Modal properties
    public $showModal = false;
    public $isEdit = false;
    public $timelineId;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $status = 'AKTIF';
    public $jenis = '';
    
    // Additional properties
    public $periodeList = [];
    public $kegiatanList = [];
    public $selectedPeriodeId;
    public $selectedKegiatanId;
    
    protected $rules = [
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'status' => 'required|in:AKTIF,berakhir',
        'jenis' => 'required|in:Pendaftaran,Pembekalan,Pelaksanaan,Pelaporan,Evaluasi',
        'selectedPeriodeId' => 'required|exists:periode,id',
        'selectedKegiatanId' => 'required|exists:kegiatan,id',
    ];
    
    protected $messages = [
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
        'jenis.required' => 'Jenis kegiatan wajib dipilih',
        'jenis.in' => 'Jenis kegiatan tidak valid',
        'selectedPeriodeId.required' => 'Periode wajib dipilih',
        'selectedKegiatanId.required' => 'Kegiatan wajib dipilih',
    ];
    
    public function mount($role)
    {
        $this->kegiatanID = KKNService::kegiatanId('KKN');
        $this->periodeFilter = KKNService::periodeId();
        $this->role = $role;
        
        $this->loadLists();
    }
    
    private function loadLists()
    {
        $this->periodeList = Periode::orderBy('id', 'desc')->get();
        $this->kegiatanList = Kegiatan::where('id', $this->kegiatanID)->get();
        
        $this->selectedPeriodeId = $this->periodeFilter;
        $this->selectedKegiatanId = $this->kegiatanID;
    }
    
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->timelineId = null;
        $this->selectedPeriodeId = $this->periodeFilter;
        $this->selectedKegiatanId = $this->kegiatanID;
        $this->showModal = true;
    }
    
    public function openEditModal($id)
    {
        $timeline = TimelineKegiatan::findOrFail($id);
        
        $this->timelineId = $timeline->id;
        $this->tanggal_mulai = $timeline->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $timeline->tanggal_selesai->format('Y-m-d');
        $this->status = $timeline->status;
        $this->jenis = $timeline->jenis;
        $this->selectedPeriodeId = $timeline->periode_id;
        $this->selectedKegiatanId = $timeline->kegiatan_id;
        
        $this->isEdit = true;
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }
    
    public function resetForm()
    {
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->status = 'AKTIF';
        $this->jenis = '';
        $this->timelineId = null;
    }
    
    public function save()
    {
        $this->validate();
        
        try {
            $exists = TimelineKegiatan::where('periode_id', $this->selectedPeriodeId)
                ->where('kegiatan_id', $this->selectedKegiatanId)
                ->where('jenis', $this->jenis)
                ->when($this->timelineId, function($query) {
                    $query->where('id', '!=', $this->timelineId);
                })
                ->exists();
                
            if ($exists) {
                session()->flash('error', 'Timeline untuk periode dan kegiatan ini sudah ada');
                return;
            }
            
            $data = [
                'periode_id' => $this->selectedPeriodeId,
                'kegiatan_id' => $this->selectedKegiatanId,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'status' => $this->status,
                'jenis' => $this->jenis,
            ];
            
            if ($this->timelineId) {
                $timeline = TimelineKegiatan::findOrFail($this->timelineId);
                $timeline->update($data);
                $message = 'Timeline kegiatan berhasil diperbarui';
            } else {
                TimelineKegiatan::create($data);
                $message = 'Timeline kegiatan berhasil ditambahkan';
            }
            
            session()->flash('success', $message);
            $this->closeModal();
            $this->resetPage();
            
        } catch (\Exception $e) {
            Log::error('Error saving timeline: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        try {
            $timeline = TimelineKegiatan::findOrFail($id);
            $timeline->delete();
            
            session()->flash('success', 'Timeline kegiatan berhasil dihapus');
            $this->resetPage();
            
        } catch (\Exception $e) {
            Log::error('Error deleting timeline: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus timeline: ' . $e->getMessage());
        }
    }
    
    public function updateStatus($id, $status)
    {
        try {
            $timeline = TimelineKegiatan::findOrFail($id);
            $timeline->update(['status' => $status]);
            
            $statusText = $status == 'AKTIF' ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('success', "Status timeline berhasil {$statusText}");
            
        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $timelines = TimelineKegiatan::with(['periode', 'kegiatan'])
            ->when($this->search, function($query) {
                $query->whereHas('kegiatan', function($q) {
                    $q->where('nama_kegiatan', 'like', '%' . $this->search . '%');
                })->orWhereHas('periode', function($q) {
                    $q->where('nama_periode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->paginate($this->perPage);
        
        return view('livewire.kegiatan.kkn.timeline.index', [
            'timelines' => $timelines,
            'periodeList' => $this->periodeList,
            'kegiatanList' => $this->kegiatanList,
        ]);
    }
}