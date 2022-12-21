<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = User::latest()->get();

        return new UserResource(
            true,
            'List Data Pengguna',
            $user
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
        $user = DB::select("SELECT * FROM users JOIN divisis ON users.divisi = divisis.kode_divisi where users.id = '$id'");

        if (!is_null($user)) {
            return new UserResource(
                true,
                'Data Pengguna Berhasil Diambil!',
                $user
            );
        } else {
            return new UserResource(
                false,
                'Data Tim Tidak Ditemukan!',
                null
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
            'name' => 'required|max:15',
            'nickname' => 'required',
            'nohp' => 'required|max:13|regex:(08)',
            'password' => 'required',
            'divisi' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

         $member = DB::update(DB::raw("UPDATE users SET users.name = '$request->name',
                                       users.nohp = '$request->nickname_member',
                                       users.nickname ='$request->nickname',
                                       users.divisi = '$request->divisi',
                                       users.password ='$request->password'
                                       where users.id = '$id'"));

        return new MemberResource(
            true,
            'Data Member Berhasil Diubah!',
            $member
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
        $user = User::find($id);
        if (is_null($user)) {
            return new UserResource(false, 'Data Pengguna Tidak Ditemukan!', null);
        }
        $user->update([
            'email_verified_at' => null
        ]);
        return new UserResource(true, 'Data Pengguna Berhasil Dihapus!', $user);
    }
}
