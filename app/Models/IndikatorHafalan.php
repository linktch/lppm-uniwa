<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorHafalan extends Model
{
    protected $table = 'indikator_hafalan';
    
    protected $fillable = [
        'nama_indikator',
        'laki_laki',
        'perempuan',
        'urutan'
    ];
    
    protected $casts = [
        'laki_laki' => 'boolean',
        'perempuan' => 'boolean'
    ];
    
    /**
     * Scope untuk indikator berdasarkan jenis kelamin
     */
    public function scopeForGender($query, $gender)
    {
        if ($gender == 'L' || $gender == 'Laki-laki' || $gender == 'Laki-Laki') {
            return $query->where('laki_laki', true);
        } else {
            return $query->where('perempuan', true);
        }
    }
    
    /**
     * Scope untuk indikator yang diurutkan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }
}