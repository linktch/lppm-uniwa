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
}