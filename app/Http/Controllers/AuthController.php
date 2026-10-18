<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login',[
            'title'=> 'Masuk Akun',
        ]);
    }
    public function loginact(Request &$request){
        $request->validate([
			'username'=> 'required',
			'password'=> 'required|min:8'
		],[
			'username.required'=> 'Username tidak boleh kosong.',
			'password.required'=> 'Password tidak boleh kosong.',
			'password.min'=> 'Password minimal 8 karakter',
		]);
        if(Auth::attempt([
			'username' => $request->username,
			'password' => $request->password
		])){
            $user = User::find(auth()->user()->id);
            $user->update([
                'last_login_at'=> now(),
            ]);
			$request->session()->regenerate();
            if($user->getRoleNames()[0] == 'camaba'){
                return redirect()->route('my.application.index');
            }
			return redirect()->route('dashboard')->with('success','berhasil login');
		}else{
			return redirect()->route('login')->withErrors(['message'=> 'username atau password salah'])->withInput();
		}

    }
    public function logout(Request &$request){
        // Logout user dari guard default (biasanya 'web')
		Auth::logout();
		// Hapus seluruh session
		$request->session()->invalidate();
		// Regenerasi CSRF token agar sesi benar-benar aman
		$request->session()->regenerateToken();
		// Redirect ke halaman login dengan pesan sukses
		return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
