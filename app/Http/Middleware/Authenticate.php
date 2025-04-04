<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Kiểm tra nếu đang truy cập vào khu vực admin thì chuyển hướng đến trang đăng nhập admin
            if (str_starts_with($request->path(), 'admin')) {
                return route('admin.login');
            }
            
            // Mặc định chuyển hướng đến trang đăng nhập nhân viên
            return route('employee.login');
        }
    }
}