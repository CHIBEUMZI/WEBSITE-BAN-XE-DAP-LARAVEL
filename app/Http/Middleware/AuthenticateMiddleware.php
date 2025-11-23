<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn phải đăng nhập mới dùng được chức năng này');
        }

        // Kiểm tra vai trò admin (giả sử cột "role" trong bảng users)
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập chức năng này');
        }

        return $next($request);
    }
}
