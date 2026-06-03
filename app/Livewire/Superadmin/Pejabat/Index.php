<?php

namespace App\Livewire\Superadmin\Pejabat;

use App\Models\PejabatSignatur;
use App\Models\ProdiFakultas;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Tambahkan ini
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Livewire akan bungkus ini
class Index extends Component
{
    use WithPagination, WithFileUploads; // Tambahkan WithFileUploads

    // protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    // Properties untuk form
    public $pejabatId;
    public $user_id;
    public $prodi_fakultas_id;
    public $nama;
    public $jabatan;
    public $signatur_path;
    public $signatur_file; // Untuk upload file baru
    public $old_signatur_path; // Untuk menyimpan path lama

    public $isModalOpen = false;

    protected $rules = [
        'user_id' => 'nullable|exists:users,id',
        'prodi_fakultas_id' => 'required|exists:prodi_fakultas,id',
        'jabatan' => 'required|string|max:255',
        'signatur_file' => 'nullable|image|mimes:png|max:1024', // PNG only, max 1MB
    ];

    public function render()
    {
        $pejabats = PejabatSignatur::with(['prodiFakultas', 'user'])
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('jabatan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        $prodiFakultas = ProdiFakultas::orderBy('nama_program_studi')->get();
        
        // Ambil user dimana role bukan mahasiswa
        $users = User::where('role', '!=', 'mahasiswa')
            ->orderBy('first_name')
            ->get();

        return view('livewire.superadmin.pejabat.index', [
            'pejabats' => $pejabats,
            'prodiFakultas' => $prodiFakultas,
            'users' => $users,
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->pejabatId = null;
        $this->user_id = '';
        $this->prodi_fakultas_id = '';
        $this->nama = '';
        $this->jabatan = '';
        $this->signatur_path = '';
        $this->signatur_file = null;
        $this->old_signatur_path = '';
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $signaturPath = null;
        
        // Upload file jika ada
        if ($this->signatur_file) {
            $signaturPath = $this->signatur_file->store('signatures', 'public');
        }
        $nama = User::where('id', $this->user_id)->value('first_name');
        PejabatSignatur::create([
            'user_id' => $this->user_id,
            'prodi_fakultas_id' => $this->prodi_fakultas_id,
            'nama' => $nama,
            'jabatan' => $this->jabatan,
            'signatur_path' => $signaturPath,
        ]);

        session()->flash('message', 'Pejabat Signatur berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $pejabat = PejabatSignatur::findOrFail($id);
        $this->pejabatId = $pejabat->id;
        $this->user_id = $pejabat->user_id;
        $this->prodi_fakultas_id = $pejabat->prodi_fakultas_id;
        $this->nama = $pejabat->nama;
        $this->jabatan = $pejabat->jabatan;
        $this->signatur_path = $pejabat->signatur_path;
        $this->old_signatur_path = $pejabat->signatur_path;
        $this->signatur_file = null;

        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate();

        $pejabat = PejabatSignatur::findOrFail($this->pejabatId);
        
        $signaturPath = $this->old_signatur_path;
        
        // Upload file baru jika ada
        if ($this->signatur_file) {
            // Hapus file lama jika ada
            if ($signaturPath && \Storage::disk('public')->exists($signaturPath)) {
                \Storage::disk('public')->delete($signaturPath);
            }
            $signaturPath = $this->signatur_file->store('signatures', 'public');
        }

        $pejabat->update([
            'user_id' => $this->user_id,
            'prodi_fakultas_id' => $this->prodi_fakultas_id,
            'nama' => $this->nama,
            'jabatan' => $this->jabatan,
            'signatur_path' => $signaturPath,
        ]);

        session()->flash('message', 'Pejabat Signatur berhasil diupdate.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $pejabat = PejabatSignatur::findOrFail($id);
        
        // Hapus file signature jika ada
        if ($pejabat->signatur_path && \Storage::disk('public')->exists($pejabat->signatur_path)) {
            \Storage::disk('public')->delete($pejabat->signatur_path);
        }
        
        $pejabat->delete();

        session()->flash('message', 'Pejabat Signatur berhasil dihapus.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}