<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Str;

class CoreController extends Controller
{
    //
    protected function defaultTimNumber($nama)
    {
        return "Tim - " . $nama . "_" . time();
    }

    protected function defaultDivisiNumber($jenis)
    {
        // $jenisSub = Str::substr($jenis, 0, 2);
        if ($jenis != "PC") {
            return "M_" . $jenis . " - " . time();
        } else {
            return "P_" . $jenis . " - " . time();
        }
    }

    protected function defaultDaftarNumber($kodeJadwal, $namaTim)
    {
        return "Daftar - " . $namaTim . "_" . sprintf('%02d', $kodeJadwal);
    }
}
