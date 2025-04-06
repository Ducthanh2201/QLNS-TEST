<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProfileController extends Controller
{
    /**
     * Hiển thị thông tin cá nhân
     */
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        return view('employees.profile', compact('employee'));
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        
        $validator = Validator::make($request->all(), [
            'inputName' => 'required|string|max:100',
            'inputEmail' => 'required|email|max:100',
            'inputPhone' => 'required|string|max:15',
            'inputAddress' => 'required|string|max:255',
            'inputPassword' => 'nullable|string|min:6|confirmed',
        ], [
            'inputName.required' => 'Họ tên không được để trống',
            'inputEmail.required' => 'Email không được để trống',
            'inputEmail.email' => 'Email không đúng định dạng',
            'inputPhone.required' => 'Số điện thoại không được để trống',
            'inputAddress.required' => 'Địa chỉ không được để trống',
            'inputPassword.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'inputPassword.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            // Cập nhật thông tin cơ bản thông qua query builder để tránh lỗi save()
            $updateData = [
                'TenNV' => $request->inputName,
                'email' => $request->inputEmail,
                'DienThoai' => $request->inputPhone,
                'DiaChi' => $request->inputAddress
            ];
            
            // Nếu có cập nhật mật khẩu mới
            if ($request->filled('inputPassword')) {
                $updateData['Password'] = $request->inputPassword;
            }
            
            // Thực hiện cập nhật trực tiếp qua query builder
            Employee::where('MaNV', $employee->MaNV)->update($updateData);
            
            return redirect()->route('employee.profile')->with('success', 'Cập nhật thông tin thành công!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi cập nhật thông tin: ' . $e->getMessage());
        }
    }
    
    /**
     * Cập nhật ảnh đại diện
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh đại diện',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.mimes' => 'Định dạng ảnh không hợp lệ (jpeg, png, jpg, gif)',
            'avatar.max' => 'Kích thước ảnh không vượt quá 2MB',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        
        $employee = Auth::guard('employee')->user();
        
        try {
            // Xử lý upload file
            if ($request->hasFile('avatar')) {
                // Thay đổi thư mục lưu từ 'employees' thành 'nhanvien'
                if (!Storage::disk('public')->exists('nhanvien')) {
                    Storage::disk('public')->makeDirectory('nhanvien', 0755, true);
                }
                
                // Xóa ảnh cũ nếu có
                if ($employee->HinhAnh && Storage::disk('public')->exists('nhanvien/' . $employee->HinhAnh)) {
                    Storage::disk('public')->delete('nhanvien/' . $employee->HinhAnh);
                }
                
                // Lưu ảnh mới
                $filename = $employee->MaNV . '_' . time() . '.' . $request->avatar->extension();
                $path = $request->file('avatar')->storeAs('nhanvien', $filename, 'public');
                
                // Cập nhật database bằng cách truy vấn trực tiếp
                $updated = Employee::where('MaNV', $employee->MaNV)->update(['HinhAnh' => $filename]);
                
                if (!$updated) {
                    return response()->json(['error' => 'Không thể cập nhật dữ liệu'], 500);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật ảnh đại diện thành công!',
                    'avatar' => asset('storage/nhanvien/' . $filename)
                ]);
            }
            
            return response()->json(['error' => 'Không tìm thấy file ảnh'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}