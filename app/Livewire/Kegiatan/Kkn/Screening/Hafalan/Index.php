<?php

namespace App\Livewire\Kegiatan\Kkn\Screening\Hafalan;

use App\Models\IndikatorHafalan;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    // Properti untuk indikator
    public $indikators;
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;
    // Form fields
    public $nama_indikator = '';
    public $laki_laki = true;
    public $perempuan = true;
    // Search
    public $search = '';

    public function mount()
    {
        $this->loadIndikators();
    }

    public function loadIndikators()
    {
        $query = IndikatorHafalan::query();

        if ($this->search) {
            $query->where('nama_indikator', 'like', '%' . $this->search . '%');
        }

        $this->indikators = $query->orderBy('urutan', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.kegiatan.kkn.screening.hafalan.index', [
            'indikators' => $this->indikators
        ]);
    }

    public function openModalTambah()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function routePenilaian()
    {
        $role = auth()->user()->role;

       return redirect()->route('kegiatan.kkn.screening.hafalan.penilaian', ['role' => $role]);
    }

    public function editIndikator($id)
    {
        $indikator = IndikatorHafalan::findOrFail($id);

        $this->editingId = $id;
        $this->nama_indikator = $indikator->nama_indikator;
        $this->laki_laki = (bool) $indikator->laki_laki;
        $this->perempuan = (bool) $indikator->perempuan;
        $this->isEditing = true;
        $this->showModal = true;

        $this->dispatch('openModal');
    }

    public function save()
    {
        $this->validate([
            'nama_indikator' => 'required|min:3|max:255',
            'laki_laki' => 'required|boolean',
            'perempuan' => 'required|boolean',
        ], [
            'nama_indikator.required' => 'Nama indikator wajib diisi',
            'nama_indikator.min' => 'Nama indikator minimal 3 karakter',
        ]);

        try {
            if ($this->isEditing) {
                // Update existing indikator
                $indikator = IndikatorHafalan::findOrFail($this->editingId);
                $indikator->update([
                    'nama_indikator' => $this->nama_indikator,
                    'laki_laki' => $this->laki_laki,
                    'perempuan' => $this->perempuan,
                ]);
                $message = 'Indikator berhasil diupdate';
            } else {
                // Get max urutan
                $maxUrutan = IndikatorHafalan::max('urutan');

                // Create new indikator
                IndikatorHafalan::create([
                    'nama_indikator' => $this->nama_indikator,
                    'laki_laki' => $this->laki_laki,
                    'perempuan' => $this->perempuan,
                    'urutan' => ($maxUrutan ?? 0) + 1,
                ]);
                $message = 'Indikator berhasil ditambahkan';
            }

            $this->loadIndikators();
            $this->closeModal();

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving indikator hafalan: ' . $e->getMessage());

            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteIndikator($id)
    {
        try {
            $indikator = IndikatorHafalan::findOrFail($id);
            $indikator->delete();

            // Reorder urutan
            $this->reorderUrutan();

            $this->loadIndikators();

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Indikator berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting indikator hafalan: ' . $e->getMessage());

            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function reorderUrutan()
    {
        $indikators = IndikatorHafalan::orderBy('urutan', 'asc')->get();

        foreach ($indikators as $index => $indikator) {
            $indikator->update(['urutan' => $index + 1]);
        }
    }

    public function moveUp($id)
    {
        $indikator = IndikatorHafalan::findOrFail($id);
        $prevIndikator = IndikatorHafalan::where('urutan', '<', $indikator->urutan)
            ->orderBy('urutan', 'desc')
            ->first();

        if ($prevIndikator) {
            $currentUrutan = $indikator->urutan;
            $prevUrutan = $prevIndikator->urutan;

            $indikator->update(['urutan' => $prevUrutan]);
            $prevIndikator->update(['urutan' => $currentUrutan]);
        }

        $this->loadIndikators();
    }

    public function moveDown($id)
    {
        $indikator = IndikatorHafalan::findOrFail($id);
        $nextIndikator = IndikatorHafalan::where('urutan', '>', $indikator->urutan)
            ->orderBy('urutan', 'asc')
            ->first();

        if ($nextIndikator) {
            $currentUrutan = $indikator->urutan;
            $nextUrutan = $nextIndikator->urutan;

            $indikator->update(['urutan' => $nextUrutan]);
            $nextIndikator->update(['urutan' => $currentUrutan]);
        }

        $this->loadIndikators();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal');
    }

    private function resetForm()
    {
        $this->nama_indikator = '';
        $this->laki_laki = true;
        $this->perempuan = true;
        $this->isEditing = false;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->loadIndikators();
    }
}
