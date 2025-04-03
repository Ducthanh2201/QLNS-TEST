<?php

use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    // Đăng nhập Admin
    Route::get('/admin/login', function () {
        return view('auth.login_admin');
    })->name('admin.login');
    
    Route::post('/admin/login', function () {
        // Logic xử lý đăng nhập Admin
        return redirect()->route('admin.dashboard');
    })->name('admin.login.post');
    
    // Đăng nhập Nhân viên
    Route::get('/login', function () {
        return view('auth.login_employee');
    })->name('employee.login');
    
    Route::post('/login', function () {
        // Logic xử lý đăng nhập Nhân viên
        return redirect()->route('employee.dashboard');
    })->name('employee.login.post');
    
    // Quên mật khẩu
    Route::get('/admin/password/reset', function () {
        return view('auth.forgot_password_admin');
    })->name('admin.password.request');
    
    Route::get('/password/reset', function () {
        return view('auth.forgot_password_employee');
    })->name('employee.password.request');
    
    // Đăng nhập với tài khoản xã hội
    Route::get('/auth/google', function () {
        // Logic xử lý đăng nhập Google
        return redirect()->route('admin.dashboard');
    })->name('auth.google');
    
    Route::get('/auth/github', function () {
        // Logic xử lý đăng nhập Github
        return redirect()->route('admin.dashboard');
    })->name('auth.github');
});

// Đăng xuất
Route::post('/logout', function () {
    // Logic xử lý đăng xuất
    return redirect('/login');
})->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Employees - Chỉnh sửa đường dẫn file view
    Route::get('/employees', function () {
        return view('admin.employee');  // Sửa thành file employee đơn lẻ
    })->name('employees.index');
    
    Route::get('/employees/create', function () {
        return view('admin.employee');  // Tạm thời trỏ vào cùng file
    })->name('employees.create');
    
    // Positions
    Route::get('/positions', function () {
        return view('admin.positions');
    })->name('positions.index');
    
    // Work Time
    Route::get('/worktime', function () {
        return view('admin.worktime');
    })->name('worktime.index');
    
    // Attendance
    Route::get('/attendance', function () {
        return view('admin.attendance');
    })->name('attendance.index');
    
    // Salary
    Route::get('/salary', function () {
        return view('admin.salary');
    })->name('salary.index');
    
    // Reward & Discipline
    Route::get('/reward-discipline', function () {
        return view('admin.reward_discipline');
    })->name('reward-discipline.index');
    
    // Statistics
    Route::get('/statistics', function () {
        return view('admin.statistics');
    })->name('statistics.index');
});

// Employee Routes
Route::prefix('employee')->name('employee.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('employees.dashboard');
    })->name('dashboard');
    
    // Profile
    Route::get('/profile', function () {
        return view('employees.profile');
    })->name('profile');
    
    // Attendance
    Route::get('/attendance', function () {
        return view('employees.attendance');
    })->name('attendance');
    
    // Leave Request
    Route::get('/leave-request', function () {
        return view('employees.leave_request');
    })->name('leave-request');
    
    // Salary
    Route::get('/salary', function () {
        return view('employees.salary');
    })->name('salary');
    
    // Check-in/out
    Route::post('/check-in', function () {
        // Logic xử lý check-in
        return redirect()->back()->with('success', 'Check-in thành công');
    })->name('check-in');
    
    Route::post('/check-out', function () {
        // Logic xử lý check-out
        return redirect()->back()->with('success', 'Check-out thành công');
    })->name('check-out');
});

// Fallback route
Route::fallback(function () {
    return redirect('/');
});
