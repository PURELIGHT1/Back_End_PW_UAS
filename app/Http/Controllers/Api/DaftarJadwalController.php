<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CoreController;
use App\Http\Resources\DaftarJadwalResource;
use App\Models\DaftarJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DaftarJadwalController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $daftarJadwal = DB::select("SELECT daftar_jadwals.* FROM daftar_jadwals 
                              JOIN jadwals ON daftar_jadwals.jadwal = jadwals.id
                              JOIN tims ON daftar_jadwals.tim = tims.kode_tim");

        return new DaftarJadwalResource(
            true,
            'List Data Daftar Jadwal',
            $daftarJadwal
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $create = $request->all();
        $idJadwal = $request['jadwal'];

        $idUser = Auth::user()->id;
        $memberCek = DB::select("SELECT members.*, tims.nama as nama_tim, tims.kode_tim as kode FROM members JOIN tims ON members.tim = tims.kode_tim WHERE members.user = '$idUser'");
        $tim = $memberCek[0]->nama_tim;
        $Idtim = $memberCek[0]->kode;

        $jadwalCek = DB::select("SELECT jadwals.id as id_jadwal FROM jadwals WHERE jadwals.id = '$idJadwal'");
        $jadwal = $jadwalCek[0]->id_jadwal;
        // var_dump($tim);
        // var_dump($jadwalCek[0]->id_jadwal);
        // var_dump($idJadwal);
        // exit;

        $create['kode_daftar'] = $this->defaultDaftarNumber($jadwal, $tim);
        $create['status'] = 1;
        $create['tim'] = $Idtim;
        $create['jadwal'] = $jadwal;

        $createDaftarJadwal = DaftarJadwal::create($create);

        return new DaftarJadwalResource(
            true,
            'Data Daftar Jadwal Berhasil Ditambah!',
            $createDaftarJadwal
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $daftarJadwal = DB::select("SELECT daftar_jadwals.*, jadwals.judul as jadwal_nama, jadwals.tgl_mulai as mulai, jadwals.tgl_selesai as selesai, tims.nama as tim_nama
                            FROM daftar_jadwals 
                            JOIN jadwals ON daftar_jadwals.jadwal = jadwals.id
                            JOIN tims ON daftar_jadwals.tim = tims.kode_tim
                            WHERE daftar_jadwals.kode_daftar = '$id'");

        if (!is_null($daftarJadwal)) {
            return new DaftarJadwalResource(
                true,
                'Data Daftar Jadwal Berhasil Diambil!',
                $daftarJadwal
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $daftarJadwal = DB::select("SELECT * FROM daftar_jadwals WHERE daftar_jadwals.kode_daftar = '$id'");

        if (is_null($daftarJadwal)) {
            return new DaftarJadwalResource(
                false,
                'Data Daftar Jadwal Tidak Ditemukan!',
                null
            );
        }

        DB::table('daftar_jadwals')->where('kode_daftar', $id)->delete();

        return new DaftarJadwalResource(
            true,
            'Data Daftar Jadwal Berhasil Dihapus!',
            null
        );
    }
}
