<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\MailSend;
use App\Models\User;
use App\Models\Saldo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'username' => 'required',
            'password' => 'required|min:8',
            'email' => 'required|email:rfc,dns|unique:users',
            'notelp' => 'required|min:10|max:13',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $registrationData['password'] = ($request->password);
        $registrationData['type_pengguna'] = 'customer';

        $saldo = new Saldo();
        $saldo->JUMLAH = 0;
        $saldo->POIN = 0;
        $saldo->save();

        $registrationData['id_saldo'] = $saldo->ID_SALDO;

        $str = Str::random(100);
        $registrationData['verify_key'] = $str;
        $registrationData['active'] = false;
        $user = User::create($registrationData);

        $saldo->ID_USER = $user->id_user;
        $saldo->save();

        $details = [
            'username' => $request->username,
            'website' => 'Atma Kitchen',
            'datetime' => Carbon::now(),
            'url' => request()->getHttpHost() . '/api/verify/' . $str,
        ];

        Mail::to($request->email)->send(new MailSend($details));

        return response([
            'message' => 'Register Success',
            'data' => $user,
            'url' => request()->getHttpHost() . '/register/verify/' . $str,
        ], 200);
    }

    public function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')
            ->where('verify_key', $verify_key)
            ->exists();

        if ($keyCheck) {
            $user = User::where('verify_key', $verify_key)
                ->update([
                    'active' => 1,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                ]);
            return ([
                'Message' => "Verifikasi berhasil. Akun anda sudah aktif.",
            ]);
        } else {
            return ([
                'message' => "Keys tidak valid.",
                'verify' => $verify_key,
                'data' => $keyCheck
            ]);
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:8',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message' => 'Invalid email & password match'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'data' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
}
