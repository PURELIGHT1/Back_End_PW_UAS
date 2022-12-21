<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CoreController;
use App\Http\Resources\TimResource;
use App\Models\Tim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TimController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tim = DB::select("SELECT tims.kode_tim as id, tims.nama as nama_tim, tims.deskripsi as deskripsi_tim FROM tims");

        return new TimResource(
            true,
            'List Data Tim',
            $tim
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
        $validate =  Validator::make($create, [
            'nama_tim' => 'required|max:100',
            'deskripsi_tim' => 'required|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }


        $create['nama'] = $request->nama_tim;
        $create['deskripsi'] = $request->deskripsi_tim;
        $create['kode_tim'] = $this->defaultTimNumber($request->nama_tim);

        $createTim = Tim::create($create);

        return new TimResource(
            true,
            'Data Tim Berhasil Ditambah!',
            $createTim
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
        $tim = DB::select("SELECT * FROM tims WHERE tims.kode_tim = '$id'");

        if (!is_null($tim)) {
            return new TimResource(
                true,
                'Data Tim Berhasil Diambil!',
                $tim
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
            'nama_tim' => 'required|max:100',
            'deskripsi_tim' => 'required|max:100'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        };

        $tim = DB::select("SELECT * FROM tims WHERE tims.kode_tim = '$id'");

        if (is_null($tim)) {
            return new TimResource(
                false,
                'Data Tim Tidak Ditemukan!',
                null
            );
        };
        
        $tim = DB::update(DB::raw("UPDATE tims SET tims.nama = '$request->nama_tim', tims.deskripsi = '$request->deskripsi_tim' where tims.kode_tim = '$id'"));


        return new TimResource(
            true,
            'Data Tim Berhasil Diubah!',
            $tim
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
        $tim = DB::select("SELECT * FROM tims WHERE tims.kode_tim = '$id'");

        if (is_null($tim)) {
            return new TimResource(
                false,
                'Data Tim Tidak Ditemukan!',
                null
            );
        }

        DB::table('tims')->where('kode_tim', $id)->delete();

        return new TimResource(
            true,
            'Data Tim Berhasil Dihapus!',
            null
        );
    }
}
