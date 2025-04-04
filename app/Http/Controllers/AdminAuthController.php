<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        // Không đặt middleware guest ở đây để tránh vòng lặp chuyển hướng
    }
    
    public function showLoginForm()
    {
        // Nếu đã đăng nhập rồi, chuyển hướng đến trang dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login_admin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ]);

        // Điều chỉnh thông tin đăng nhập để phù hợp với cấu trúc bảng và Model
        $authData = [
            'email' => $credentials['username'],
            'password' => $credentials['password']
        ];

        // Kiểm tra đăng nhập trực tiếp với database 
        $admin = Admin::where('email', $authData['email'])->first();

        if ($admin && $admin->Password === $authData['password']) {
            // Đăng nhập thành công, lưu thông tin admin vào session
            Auth::guard('admin')->loginUsingId($admin->email);
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'username' => __('Thông tin đăng nhập không chính xác.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}