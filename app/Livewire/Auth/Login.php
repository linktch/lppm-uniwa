<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public $username;
    public $password;
    public $remember = false;
    public $captcha;

    public function login()
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ];

        $messages = [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ];

        // 🔐 CAPTCHA only if enabled
        if (env('ENABLE_CAPTCHA', true)) {
            $rules['captcha'] = 'required|string';
            $messages['captcha.required'] = 'Captcha wajib diisi';
        }

        $this->validate($rules, $messages);

        // 🔐 CAPTCHA CHECK (only if enabled)
        if (env('ENABLE_CAPTCHA', true)) {
            if ($this->captcha !== session('captcha_phrase')) {
                $this->addError('captcha', 'Captcha tidak sesuai');
                $this->reset('captcha');
                $this->dispatch('refreshCaptcha');
                return;
            }
        }

        // 🔥 coba username dulu
        if (Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ], $this->remember)) {
            session()->regenerate();

            // ✅ CEK NIM MAHASISWA
            if (!$this->checkMahasiswaNim()) {
                return;
            }

            return redirect()->route('dashboard');
        }
        // 🔥 kalau gagal, coba email
        elseif (Auth::attempt([
            'email' => $this->username,
            'password' => $this->password
        ], $this->remember)) {
            session()->regenerate();

            // ✅ CEK NIM MAHASISWA
            if (!$this->checkMahasiswaNim()) {
                return;
            }

            return redirect()->route('dashboard');
        }

        // ❌ kalau dua-duanya gagal
        $this->addError('username', 'Username atau password salah');
        $this->reset('password');
        $this->dispatch('refreshCaptcha');
    }

    /**
     * Private function untuk cek NIM mahasiswa
     *
     * @return bool
     */
    private function checkMahasiswaNim()
    {
        $user = Auth::user();

        // Hanya cek jika role mahasiswa
        if ($user && $user->role == 'mahasiswa') {
            $dataMahasiswa = is_string($user->data_mahasiswa)
                ? json_decode($user->data_mahasiswa, true)
                : $user->data_mahasiswa;

            $nim = $dataMahasiswa['nim'] ?? null;

            // Daftar NIM yang diizinkan
            $allowedNims = ['20180310111', '20182310014', '20214210032', '20231120304', '20233310053', '20236610458','20232210151', '20181110462'];

            // Jika NIM tidak ada dalam daftar yang diizinkan
            if (!in_array($nim, $allowedNims)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                $this->addError('username', 'Akses ditolak. NIM tidak terdaftar untuk kegiatan ini.');
                $this->reset('password');
                $this->dispatch('refreshCaptcha');

                return false;
            }
        }

        return true;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
