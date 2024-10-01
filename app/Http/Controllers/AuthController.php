<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        
        $user = DB::table('users')->where('username', $request->username)->first();
        
        if ($user && $this->verifyPassword($request->password, $user->password)) {
            auth()->loginUsingId($user->id);
            return redirect()->route('dashboard'); 
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    private function verifyPassword($inputPassword, $hashedPassword)
    {
        // Di sini kita anggap hashedPassword adalah hasil hash dari Yii1
        // Misalnya jika hashedPassword menggunakan MD5:
        return md5($inputPassword) === $hashedPassword;
        
        // Jika menggunakan bcrypt:
        // return Hash::check($inputPassword, $hashedPassword);
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}
