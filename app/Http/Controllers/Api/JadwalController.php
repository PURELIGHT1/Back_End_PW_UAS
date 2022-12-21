<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\JadwalResource;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $jadwal = DB::select("SELECT jadwals.id as id_jadwal, 
                              jadwals.judul as judul_jadwal, 
                              jadwals.jenis_kegiatan as jenis, 
                              jadwals.tgl_mulai as mulai, 
                              jadwals.tgl_selesai as selesai, 
                              jadwals.tempat as tempat_jadwal, 
                              jadwals.biaya as biaya_jadwal, 
                              jadwals.deskripsi as deskripsi_jadwal FROM jadwals");

        return new JadwalResource(
            true,
            'List Data Jadwal',
            $jadwal
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
        $validate = Validator::make($create, [
            'judul_jadwal' => 'required|max:100',
            'jenis' => 'required',
            'mulai' => 'required|date|before_or_equal:selesai',
            'selesai' => 'required|date',
            'tempat_jadwal' => 'required|max:255',
            // 'poster_jadwal' => 'image|mimes:png,jpg,jpeg|max:5120',
        ]);


        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        if ($request['jenis'] == "Latihan") {
            $create['biaya'] = 0;
        }

        // if ($image = $request->file('poster_jadwal')) {
        //     $imagePath = 'poster';
        //     $posterImage = "Poster" . date('Ymd') . "-" . time() . "." . $image->getClientOriginalExtension();
        //     $image->move(public_path($imagePath), $posterImage);
        //     $create['poster'] = "$posterImage";
        // } else {
        //     unset($create['poster']);
        // }
        
        $create['judul'] = $request->judul_jadwal;
        $create['jenis_kegiatan'] = $request->jenis;
        $create['tgl_mulai'] = $request->mulai;
        $create['tgl_selesai'] = $request->selesai;
        $create['tempat'] = $request->tempat_jadwal;
        $create['deskripsi'] = $request->deskripsi_jadwal;

        $data = Jadwal::create($create);

        return new JadwalResource(
            true,
            'Data Jadwal Berhasil Ditambah!',
            $data
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
        $jadwal = Jadwal::findOrFail($id);

        if (!is_null($jadwal)) {
            return new JadwalResource(
                true,
                'Data Jadwal Berhasil Diambil!',
                $jadwal
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
        $update = $request->all();
        $validate = Validator::make($update, [
            'judul_jadwal' => 'required|max:100',
            'jenis' => 'required',
            'mulai' => 'required|date|before_or_equal:selesai',
            'selesai' => 'required|date',
            'tempat_jadwal' => 'required|max:255',
            // 'poster_jadwal' => 'image|mimes:png,jpg,jpeg|max:5120',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        if ($request['jenis'] == "Latihan") {
            $update['biaya'] = 0;
        }

        // if ($image = $request->file('poster_jadwal')) {
        //     $imagePath = 'poster';
        //     $profileImage = "Poster" . date('Ymd') . "-" . time() . "." . $image->getClientOriginalExtension();
        //     $image->move(public_path($imagePath), $profileImage);
        //     $update['poster'] = "$profileImage";
        // } else {
        //     unset($update['poster']);
        // }
        
        $jadwal = DB::update(DB::raw("UPDATE jadwals SET jadwals.judul = '$request->judul_jadwal', 
                                      jadwals.jenis_kegiatan = '$request->jenis',
                                      jadwals.tgl_mulai = '$request->mulai',
                                      jadwals.tgl_selesai = '$request->selesai',
                                      jadwals.tempat = '$request->tempat_jadwal',
                                      jadwals.deskripsi = '$request->deskripsi_jadwal'
                                      where jadwals.id = '$id'"));
        // $data = Jadwal::findOrFail($id);
        // //update post without image
        // $data->update([
        //     'judul' => $request->judul_jadwal,
        //     'jenis_kegiatan' => $request->jenis,
        //     'tgl_mulai' => $request->mulai,
        //     'tgl_selesai' => $request->selesai,
        //     'tempat' => $request->tempat_jadwal,
        //     // 'poster' => $update->poster,
        //     'deskripsi' => $request->deskripsi,
        //     'biaya' => $update->biaya,
        // ]);

        return new JadwalResource(
            true,
            'Data Jadwal Berhasil Diubah!',
            $jadwal
        );
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
        $jadwal = Jadwal::find($id);
        if (is_null($jadwal)) {
            return new JadwalResource(false, 'Data Jadwal Tidak Ditemukan!', null);
        }
        $jadwal->delete();
        return new JadwalResource(true, 'Data Jadwal Berhasil Dihapus!', $jadwal);
    }
}
