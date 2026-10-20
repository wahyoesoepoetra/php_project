<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    use HasFactory;

    public $primaryKey = 'id_penjual';

    protected $fillable = [
        'nama',
        'nik',
        'alamat',
        'telp',
        'foto',
        'id_kelompok_tani',
        'status',
    ];

    public function kelompoktani()
    {
        return $this->belongsTo(
            KelompokTani::class,
            'id_kelompok_tani',
            'id_kelompok_tani'
        );
    }
}