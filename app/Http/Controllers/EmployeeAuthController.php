<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_employee');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        // Tìm nhân viên trực tiếp
        $employee = Employee::where('MaNV', $credentials['employee_id'])->first();
        
        // Nếu tìm thấy và mật khẩu khớp
        if ($employee && $credentials['password'] === $employee->Password) {
            // Đăng nhập thủ công
            Auth::guard('employee')->loginUsingId($employee->MaNV);
            
            // Hoặc tùy chọn này nếu trên không hoạt động
            // session(['employee_id' => $employee->MaNV]);
            
            return redirect()->route('employee.dashboard');
        }

        // Authentication failed
        throw ValidationException::withMessages([
            'employee_id' => __('Thông tin đăng nhập không chính xác.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('employee.login');
    }
}