<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KelompokUser extends Model
{
    protected $table = "kelompok_user";

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'role'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}