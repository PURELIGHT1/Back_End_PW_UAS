<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarJadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_daftar',
        'status',
        'tim',
        'jadwal',
    ];
}
