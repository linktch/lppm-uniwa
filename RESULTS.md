# 🔍 LAPORAN AUDIT KEAMANAN & KODE

**Path**: `/www/wwwroot/lppm.uniwa.ac.id`  
**Stack**: Laravel 12 + Livewire 3.6 + MySQL  
**Tanggal Audit**: 2026-05-18

---

## 🔴 KRITIKAL (Segera Perbaiki)

### 1. `.env` dengan `APP_DEBUG=true` di Production

```env
APP_ENV=local         # ❌ harus "production"
APP_DEBUG=true        # ❌ harus "false" — bocorkan stack trace & query ke user
APP_URL=https://lppm.uniwa.ac.id
```

Error, exception, dan query SQL akan ditampilkan ke pengguna. Wajib dimatikan.

### 2. File Permission 777 (rwx semua orang)

Banyak file kunci memiliki permission **0777**:

```
.env, artisan, composer.json, .htaccess, seluruh folder app/, routes/, config/
```

**Risiko**: Jika ada celah di shared hosting, attacker bisa membaca/menulis file sensitif.  
**Perbaiki**:
```bash
find /www/wwwroot/lppm.uniwa.ac.id -type f -exec chmod 644 {} \;
find /www/wwwroot/lppm.uniwa.ac.id -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```

### 3. Login Tanpa Rate Limiting (Brute-Force)

**File**: `app/Livewire/Auth/Login.php`

Tidak ada throttle. Attacker bisa brute force tanpa batas.

**Perbaiki** — tambahkan `RateLimiter`:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

public function login()
{
    $key = 'login:' . request()->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        $this->addError('username', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
        return;
    }

    // ... validasi & Auth::attempt() ...

    if (!Auth::attempt([...])) {
        RateLimiter::hit($key, 60); // 5 attempt per 60 detik
        $this->addError('username', 'Username atau password salah');
        return;
    }

    RateLimiter::clear($key);
}
```

### 4. Mass Assignment `role` & `permissions` di Model User

**File**: `app/Models/User.php`

```php
protected $fillable = [
    'email', 'password', 'role',       // ❌ 'role' mass-assignable!
    'permissions',                       // ❌ 'permissions' mass-assignable!
    ...
];
```

Di `Superadmin/User/Index.php`, ada `User::create([... 'role' => $validated['role']])`. Jika validasi longgar, user bisa menyuntikkan `role=superadmin`.

**Perbaiki**:
```php
protected $fillable = [
    'email', 'password', 'first_name', 'last_name', 'phone',
    'username', 'id_mahasiswa', 'id_registrasi_mahasiswa',
    'id_prodi', 'status_mahasiswa', 'id_periode',
    'data_mahasiswa', 'profile_pic',
];

protected $guarded = ['role', 'permissions'];  // tidak bisa mass-assign
```

### 5. `ScreeningQuestion` Model Tidak Sinkron

**File**: `app/Models/ScreeningQuestion.php`

```php
protected $fillable = ['pertanyaan'];  // ❌ hanya 1 field
```

Komponen `Screening/Index.php` mencoba set `is_active`, `deskripsi`, `tipe_jawaban`, `pilihan_jawaban`. **Semua field selain `pertanyaan` akan gagal tersimpan**.

**Perbaiki**:
```php
protected $fillable = [
    'pertanyaan',
    'deskripsi',
    'tipe_jawaban',
    'pilihan_jawaban',
    'is_required',
    'is_active',
];
```

### 6. `index.html` Default aaPanel Masih Ada

**File**: `/www/wwwroot/lppm.uniwa.ac.id/index.html`

Halaman default aaPanel masih ada di root. Bisa membingungkan routing dan membocorkan info server. **Hapus file ini**.

### 7. Kredensial Sudah Terekspos

Kredensial `.env` (DB password, API key) sudah terpapar dalam audit ini. **Wajib rotasi**:
- Database password
- `FEEDER_API_KEY`
- `APP_KEY` (generate ulang dengan `php artisan key:generate`)

---

## 🟠 HIGH (Segera Diperbaiki)

### 8. Query Ambil Semua Data ke Memory — `Add.php`

**File**: `app/Livewire/Kegiatan/Kkn/Kelompok/Add.php`

```php
$query = User::where('role', 'mahasiswa')...;
$collection = $query->get();  // ❌ ambil SEMUA mahasiswa ke memory
// lalu filter manual PHP...
$collection = $collection->filter(function ($mhs) { ... });
// lalu pagination manual...
```

**Dampak**: 10.000+ mahasiswa → memory habis, server crash.  
**Perbaiki**: Gunakan query builder sepenuhnya, jangan `get()` dulu.

### 9. Search JSON dengan LIKE Tanpa Index

**File**: `app/Livewire/Kegiatan/Kkn/Screening/Hafalan/Penilaian.php`

```php
$q->where('data_mahasiswa', 'like', '%' . $this->search . '%');
```

Ini **sangat lambat** untuk data besar karena tidak bisa pakai index.  
**Perbaiki**: Gunakan MySQL JSON function atau buat generated column + index.

```php
// Alternatif 1: cari field spesifik pakai ->
$q->where('data_mahasiswa->nama_mahasiswa', 'like', '%' . $this->search . '%');

// Alternatif 2: buat generated column di migration
$table->string('nama_mahasiswa_virtual')
      ->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(data_mahasiswa, '$.nama_mahasiswa'))")
      ->index();
