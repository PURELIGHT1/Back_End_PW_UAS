<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registration = $request->all();

        $validate = Validator::make($registration, [
            'name' => 'required|max:15',
            'nickname' => 'required',
            'nohp' => 'required|max:13|regex:(08)',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
            'divisi' => 'required',
            'pasphoto' => 'image|mimes:png,jpg,jpeg|max:5120'
            // 'pasphoto' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if ($image = $request->file('pasphoto')) {
            $imagePath = 'foto';
            $profileImage = date('Ymd') . "-" . time() . "." . $image->getClientOriginalExtension();
            $image->move(public_path($imagePath), $profileImage);
            $registration['pasphoto'] = "$profileImage";
        } else {
            unset($registration['pasphoto']);
        }

        // $imageName = $request->file('image')->getClientOriginalName();
        // $request->image->move(public_path('images'), $imageName);
        // $registration['image'] = $imageName;
        $registration['password'] = bcrypt($request->password);
        $registration['role'] = 'Member';

        $user = User::create($registration);

        $userCek = DB::select("SELECT * FROM users WHERE users.email = '$request->email'");
        $create['nickname'] = $userCek[0]->nickname;
        $create['name'] = $userCek[0]->name;
        $create['divisi'] = $userCek[0]->divisi;
        $create['user'] = $userCek[0]->id;
        $create['status'] = 0;

        Member::create($create);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('verification.notice')->with(['message' => 'Registration Success, Please register your email', 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $login = $request->all();

        $validate = Validator::make($login, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if (!Auth::attempt($login)) {
            return response(['message' => 'Invalid Credentials'], 402);
        }
        
        // $email = $login['email'];
        // $user = DB::select("SELECT * FROM users WHERE users.email = '$email'");
        // $user = DB::select("SELECT * FROM users JOIN divisis ON users.divisi = divisis.kode_divisi WHERE users.email = '$request->email'");
        
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at == null) {
            return response(['message' => 'Please verify your email'], 401);
        } else {
            $token = $user->createToken('Authentication Token')->accessToken;
            return response(['message' => 'Authenticated', 'user' => $user, 'token_type' => 'Bearer', 'access_token' => $token]);
            // return response(['message' => 'Authenticated', 'user' => $user]);
        }
    }


    //logout dan hapus token
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Logout Success']);
    }

    public function loginAdmin(Request $request)
    {
        $login = $request->all();
        $create =  $request->all();
        $validate = Validator::make($login, [
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if ($login['email'] == "admin@gmail.com") {
            $create['name'] = 'Admin Four Kings E-Sport';
            $create['nickname'] = '-';
            $create['nohp'] = '0823-7517-8720';
            $create['email'] = $request->email;
            $create['password'] = bcrypt($request->password);
            $create['pasphoto'] = 'AdminFourKings.jpg';
            $create['email_verified_at'] = now();
            $create['role'] = 'admin';

            $user = User::create($create);
            if (!Auth::attempt($login)) {
                return response(['message' => 'Invalid Credentials'], 401);
            }

            $user = User::where('email', $request->email)->first();
            if ($user->email_verified_at == null) {
                return response(['message' => 'Please verify your email'], 401);
            } else {
                $token = $user->createToken('Authentication Token')->accessToken;
                return response(['message' => 'Authenticated', 'user' => $user, 'token_tyope' => 'Bearer', 'access_token' => $token]);
                // return response(['message' => 'Authenticated', 'user' => $user]);
            }
        }
    }
}
