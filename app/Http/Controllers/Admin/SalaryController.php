<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\TimeKeeping;
use App\Models\Overtime;
use App\Models\EmployeeAllowance;
use App\Models\AdvanceSalary;
use App\Models\RewardPenalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class SalaryController extends Controller
{
    /**
     * Hiển thị danh sách lương
     */
    public function index(Request $request)
    {
        // Lấy tháng/năm được chọn hoặc hiện tại
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $departmentId = $request->input('department_id');
        $status = $request->input('status');
        $search = $request->input('search');
        
        // Query danh sách lương
        $salariesQuery = Salary::with(['employee.department', 'employee.position'])
            ->where('Thang', $month)
            ->where('Nam', $year);
        
        // Lọc theo phòng ban nếu có
        if ($departmentId) {
            $salariesQuery->whereHas('employee', function($query) use ($departmentId) {
                $query->where('IDPB', $departmentId);
            });
        }
        
        // Lọc theo trạng thái nếu có
        if ($status && $status != 'all') {
            $salariesQuery->where('TrangThai', $status);
        }
        
        // Tìm kiếm theo tên nhân viên hoặc mã nhân viên
        if ($search) {
            $salariesQuery->whereHas('employee', function($query) use ($search) {
                $query->where('TenNV', 'like', '%' . $search . '%')
                      ->orWhere('MaNV', 'like', '%' . $search . '%');
            });
        }
        
        $salaries = $salariesQuery->paginate(15);
        
        // Lấy danh sách phòng ban
        $departments = Department::where('TrangThai', Department::STATUS_ACTIVE)->get();
        
        // Tính tổng số tiền lương
        $totalSalary = $salariesQuery->sum('TongTien');
        $pendingSalary = $salariesQuery->where('TrangThai', Salary::STATUS_PENDING)->sum('TongTien');
        $paidSalary = $salariesQuery->where('TrangThai', Salary::STATUS_PAID)->sum('TongTien');
        
        // Tạo dữ liệu thống kê
        $statistics = $this->generateStatistics($month, $year, $departmentId);
        
        return view('admin.salary', compact(
            'salaries',
            'departments',
            'month',
            'year',
            'totalSalary',
            'pendingSalary',
            'paidSalary',
            'statistics'
        ));
    }

    /**
     * Hiển thị chi tiết lương
     */
    public function show(Salary $salary)
    {
        $details = $salary->getFullSalaryDetails();
        return response()->json($details);
    }

    /**
     * Tính lương cho một nhân viên
     */
    public function calculate(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'employee_id' => 'required|exists:nhanvien,MaNV',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'note' => 'nullable|string|max:255'
        ]);
        
        try {
            $salary = Salary::calculateSalary(
                $validated['employee_id'], 
                $validated['month'], 
                $validated['year']
            );
            
            if (!empty($validated['note'])) {
                $salary->GhiChu = $validated['note'];
                $salary->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Đã tính lương thành công!',
                'salary' => $salary
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính lương: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tính lương cho nhiều nhân viên
     */
    public function batchCalculate(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'department_id' => 'nullable|exists:phongban,IDPB',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:nhanvien,MaNV'
        ]);
        
        // Query nhân viên
        $employeeQuery = Employee::where('TrangThai', Employee::STATUS_ACTIVE);
        
        // Lọc theo phòng ban nếu có
        if (!empty($validated['department_id'])) {
            $employeeQuery->where('IDPB', $validated['department_id']);
        }
        
        // Lọc theo danh sách nhân viên nếu có
        if (!empty($validated['employee_ids'])) {
            $employeeQuery->whereIn('MaNV', $validated['employee_ids']);
        }
        
        $employees = $employeeQuery->get();
        $results = [];
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($employees as $employee) {
            try {
                $salary = Salary::calculateSalary(
                    $employee->MaNV,
                    $validated['month'],
                    $validated['year']
                );
                
                $results[] = [
                    'employee' => $employee->TenNV,
                    'status' => 'success',
                    'salary' => $salary->TongTien
                ];
                
                $successCount++;
            } catch (Exception $e) {
                $results[] = [
                    'employee' => $employee->TenNV,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
                
                $errorCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Đã tính lương cho $successCount nhân viên thành công. $errorCount lỗi.",
            'results' => $results
        ]);
    }

    /**
     * Cập nhật trạng thái thanh toán lương
     */
    public function updateStatus(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:' . implode(',', [
                Salary::STATUS_PENDING,
                Salary::STATUS_PAID,
                Salary::STATUS_CANCELLED
            ]),
            'note' => 'nullable|string|max:255'
        ]);
        
        try {
            $salary->TrangThai = $validated['status'];
            
            if (!empty($validated['note'])) {
                $salary->GhiChu = $validated['note'];
            }
            
            $salary->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật trạng thái thanh toán lương thành công!',
                'salary' => $salary
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo dữ liệu thống kê lương
     */
    private function generateStatistics($month, $year, $departmentId = null)
    {
        // Thống kê theo phòng ban
        $departments = Department::where('TrangThai', Department::STATUS_ACTIVE)->get();
        $departmentStats = [];
        
        foreach ($departments as $dept) {
            $salaries = Salary::whereHas('employee', function($query) use ($dept) {
                    $query->where('IDPB', $dept->IDPB);
                })
                ->where('Thang', $month)
                ->where('Nam', $year)
                ->get();
                
            $avgSalary = $salaries->count() > 0 ? $salaries->avg('TongTien') : 0;
            $totalSalary = $salaries->sum('TongTien');
            $employeeCount = $salaries->count();
            
            $departmentStats[] = [
                'name' => $dept->TenPB,
                'employee_count' => $employeeCount,
                'avg_salary' => round($avgSalary),
                'total_salary' => $totalSalary
            ];
        }
        
        // Thống kê theo tháng (6 tháng gần nhất)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $currentMonth = Carbon::createFromDate($year, $month, 1)->subMonths($i);
            $m = $currentMonth->month;
            $y = $currentMonth->year;
            
            $monthSalaries = Salary::where('Thang', $m)
                ->where('Nam', $y)
                ->when($departmentId, function($query) use ($departmentId) {
                    return $query->whereHas('employee', function($q) use ($departmentId) {
                        $q->where('IDPB', $departmentId);
                    });
                })
                ->get();
                
            $monthlyStats[] = [
                'month' => $currentMonth->format('m/Y'),
                'total' => $monthSalaries->sum('TongTien'),
                'count' => $monthSalaries->count()
            ];
        }
        
        return [
            'department_stats' => $departmentStats,
            'monthly_stats' => $monthlyStats
        ];
    }

    /**
     * Cập nhật thông tin lương
     */
    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'luong_co_ban' => 'required|numeric|min:0',
            'tong_ngay_cong' => 'required|numeric|min:0|max:31',
            'trang_thai' => 'required|integer|in:' . implode(',', [
                Salary::STATUS_PENDING,
                Salary::STATUS_PAID, 
                Salary::STATUS_CANCELLED
            ]),
            'ghi_chu' => 'nullable|string|max:255'
        ]);
        
        try {
            // Cập nhật thông tin lương
            $salary->LuongCoBan = $validated['luong_co_ban'];
            $salary->TongNgayCong = $validated['tong_ngay_cong'];
            $salary->TrangThai = $validated['trang_thai'];
            
            if (isset($validated['ghi_chu'])) {
                $salary->GhiChu = $validated['ghi_chu'];
            }
            
            // Tính lại lương theo ngày công
            $luongTheoNgayCong = ($salary->LuongCoBan / 22) * $salary->TongNgayCong;
            
            // Lấy các thành phần lương khác
            $details = $salary->getFullSalaryDetails();
            $components = $details['salary_components'];
            
            // Tính tổng lương mới
            $tongTien = $luongTheoNgayCong + $components['allowance'] + 
                       $components['reward'] - $components['penalty'] + 
                       $components['overtime_pay'] - $components['advance'];
            
            $salary->TongTien = $tongTien;
            $salary->save();
            
            return redirect()->route('admin.salary.index')
                ->with('success', 'Cập nhật lương thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật lương: ' . $e->getMessage());
        }
    }

    /**
     * Xuất phiếu lương
     */
    public function exportPayslip(Salary $salary)
    {
        $details = $salary->getFullSalaryDetails();
        return view('admin.payslip_print', compact('details'));
    }
}