```

### 10. `dispatchBrowserEvent` (Livewire v2) di Project v3

**File**: `app/Livewire/Superadmin/User/Index.php` dan lainnya

```php
$this->dispatchBrowserEvent('notify', [...]);   // ❌ v2 API
```

Di Livewire 3:
```php
$this->dispatch('notify', [...]);
```

### 11. Duplikasi Service: KKNService & PAMService

Keduanya 99% identik. Gabungkan jadi satu `App\Services\KegiatanService`:

```php
class KegiatanService
{
    public static function periode() { ... }
    public static function kegiatan($nama) { ... }
    public static function periodeId() { ... }
    public static function kegiatanId($nama) { ... }
    public static function weekRange($week = 1) { ... }
}
```

### 12. Tidak Ada Middleware `auth` Khusus di Beberapa Komponen Livewire

Sebagian besar komponen Livewire (terutama yang dipanggil via route `/kkn/screening/pdf`) tidak punya proteksi `auth` eksplisit di dalam class-nya.

**Perbaiki**: Pastikan setiap route Livewire yang butuh login dibungkus `Route::middleware('auth')`.

---

## 🟡 MEDIUM

### 13. API Key Eksternal di `.env` — Tidak Ada Rotasi

```env
FEEDER_API_KEY=uniwa7_secret1_123Yt797
FEEDER_API_URL=https://feedermate.uniwa.ac.id/api/users-mahasiswa
VALIDASI_KRS_URL=https://feedermate.uniwa.ac.id/api/users-validasi-krs
```

Jika `.env` bocor, attacker bisa panggil API internal kampus.

### 14. Tidak Ada Database Transaction

Operasi multi-step tanpa `DB::transaction()`:
- Tambah anggota kelompok (`Add.php`) — loop `KelompokUser::firstOrCreate()`
- Simpan screening + file (`Kknreg/Index.php`)

Risiko data tidak konsisten jika salah satu step gagal.

### 15. Validasi File Upload Kurang Ketat

**File**: `app/Livewire/Kegiatan/Pam/Laporanharian/Create.php`

```php
'foto' => $this->isEditing ? 'nullable' : 'required',
// ❌ tidak ada validasi tipe file saat edit
```

**Perbaiki**:
```php
'foto' => $this->isEditing ? 'nullable|image|mimes:jpg,jpeg,png|max:2048' : 'required|image|mimes:jpg,jpeg,png|max:2048',
```

### 16. Komentar Kode Mati di Banyak File

Banyak `// dd(...)`, `$this->dispatchBrowserEvent` yang di-comment. Harus dibersihkan sebelum production.

---

## 🟢 LOW

### 17. `Counter.php` — Komponen Contoh

**File**: `app/Livewire/Counter.php`

Komponen contoh Livewire tidak terpakai di route manapun. **Hapus**.

### 18. File Kosong `routes/auth.php`

Tidak digunakan. Hapus.

### 19. `open_basedir` di `.user.ini`

```ini
open_basedir=/www/wwwroot/lppm.uniwa.ac.id/:/tmp/
```

Bisa memblokir akses ke path sistem yang dibutuhkan composer/vendor. Jika ada error aneh, cek ini.

### 20. Tidak Ada Soft Deletes

Tidak ada `SoftDeletes` trait di model manapun. Data terhapus permanen.

### 21. `SESSION_ENCRYPT=false`

```env
SESSION_ENCRYPT=false
```

Aktifkan: `SESSION_ENCRYPT=true`.

### 22. Komponen Kosong

- `app/Livewire/Kegiatan/Kkn/Screening/Kesehatan/Index.php` — hanya render view
- `app/Livewire/Kegiatan/Kkn/Screening/Dokumen/Index.php` — hanya render view

Jika belum selesai, beri komentar `TODO` atau hapus sementara.

---

## 📋 DAFTAR PERBAIKAN (Checklist Prioritas)

| # | Tindakan | Prioritas | Status |
|---|----------|-----------|--------|
| 1 | `APP_ENV=production`, `APP_DEBUG=false` | 🔴 | ☐ |
| 2 | Permission: file `644`, folder `755`, `storage/` `775` | 🔴 | ☐ |
| 3 | Rate limiting di `Login.php` | 🔴 | ☐ |
| 4 | `role` & `permissions` di `$guarded` User model | 🔴 | ☐ |
| 5 | Perbaiki `$fillable` di `ScreeningQuestion` | 🔴 | ☐ |
| 6 | Hapus `index.html` aaPanel | 🔴 | ☐ |
| 7 | Rotasi DB password, API key, APP_KEY | 🔴 | ☐ |
| 8 | `dispatchBrowserEvent` → `dispatch` (v3) | 🟠 | ☐ |
| 9 | Optimasi query `Add.php` (jangan `get()` semua) | 🟠 | ☐ |
| 10 | Ganti LIKE JSON → JSON function / generated column | 🟠 | ☐ |
| 11 | Gabung KKNService + PAMService | 🟠 | ☐ |
| 12 | Tambah `DB::transaction()` di operasi multi-step | 🟡 | ☐ |
| 13 | Validasi tipe file upload (edit mode) | 🟡 | ☐ |
| 14 | Bersihkan komentar kode mati | 🟡 | ☐ |
| 15 | `SESSION_ENCRYPT=true` | 🟢 | ☐ |
| 16 | Hapus `Counter.php` | 🟢 | ☐ |
| 17 | Hapus `routes/auth.php` kosong | 🟢 | ☐ |

---

## 📊 Ringkasan

| Kategori | Jumlah |
|----------|--------|
| 🔴 Kritikal | 7 |
| 🟠 High | 5 |
| 🟡 Medium | 4 |
| 🟢 Low | 6 |
| **Total Temuan** | **22** |

**Kesimpulan**: Aplikasi sudah berfungsi dan cukup terstruktur dengan baik. Namun ada **7 temuan kritikal** yang harus segera diperbaiki sebelum production, terutama: **APP_DEBUG=true**, **mass assignment role**, **tanpa rate limiting login**, dan **file permission 777**. Kredensial yang terpapar wajib segera dirotasi.
