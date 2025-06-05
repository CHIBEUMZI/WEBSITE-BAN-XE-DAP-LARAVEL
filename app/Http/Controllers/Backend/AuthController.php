<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct() {}

    public function index()
    {
        if(Auth::check()) {
            // Kiểm tra vai trò người dùng và chuyển hướng
            if(Auth::user()->role == 'Admin') {
                return redirect()->route('dashboard.index');
            }
            return redirect()->route('client.layout');  // Trang cho User
        }
        return view('backend.auth.login');
    }

  
    public function login(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];


        if (Auth::attempt($credentials)) {
            // Kiểm tra vai trò và chuyển hướng phù hợp
            if(Auth::user()->role == 'Admin') {
                return redirect()->route('dashboard.index')->with('success', 'Đăng nhập thành công');
            }else{
                return redirect()->route('client.home')->with('success', 'Đăng nhập thành công');
            }
            
        }

        return redirect()->route('auth.login')->with('error', 'Email hoặc mật khẩu không chính xác');
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');

    }
}
