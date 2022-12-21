<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    //
    public function notice()
    {
        return response()->json([
            'message' => 'Please verify your email'
        ], 200);
    }

    public function verify(Request $request)
    {
        $idUser = $request->route('id');
        $user = User::findOrFail($idUser);
        $member = DB::select("SELECT * FROM members WHERE members.user = '$idUser'");
        $user->update([
            'email_verified_at' => now()
        ]);
        $idMember = $member[0]->id;
        DB::update(DB::raw("UPDATE members SET members.status = 1 where members.id = '$idMember'"));
        header("Location: https://fourking.000webhostapp.com/");

    }
}
