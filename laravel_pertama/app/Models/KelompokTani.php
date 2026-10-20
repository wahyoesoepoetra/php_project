<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokTani extends Model
{
    use HasFactory;

    public $primaryKey = 'id_kelompok_tani';

    protected $fillable = [
        'nama_kelompok',
    ];

    public function petanis()
    {
        return $this->hasMany(
            Petani::class,
            'id_kelompok_tani',
            'id_kelompok_tani'
        );
    }
}