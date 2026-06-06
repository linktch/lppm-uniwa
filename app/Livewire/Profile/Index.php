<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    public $user;
    public $role;
    
    // Data untuk ditampilkan
    public $name;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $foto;
    public $existing_foto;
    
    // Data mahasiswa (readonly)
    public $nim;
    public $nama_mahasiswa;
    public $nama_program_studi;
    public $semester;
    public $jenis_kelamin;
    public $no_hp;
    
    // Password change
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $showPasswordForm = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->role = $this->user->role;
        
        // Load user data
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->phone = $this->user->phone;
        $this->existing_foto = $this->user->foto;
        
        // Load mahasiswa data if role is mahasiswa
        if ($this->role == 'mahasiswa') {
            $dataMahasiswa = is_string($this->user->data_mahasiswa)
                ? json_decode($this->user->data_mahasiswa, true)
                : ($this->user->data_mahasiswa ?? []);
            
            $this->nim = $dataMahasiswa['nim'] ?? '';
            $this->nama_mahasiswa = $dataMahasiswa['nama_mahasiswa'] ?? '';
            $this->nama_program_studi = $dataMahasiswa['nama_program_studi'] ?? '';
            $this->semester = $dataMahasiswa['semester'] ?? '';
            $this->jenis_kelamin = $dataMahasiswa['jenis_kelamin'] ?? '';
            $this->no_hp = $dataMahasiswa['no_hp'] ?? '';
        }
    }

    // UPDATE PROFIL - HANYA UNTUK ADMIN (mahasiswa tidak bisa)
    public function updateProfile()
    {
        // Mahasiswa TIDAK BISA update data profil
        if ($this->role == 'mahasiswa') {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Akses Ditolak!',
                'text' => 'Mahasiswa tidak diizinkan mengubah data profil.',
            ]);
            return;
        }
        
        $this->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
            ]);
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Profil berhasil diperbarui',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    // UPDATE FOTO - SEMUA ROLE BISA (termasuk mahasiswa)
    public function updateFoto()
    {
        $this->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Hapus foto lama jika ada
            if ($this->existing_foto && Storage::disk('public')->exists($this->existing_foto)) {
                Storage::disk('public')->delete($this->existing_foto);
            }
            
            $fotoPath = $this->foto->store('profile', 'public');
            $this->user->update(['foto' => $fotoPath]);
            $this->existing_foto = $fotoPath;
            $this->foto = null;
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Foto profil berhasil diperbarui',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    // GANTI PASSWORD - SEMUA ROLE BISA (termasuk mahasiswa)
    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|same:new_password_confirmation',
        ]);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Password saat ini salah');
            return;
        }

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation', 'showPasswordForm']);
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Password berhasil diubah',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);
    }

    public function togglePasswordForm()
    {
        $this->showPasswordForm = !$this->showPasswordForm;
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function back()
    {
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}