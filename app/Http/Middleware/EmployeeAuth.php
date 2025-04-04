<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập bằng guard employee hay chưa
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login');
        }

        return $next($request);
    }
}