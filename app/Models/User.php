<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'permissions',
        'last_login',
        'first_name',
        'last_name',
        'phone',
        'username',  // 🔥 WAJIB ADA
        'foto',
        'id_mahasiswa',
        'id_registrasi_mahasiswa',
        'id_prodi',
        'status_mahasiswa',
        'id_periode',
        'data_mahasiswa',
        'profile_pic',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'data_mahasiswa' => 'array',
    ];

    public function kelompok()
    {
        return $this
            ->belongsToMany(Kelompok::class, 'kelompok_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function hasRole($role)
    {
        return in_array($this->role, (array) $role);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'user_id');
    }

    public function kelompokUser()
    {
        return $this->hasMany(\App\Models\KelompokUser::class);
    }

    public function screeningAnswers()
    {
        return $this->hasMany(ScreeningAnswer::class);
    }

    public function pejabatSignaturs()
    {
        return $this->hasMany(PejabatSignatur::class, 'user_id');
    }
        // Accessor for foto URL
    public function getFotoUrlAttribute()
    {
        if ($this->foto && \Storage::disk('public')->exists($this->foto)) {
            return \Storage::url($this->foto);
        }
        return null;
    }
}
