<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'jenis_kegiatan',
        'tgl_mulai',
        'tgl_selesai',
        'tempat',
        'biaya',
        'deskripsi',
        'poster',
    ];
}
