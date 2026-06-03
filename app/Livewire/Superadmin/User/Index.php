<?php

namespace App\Livewire\Superadmin\User;

use App\Models\ProdiFakultas;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]  // Livewire akan bungkus ini
class Index extends Component
{
    use WithPagination;

    // protected $paginationTheme = 'bootstrap';

    public $search = '';

    // Form fields
    public $showModal = false;

    public $name,
        $email,
        $role,
        $password;

    public $userId = null;

    public $prodi_id;
    public $editingId = null;  // <- HARUS ADA

    // Reset pagination saat search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset search method

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    // Buka modal tambah atau edit
    public function openModal($id = null)
    {
        $this->resetForm();
        $this->userId = $id;

        if ($id) {
            $user = User::findOrFail($id);
            $this->name = $user->first_name;
            $this->email = $user->email;
            $this->role = $user->role;
        }

        $this->showModal = true;
    }

    // Tutup modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // Reset form
    public function resetForm()
    {
        $this->reset(['name', 'email', 'role', 'password', 'userId']);
    }

    // Simpan user (create atau update)
    public function storeUser()
    {
        // Validasi
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'unique:users,email' . ($this->userId ? ',' . $this->userId : ''),
                'regex:/^(\d+|[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/'
            ],
            'role' => 'required|string',
            'prodi_id' => 'nullable|string',  // biarkan null jika kosong
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
        ];

        $validated = $this->validate($rules);

        if ($this->userId) {
            // ===== UPDATE USER =====
            $user = User::findOrFail($this->userId);
            $user->first_name = $validated['name'];
            $user->email = $validated['email'];
            $user->username = $validated['email'];
            $user->role = $validated['role'];
            $user->id_prodi = $validated['prodi_id'] !== '' ? $validated['prodi_id'] : null;

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Kirim event SweetAlert
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'User berhasil diperbarui'
            ]);
        } else {
            // ===== CREATE USER BARU =====
            User::create([
                'first_name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['email'],
                'role' => $validated['role'],
                'id_prodi' => $validated['prodi_id'] !== '' ? $validated['prodi_id'] : null,
                'password' => Hash::make($validated['password']),
            ]);

            // Kirim event SweetAlert
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'User berhasil ditambahkan'
            ]);
        }

        $this->closeModal();
    }

    // Hapus user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'User berhasil dihapus']);
    }

    public function syncUser()
    {
        // Reset progress cache
        Cache::put('sync_user_progress', 0);
        Cache::put('sync_user_current', 0);
        Cache::put('sync_user_total', 0);
        Cache::put('sync_user_status', 'processing');

        // Dispatch job
        \App\Jobs\SyncUserApiJob::dispatch();

        // Kirim event ke browser
        // $this->dispatchBrowserEvent('sync-start');
    }

    // Method untuk mengecek progress
    public function getProgress()
    {
        $this->progress = Cache::get('sync_user_progress', 0);
        $this->current = Cache::get('sync_user_current', 0);
        $this->total = Cache::get('sync_user_total', 0);

        $status = Cache::get('sync_user_status');

        if ($status === 'done') {
            // $this->dispatchBrowserEvent('sync-finished');
            Cache::forget('sync_user_progress');
            Cache::forget('sync_user_current');
            Cache::forget('sync_user_total');
            Cache::forget('sync_user_status');
        }

        if ($status === 'failed') {
            // $this->dispatchBrowserEvent('sync-failed');
        }
    }

    public function render()
    {
        $roles = Roles::all();
        $prodis = ProdiFakultas::all();
        $users = User::query()
            ->where(function ($query) {
                $query
                    ->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderByRaw("FIELD(role, 'superadmin', 'admin', 'perangkat', 'dosen', 'koordinator', 'mahasiswa')")
            ->latest()
            ->paginate(10);

        return view('livewire.superadmin.user.index', compact('users', 'roles', 'prodis'));
    }
}
