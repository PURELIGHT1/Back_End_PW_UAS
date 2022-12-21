<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CoreController;
use App\Http\Resources\DivisiResource;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DivisiController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $divisi = Divisi::latest()->get();

        return new DivisiResource(
            true,
            'List Data Divisi',
            $divisi
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
            'nama' => 'required|max:100|unique:divisis',
            'jenis' => 'required|max:100',
            'genre' => 'required|max:100',
        ]);


        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $create['kode_divisi'] = $this->defaultDivisiNumber($request['jenis']);

        $createDivisi = Divisi::create($create);

        return new DivisiResource(
            true,
            'Data Divisi Berhasil Ditambah!',
            $createDivisi
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
        // $divisi = Divisi::findOrFail($id);
        $divisi = DB::select("SELECT * FROM divisis WHERE divisis.kode_divisi = '$id'");

        if (!is_null($divisi)) {
            return new DivisiResource(
                true,
                'Data Divisi Berhasil Diambil!',
                $divisi
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
            'nama' => 'required|max:100',
            'jenis' => 'required|max:100',
            'genre' => 'required|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $divisi = DB::select("SELECT * FROM divisis WHERE divisis.kode_divisi = '$id'");

        if (is_null($divisi)) {
            return new DivisiResource(
                false,
                'Data Divisi Tidak Ditemukan!',
                null
            );
        }

        $divisi = DB::update(DB::raw("UPDATE divisis SET divisis.nama = '$request->nama', divisis.jenis = '$request->jenis', divisis.genre = '$request->genre' where divisis.kode_divisi = '$id'"));

        return new DivisiResource(
            true,
            'Data Divisi Berhasil Diubah!',
            $divisi
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
        $divisi = DB::select("SELECT * FROM divisis WHERE divisis.kode_divisi = '$id'");

        if (is_null($divisi)) {
            return new DivisiResource(
                false,
                'Data Divisi Tidak Ditemukan!',
                null
            );
        }

        DB::table('divisis')->where('kode_divisi', $id)->delete();

        return new DivisiResource(
            true,
            'Data Divisi Berhasil Dihapus!',
            null
        );
    }
}
