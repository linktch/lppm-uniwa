<?php

namespace App\Livewire\Superadmin\Periode;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\WithPagination;
use App\Models\Periode;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Livewire akan bungkus ini
class Index extends Component
{
    public $nama_periode, $tanggal_mulai, $tanggal_selesai, $periode_id;
    public $updateMode = false;

    public $isModalOpen = false;

    public function openModal()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    protected $rules = [
        'nama_periode' => 'required|string|max:50',
        'tanggal_mulai' => 'nullable|date',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
    ];

    public function render()
    {
        $periodes = Periode::orderBy('created_at', 'desc')->paginate(10); // 10 per halaman
        return view('livewire.superadmin.periode.index', compact('periodes'));
    }

    public function resetInput()
    {
        $this->nama_periode = '';
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->periode_id = null;
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate();

        Periode::create([
            'nama_periode' => $this->nama_periode,
            // Kalau kosong, simpan null
            'tanggal_mulai' => $this->tanggal_mulai ?: null,
            'tanggal_selesai' => $this->tanggal_selesai ?: null,
        ]);

        session()->flash('message', 'Periode berhasil dibuat.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $periode = Periode::findOrFail($id);
        $this->periode_id = $id;
        $this->nama_periode = $periode->nama_periode;
        $this->tanggal_mulai = $periode->tanggal_mulai;
        $this->tanggal_selesai = $periode->tanggal_selesai;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->periode_id) {
            $periode = Periode::find($this->periode_id);
            $periode->update([
                'nama_periode' => $this->nama_periode,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
            ]);
            session()->flash('message', 'Periode berhasil diperbarui.');
            $this->resetInput();
        }
    }

    public function delete($id)
    {
        if ($id) {
            $periode = Periode::find($id);
            $periode->delete();
            session()->flash('message', 'Periode berhasil dihapus.');
        }
    }
}
