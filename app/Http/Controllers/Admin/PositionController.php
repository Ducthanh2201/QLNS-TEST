<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        $positions = Position::when($search, function ($query) use ($search) {
                $query->where('TenCV', 'like', "%{$search}%");
            })
            ->orderBy('IDCV', 'asc')
            ->paginate(10);
            
        // Đếm số nhân viên cho mỗi chức vụ
        foreach ($positions as $position) {
            $position->employee_count = $position->employees()->count();
        }
        
        // Dữ liệu cho biểu đồ
        $chart_data = DB::table('chucvu')
            ->join('nhanvien', 'chucvu.IDCV', '=', 'nhanvien.IDCV')
            ->select('chucvu.TenCV', DB::raw('count(*) as employee_count'))
            ->where('nhanvien.TrangThai', '!=', 2) // Không tính nhân viên đã xóa
            ->groupBy('chucvu.IDCV', 'chucvu.TenCV')
            ->get();

        // Nếu không có dữ liệu, truyền một mảng trống
        if ($chart_data->isEmpty()) {
            $chart_data = collect([]);
        }
            
        return view('admin.positions', compact('positions', 'search', 'chart_data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'TenCV' => 'required|string|max:50|unique:chucvu',
            'TrangThai' => 'required|in:0,1',
        ]);

        Position::create([
            'TenCV' => $request->TenCV,
            'TrangThai' => $request->TrangThai,
        ]);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Thêm chức vụ thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        $position->employee_count = $position->employees()->count();
        
        return response()->json([
            'position' => $position,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'TenCV' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($position) {
                    $exists = Position::where('TenCV', $value)
                        ->where('IDCV', '!=', $position->IDCV)
                        ->exists();
                    if ($exists) {
                        $fail('Tên chức vụ đã tồn tại.');
                    }
                },
            ],
            'TrangThai' => 'required|in:0,1',
        ]);

        $position->update([
            'TenCV' => $request->TenCV,
            'TrangThai' => $request->TrangThai,
        ]);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Cập nhật chức vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        // Kiểm tra xem có nhân viên nào đang sử dụng chức vụ này không
        $employeeCount = $position->employees()->count();
        
        if ($employeeCount > 0) {
            return redirect()->route('admin.positions.index')
                ->with('error', 'Không thể xóa chức vụ này vì có ' . $employeeCount . ' nhân viên đang sử dụng!');
        }
        
        $position->delete();
        
        return redirect()->route('admin.positions.index')
            ->with('success', 'Xóa chức vụ thành công!');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Position $position)
    {
        $position->update([
            'TrangThai' => $position->TrangThai == Position::STATUS_ACTIVE 
                ? Position::STATUS_INACTIVE 
                : Position::STATUS_ACTIVE
        ]);
        
        return redirect()->route('admin.positions.index')
            ->with('success', 'Đã thay đổi trạng thái chức vụ thành công!');
    }
}