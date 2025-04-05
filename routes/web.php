<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Middleware\EmployeeAuth;

// Thêm dòng này trước các route khác
Route::redirect('/login', '/login')->name('login');

// Trang chủ - Chuyển hướng đến trang đăng nhập nhân viên
Route::get('/', function () {
    return redirect()->route('employee.login');
});

// Auth Routes cho Admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth:admin');

// Auth Routes cho Nhân viên
Route::get('/login', [EmployeeAuthController::class, 'showLoginForm'])->name('employee.login');
Route::post('/login', [EmployeeAuthController::class, 'login'])->name('employee.login');
Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('employee.logout');

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

// Admin Routes - thêm middleware auth:admin
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Employees - Sử dụng resource controller
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
    
    // Toggle Employee Status
    Route::post('/employees/{employee}/toggle-status', [App\Http\Controllers\Admin\EmployeeController::class, 'toggleStatus'])
        ->name('employees.toggle-status');
    
    // Positions - Sử dụng resource controller
    Route::resource('positions', \App\Http\Controllers\Admin\PositionController::class);
    
    // Toggle Position Status
    Route::post('/positions/{position}/toggle-status', [App\Http\Controllers\Admin\PositionController::class, 'toggleStatus'])
        ->name('positions.toggle-status');
    
    // Work Time
    Route::get('/worktime', [App\Http\Controllers\Admin\WorkTimeController::class, 'index'])
        ->name('worktime.index');
    Route::post('/worktime/config', [App\Http\Controllers\Admin\WorkTimeController::class, 'saveConfig'])
        ->name('worktime.config');
    Route::get('/worktime/create', [App\Http\Controllers\Admin\WorkTimeController::class, 'create'])
        ->name('worktime.create');
    Route::post('/worktime', [App\Http\Controllers\Admin\WorkTimeController::class, 'store'])
        ->name('worktime.store');
    Route::get('/worktime/{timeKeeping}/edit', [App\Http\Controllers\Admin\WorkTimeController::class, 'edit'])
        ->name('worktime.edit');
    Route::put('/worktime/{timeKeeping}', [App\Http\Controllers\Admin\WorkTimeController::class, 'update'])
        ->name('worktime.update');
    Route::post('/worktime/{timeKeeping}/delete', [App\Http\Controllers\Admin\WorkTimeController::class, 'destroy'])
        ->name('worktime.destroy');
    Route::post('/worktime/generate', [App\Http\Controllers\Admin\WorkTimeController::class, 'generate'])
        ->name('worktime.generate');
    Route::get('/worktime/export', [App\Http\Controllers\Admin\WorkTimeController::class, 'export'])
        ->name('worktime.export');
    Route::post('/worktime/update-status', [App\Http\Controllers\Admin\WorkTimeController::class, 'updateStatus'])
        ->name('worktime.update-status');
    Route::get('/worktime/fix-all', [App\Http\Controllers\Admin\WorkTimeController::class, 'fixAllStatuses'])
        ->name('worktime.fix-all');
    Route::get('/worktime/{id}/restore', [App\Http\Controllers\Admin\WorkTimeController::class, 'restore'])->name('worktime.restore');
    Route::get('/worktime/update-deleted-statuses', [App\Http\Controllers\Admin\WorkTimeController::class, 'updateDeletedStatuses'])->name('worktime.update-deleted-statuses');
    
    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/attendance/{id}/restore', [AttendanceController::class, 'restore'])->name('attendance.restore');
    Route::post('/attendance/generate', [AttendanceController::class, 'generate'])->name('attendance.generate');
    Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
    
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

    // Routes quản lý chấm công
    Route::prefix('attendance')->name('attendance.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('store');
        Route::get('/{attendance}', [App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('show');
        Route::put('/{attendance}', [App\Http\Controllers\Admin\AttendanceController::class, 'update'])->name('update');
        Route::delete('/{attendance}', [App\Http\Controllers\Admin\AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/restore', [App\Http\Controllers\Admin\AttendanceController::class, 'restore'])->name('restore');
        Route::post('/generate', [App\Http\Controllers\Admin\AttendanceController::class, 'generate'])->name('generate');
        Route::get('/export', [App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('export');
    });
});

// Employee Routes
Route::prefix('employee')->name('employee.')->middleware(EmployeeAuth::class)->group(function () {
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
    
    // Check-in và Check-out có thể là POST routes
    Route::post('/check-in', function () {
        // Logic xử lý check-in
        return redirect()->back()->with('success', 'Check-in thành công!');
    })->name('check-in');
    
    Route::post('/check-out', function () {
        // Logic xử lý check-out
        return redirect()->back()->with('success', 'Check-out thành công!');
    })->name('check-out');
});

// Fallback route
Route::fallback(function () {
    return redirect('/');
});
