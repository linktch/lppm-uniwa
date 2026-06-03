<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'laporan_id',
        'user_id',
        'role',
        'komentar',
        'status',
        'parent_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_APPROVED = 'approved';
    const STATUS_REVISI = 'revisi';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_SUBMITTED = 'submitted';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporan()
    {
        return $this->belongsTo(LaporanHarian::class, 'laporan_id');
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'parent_id')->with('user');
    }

    public function parent()
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }
}