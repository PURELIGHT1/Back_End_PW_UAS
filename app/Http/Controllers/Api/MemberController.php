<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idUser = Auth::user()->id;
        $cekUser = DB::select("SELECT users.id as id_user, users.role as role_user FROM users 
                               where users.id = '$idUser'");
        $cekMember = DB::select("SELECT members.id as id_member, members.name as nama_member, members.nickname as nickname_member, divisis.nama as nama_divisi, members.user as kapten 
                                 FROM members 
                                 JOIN users ON members.user = users.id 
                                 JOIN divisis ON members.divisi = divisis.kode_divisi 
                                 where members.user = '$idUser'");
        if($cekUser[0]->role_user == "Admin"){
             $member = DB::select("SELECT members.id as id_member, members.name as nama_member, members.nickname as nickname_member, divisis.nama as nama_divisi, members.user as kapten 
                                 FROM members 
                                 JOIN users ON members.user = users.id 
                                 JOIN divisis ON members.divisi = divisis.kode_divisi");
            return new MemberResource(
                true,
                'List Data Member',
                $member
            );
            
        }else {
            return new MemberResource(
                true,
                'List Data Member',
                $cekMember
            );
        }
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
            'nickname_member' => 'required|max:100',
            'nama_member' => 'required|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $idUser = Auth::user()->id;
        $user = DB::select("SELECT * FROM users WHERE users.id = '$idUser'");

        $create['name'] = $request->nama_member;
        $create['nickname'] = $request->nickname_member;
        $create['divisi'] = $user[0]->divisi;
        $create['user'] = $user[0]->id;
        $create['status'] = 1;

        $createMember = Member::create($create);

        return new MemberResource(
            true,
            'Data Member Berhasil Ditambah!',
            $createMember
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
        $cekMember = DB::select("SELECT members.id as id_member, members.* FROM members WHERE members.id = '$id'");
        if ($cekMember[0]->divisi != null || $cekMember[0]->tim != null) {
            $member = DB::select("SELECT members.id as id_member, members.* FROM members 
                              JOIN users ON members.user = users.id
                              JOIN divisis ON members.divisi = divisis.kode_divisi
                              JOIN tims ON members.tim = tims.kode_tim
                              WHERE members.id = '$id'");
            return new MemberResource(
                true,
                'List Data Member',
                $member
            );
        } else {
            if (!is_null($cekMember)) {
                return new MemberResource(
                    true,
                    'Data Member Berhasil Diambil!',
                    $cekMember
                );
            }
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
            'nickname_member' => 'required|max:100',
            'nama_member' => 'required|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $member = DB::select("SELECT * FROM members WHERE members.id = '$id'");

        if (is_null($member)) {
            return new MemberResource(
                false,
                'Data Member Tidak Ditemukan!',
                null
            );
        }

        $member = DB::update(DB::raw("UPDATE members SET members.name = '$request->nama_member', members.nickname = '$request->nickname_member' where members.id = '$id'"));

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
        $member = DB::select("SELECT * FROM members WHERE members.id = '$id'");

        if (is_null($member)) {
            return new MemberResource(
                false,
                'Data Member Tidak Ditemukan!',
                null
            );
        }

        // $memberCek = DB::delete(DB::raw("UPDATE members SET members.name = '$request->name', members.nickname = '$request->nickname' where members.id = '$id'"));
        $user = DB::table('members')->where('id', '=', $id)->delete();
        return new MemberResource(
            true,
            'Data Member Berhasil Dihapus!',
            $user
        );
    }
}
