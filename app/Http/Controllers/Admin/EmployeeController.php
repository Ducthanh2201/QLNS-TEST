<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Thêm dòng này để import DB Facade
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter by position if provided
        $query = Employee::with('position');
        
        // Chỉ lấy nhân viên chưa bị xóa mềm
        $query->where('TrangThai', '!=', Employee::STATUS_DELETED);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('TrangThai', $request->status);
        }
        
        if ($request->has('position') && !empty($request->position)) {
            $query->where('IDCV', $request->position);
        }
        
        // Search by name, email or phone if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('TenNV', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('DienThoai', 'like', "%{$search}%");
            });
        }
        
        // Paginate results
        $employees = $query->paginate(10);
        
        return view('admin.employee', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = Position::where('TrangThai', Position::STATUS_ACTIVE)->get();
        return view('admin.employee_create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'TenNV' => 'required|string|max:50',
            'email' => 'required|email|unique:nhanvien,email|max:100',
            'Password' => 'required|string|min:6|max:50',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date',
            'DienThoai' => 'required|string|max:10',
            'CCCD' => 'required|string|max:22|unique:nhanvien,CCCD',
            'DiaChi' => 'required|string|max:100',
            'IDCV' => 'required|exists:chucvu,IDCV',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Hash mật khẩu nếu không được hash trước đó
        if (!empty($validated['Password']) && substr($validated['Password'], 0, 4) !== '$2y$') {
            $validated['Password'] = Hash::make($validated['Password']);
        }

        // Xử lý hình ảnh - Upload trực tiếp vào thư mục public/nhanvien
        if ($request->hasFile('HinhAnh')) {
            // Lấy thông tin file
            $file = $request->file('HinhAnh');
            
            // Định nghĩa đường dẫn lưu file
            $uploadPath = public_path('nhanvien');
            
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Tạo tên file mới để tránh trùng lặp
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Upload file trực tiếp vào thư mục public/nhanvien
            $file->move($uploadPath, $fileName);
            
            // Cập nhật tên file vào mảng dữ liệu
            $validated['HinhAnh'] = $fileName;
        }

        // Thêm trạng thái mặc định là active
        $validated['TrangThai'] = Employee::STATUS_ACTIVE;

        // Tạo nhân viên mới
        try {
            Employee::create($validated);
            return redirect()->route('admin.employees.index')
                ->with('success', 'Thêm nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi tạo nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('position');
        return view('admin.employee_show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $positions = Position::all();
        return view('admin.employee_edit', compact('employee', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Validate input
        $validated = $request->validate([
            'TenNV' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('nhanvien', 'email')->ignore($employee->MaNV, 'MaNV'),
            ],
            'Password' => 'nullable|string|min:6|max:50',
            'GioiTinh' => 'required|boolean',
            'NgaySinh' => 'required|date',
            'DienThoai' => 'required|string|max:10',
            'CCCD' => [
                'required',
                'string',
                'max:22',
                Rule::unique('nhanvien', 'CCCD')->ignore($employee->MaNV, 'MaNV'),
            ],
            'DiaChi' => 'required|string|max:100',
            'IDCV' => 'required|exists:chucvu,IDCV',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'TrangThai' => 'nullable|in:0,1,2'
        ]);

        // Xử lý hình ảnh - Upload trực tiếp vào thư mục public/nhanvien
        if ($request->hasFile('HinhAnh')) {
            // Lấy thông tin file
            $file = $request->file('HinhAnh');
            
            // Định nghĩa đường dẫn lưu file
            $uploadPath = public_path('nhanvien');
            
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Xóa hình ảnh cũ nếu có
            if ($employee->HinhAnh && file_exists(public_path('nhanvien/' . $employee->HinhAnh))) {
                unlink(public_path('nhanvien/' . $employee->HinhAnh));
            }
            
            // Tạo tên file mới để tránh trùng lặp
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Upload file trực tiếp vào thư mục public/nhanvien
            $file->move($uploadPath, $fileName);
            
            // Cập nhật tên file vào mảng dữ liệu
            $validated['HinhAnh'] = $fileName;
        } else if ($request->has('remove_image') && $request->remove_image) {
            // Xóa hình ảnh hiện tại nếu đã chọn xóa
            if ($employee->HinhAnh && file_exists(public_path('nhanvien/' . $employee->HinhAnh))) {
                unlink(public_path('nhanvien/' . $employee->HinhAnh));
            }
            $validated['HinhAnh'] = null;
        }

        // Xử lý mật khẩu
        if (empty($validated['Password'])) {
            unset($validated['Password']);
        } else if (substr($validated['Password'], 0, 4) !== '$2y$') {
            $validated['Password'] = Hash::make($validated['Password']);
        }

        try {
            // Sử dụng DB trực tiếp để đảm bảo cập nhật
            $updateData = $validated;
            
            // Cập nhật thông tin nhân viên
            DB::table('nhanvien')
                ->where('MaNV', $employee->MaNV)
                ->update($updateData);
                
            // Refresh model để lấy dữ liệu mới
            $employee = Employee::find($employee->MaNV);
            
            return redirect()->route('admin.employees.index')
                ->with('success', 'Cập nhật nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi cập nhật: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Xóa mềm (cập nhật trạng thái thành đã xóa)
        $employee->update(['TrangThai' => Employee::STATUS_DELETED]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }
    
    /**
     * Toggle employee status (active/inactive)
     */
    public function toggleStatus(Employee $employee)
    {
        // Chuyển đổi trạng thái giữa active và inactive
        $newStatus = $employee->TrangThai == Employee::STATUS_ACTIVE 
                   ? Employee::STATUS_INACTIVE : Employee::STATUS_ACTIVE;
        
        $employee->update(['TrangThai' => $newStatus]);
        
        $statusText = $newStatus == Employee::STATUS_ACTIVE ? 'kích hoạt' : 'vô hiệu hóa';
        
        return redirect()->route('admin.employees.index')
            ->with('success', "Đã {$statusText} tài khoản nhân viên thành công!");
    }
}