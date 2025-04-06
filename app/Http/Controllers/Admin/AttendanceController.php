<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use App\Models\WorkType;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class AttendanceController extends Controller
{
   
    public function index(Request $request)
    {
        if ($request->has('clear_cache')) {
            \Illuminate\Support\Facades\Cache::flush();
            \Illuminate\Support\Facades\Session::flush();
            return redirect()->route('admin.attendance.index');
        }
        
        $date = $request->input('date') ? Carbon::createFromFormat('d/m/Y', $request->input('date')) : Carbon::today();
        $departmentId = $request->input('department_id');
        $status = $request->input('status');
        $search = $request->input('search');
        $showDeleted = $request->boolean('show_deleted', false);
        
        \Illuminate\Support\Facades\Log::info('Attendance Index');
        \Illuminate\Support\Facades\Log::info('Show deleted: ' . ($showDeleted ? 'true' : 'false'));
        \Illuminate\Support\Facades\Log::info('Date: ' . $date->format('Y-m-d'));
        
        $attendancesQuery = Attendance::with(['employee.department', 'employee.position', 'workType'])
            ->where('Nam', $date->year)
            ->where('Thang', $date->month)
            ->where('Ngay', $date->day);
        
        if ($departmentId) {
            $attendancesQuery->whereHas('employee', function($query) use ($departmentId) {
                $query->where('IDPB', $departmentId);
            });
        }
        
        if ($status && $status != 'all') {
            $attendancesQuery->where('TrangThai', $status);
        }
        
        if ($search) {
            $attendancesQuery->whereHas('employee', function($query) use ($search) {
                $query->where('TenNV', 'like', '%' . $search . '%')
                      ->orWhere('MaNV', 'like', '%' . $search . '%');
            });
        }
        
        if (!$showDeleted) {
            $attendancesQuery->where('TrangThai', '!=', Attendance::STATUS_DELETED);
        }
        
        \Illuminate\Support\Facades\Log::info('SQL: ' . $attendancesQuery->toSql());
        
        $attendances = $attendancesQuery->paginate(15);
        
        \Illuminate\Support\Facades\Log::info('Count: ' . $attendances->total());
        
        $employees = Employee::where('TrangThai', Employee::STATUS_ACTIVE)->get();
        $departments = Department::where('TrangThai', Department::STATUS_ACTIVE)->get();
        $workTypes = WorkType::all();
        
        $statistics = $this->getStatistics($date, $departmentId);
        
        return view('admin.attendance', compact(
            'attendances',
            'employees',
            'departments',
            'workTypes',
            'date',
            'statistics'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:nhanvien,MaNV',
            'date' => 'required|date_format:d/m/Y',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|integer',
            'work_type_id' => 'required|exists:loaicong,IDLC',
            'note' => 'nullable|string'
        ]);
        
        $date = Carbon::createFromFormat('d/m/Y', $validated['date']);
        
        $existingRecord = Attendance::where('Nam', $date->year)
            ->where('Thang', $date->month)
            ->where('Ngay', $date->day)
            ->where('MaNV', $validated['employee_id'])
            ->where('TrangThai', '!=', Attendance::STATUS_DELETED)
            ->first();
            
        if ($existingRecord) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã tồn tại bản ghi chấm công cho nhân viên này vào ngày ' . $date->format('d/m/Y'));
        }
        
        try {
            // Mặc định giờ vào/ra
            $giovao = 0;
            $phutvao = 0;
            $giora = 0;
            $phutra = 0;
            
            // Nếu có giờ vào
            if (!empty($validated['check_in'])) {
                $timeIn = Carbon::createFromFormat('H:i', $validated['check_in']);
                $giovao = (int) $timeIn->format('H');
                $phutvao = (int) $timeIn->format('i');
            }
            
            // Nếu có giờ ra
            if (!empty($validated['check_out'])) {
                $timeOut = Carbon::createFromFormat('H:i', $validated['check_out']);
                $giora = (int) $timeOut->format('H');
                $phutra = (int) $timeOut->format('i');
            }
            
            // Tạo bản ghi chấm công mới
            Attendance::create([
                'Nam' => $date->year,
                'Thang' => $date->month,
                'Ngay' => $date->day,
                'Giovao' => $giovao,
                'Phutvao' => $phutvao,
                'GioRa' => $giora,
                'PhutRa' => $phutra,
                'MaNV' => $validated['employee_id'],
                'IDLC' => $validated['work_type_id'],
                'TrangThai' => $validated['status'],
                'TrangThaiChamCong' => Attendance::MANUAL_ATTENDANCE,
                'GhiChu' => $validated['note'] ?? '' // Đảm bảo ghi chú không null
            ]);
            
            return redirect()->route('admin.attendance.index', ['date' => $date->format('d/m/Y')])
                ->with('success', 'Đã thêm bản ghi chấm công thành công!');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Store attendance error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi khi thêm bản ghi: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết bản ghi chấm công
     */
    public function show(Attendance $attendance)
    {
        // Load relationships
        $attendance->load(['employee.department', 'employee.position', 'workType']);
        
        // Chuẩn bị dữ liệu để trả về
        $data = [
            'attendance' => $attendance,
            'employee' => $attendance->employee,
            'department' => $attendance->employee ? $attendance->employee->department : null,
            'position' => $attendance->employee ? $attendance->employee->position : null,
            'workType' => $attendance->workType,
            'formattedDate' => $attendance->formattedDate,
            'checkInTime' => $attendance->checkInTime,
            'checkOutTime' => $attendance->checkOutTime,
            'totalWorkTime' => $attendance->totalWorkTime,
            'statusText' => $attendance->statusText,
            'statusClass' => $attendance->statusClass,
            'GhiChu' => $attendance->GhiChu
        ];
        
        return response()->json($data);
    }

    /**
     * Cập nhật bản ghi chấm công
     */
    public function update(Request $request, Attendance $attendance)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|integer',
            'work_type_id' => 'required|exists:loaicong,IDLC',
            'note' => 'nullable|string'
        ]);
        
        try {
            // Mặc định giờ vào/ra
            $giovao = 0;
            $phutvao = 0;
            $giora = 0;
            $phutra = 0;
            
            // Nếu có giờ vào
            if (!empty($validated['check_in'])) {
                $timeIn = Carbon::createFromFormat('H:i', $validated['check_in']);
                $giovao = (int) $timeIn->format('H');
                $phutvao = (int) $timeIn->format('i');
            }
            
            // Nếu có giờ ra
            if (!empty($validated['check_out'])) {
                $timeOut = Carbon::createFromFormat('H:i', $validated['check_out']);
                $giora = (int) $timeOut->format('H');
                $phutra = (int) $timeOut->format('i');
            }
            
            // Cập nhật bản ghi
            $attendance->update([
                'Giovao' => $giovao,
                'Phutvao' => $phutvao,
                'GioRa' => $giora,
                'PhutRa' => $phutra,
                'IDLC' => $validated['work_type_id'],
                'TrangThai' => $validated['status'],
                'TrangThaiChamCong' => Attendance::MANUAL_ATTENDANCE,
                'GhiChu' => $validated['note'] ?? '' // Đảm bảo ghi chú không null
            ]);
            
            return redirect()->route('admin.attendance.index', [
                'date' => sprintf("%02d/%02d/%04d", $attendance->Ngay, $attendance->Thang, $attendance->Nam)
            ])->with('success', 'Đã cập nhật bản ghi chấm công thành công!');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update attendance error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
        }
    }

    /**
     * Xóa mềm bản ghi chấm công
     */
    public function destroy(Attendance $attendance)
    {
        try {
            // Lưu ngày của bản ghi để redirect sau khi xóa
            $date = sprintf("%02d/%02d/%04d", $attendance->Ngay, $attendance->Thang, $attendance->Nam);
            
            // Xóa mềm (cập nhật trạng thái)
            $attendance->update(['TrangThai' => Attendance::STATUS_DELETED]);
            
            // Ghi log để debug
            \Illuminate\Support\Facades\Log::info('Deleted attendance ID: ' . $attendance->MABC);
            \Illuminate\Support\Facades\Log::info('New status: ' . $attendance->TrangThai);
            
            return redirect()->route('admin.attendance.index', ['date' => $date])
                ->with('success', 'Đã xóa bản ghi chấm công thành công!');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Delete attendance error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Lỗi khi xóa bản ghi: ' . $e->getMessage());
        }
    }

    /**
     * Khôi phục bản ghi đã xóa mềm
     */
    public function restore(Request $request, $id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            
            // Kiểm tra xem bản ghi có bị xóa mềm không
            if ($attendance->TrangThai == Attendance::STATUS_DELETED) {
                // Khôi phục về trạng thái đi làm đúng giờ
                $attendance->update(['TrangThai' => Attendance::ATTENDANCE_ONTIME]);
                
                // Lấy ngày của bản ghi để redirect
                $date = sprintf("%02d/%02d/%04d", $attendance->Ngay, $attendance->Thang, $attendance->Nam);
                
                return redirect()->route('admin.attendance.index', ['date' => $date, 'show_deleted' => true])
                    ->with('success', 'Đã khôi phục bản ghi chấm công thành công!');
            }
            
            return redirect()->back()->with('error', 'Bản ghi này không trong trạng thái đã xóa');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Restore attendance error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi khôi phục bản ghi: ' . $e->getMessage());
        }
    }

    /**
     * Tạo chấm công tự động
     */
    public function generate(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'date' => 'required|date_format:d/m/Y',
            'department_id' => 'nullable|exists:phongban,IDPB',
            'status' => 'required|integer',
            'note' => 'nullable|string'
        ]);
        
        // Parse ngày
        $date = Carbon::createFromFormat('d/m/Y', $validated['date']);
        
        // Lấy danh sách nhân viên
        $employeesQuery = Employee::where('TrangThai', Employee::STATUS_ACTIVE);
        
        // Lọc theo phòng ban nếu có
        if (!empty($validated['department_id'])) {
            $employeesQuery->where('IDPB', $validated['department_id']);
        }
        
        $employees = $employeesQuery->get();
        
        // Biến đếm
        $created = 0;
        $skipped = 0;
        
        foreach ($employees as $employee) {
            // Kiểm tra xem đã có bản ghi nào cho nhân viên này trong ngày chưa
            $existingRecord = Attendance::where('Nam', $date->year)
                ->where('Thang', $date->month)
                ->where('Ngay', $date->day)
                ->where('MaNV', $employee->MaNV)
                ->where('TrangThai', '!=', Attendance::STATUS_DELETED)
                ->first();
                
            if ($existingRecord) {
                $skipped++;
                continue;
            }
            
            // Mặc định giờ vào/ra
            $giovao = 0;
            $phutvao = 0;
            $giora = 0;
            $phutra = 0;
            
            // Nếu trạng thái không phải vắng mặt, nghỉ phép, công tác thì thêm giờ vào/ra mặc định
            if ($validated['status'] == Attendance::ATTENDANCE_ONTIME) {
                $giovao = 8;
                $phutvao = 0;
                $giora = 17;
                $phutra = 0;
            }
            
            // Tạo bản ghi chấm công mới
            Attendance::create([
                'Nam' => $date->year,
                'Thang' => $date->month,
                'Ngay' => $date->day,
                'Giovao' => $giovao,
                'Phutvao' => $phutvao,
                'GioRa' => $giora,
                'PhutRa' => $phutra,
                'MaNV' => $employee->MaNV,
                'IDLC' => 1, // Mặc định loại công đầu tiên
                'TrangThai' => $validated['status'],
                'TrangThaiChamCong' => Attendance::AUTO_ATTENDANCE,
                'GhiChu' => $validated['note'] ?? '' // Đảm bảo ghi chú không null
            ]);
            
            $created++;
        }
        
        $message = "Đã tạo $created bản ghi chấm công mới.";
        if ($skipped > 0) {
            $message .= " Bỏ qua $skipped nhân viên đã có dữ liệu chấm công.";
        }
        
        return redirect()->route('admin.attendance.index', ['date' => $validated['date']])
            ->with('success', $message);
    }

    /**
     * Xuất dữ liệu chấm công
     */
    public function export(Request $request)
    {
        // TODO: Implement export method
        return redirect()->back()->with('info', 'Chức năng xuất dữ liệu đang được phát triển.');
    }

    /**
     * Lấy thống kê cho trang chủ
     */
    private function getStatistics($date, $departmentId = null)
    {
        // Đếm tổng số nhân viên
        $totalEmployees = Employee::where('TrangThai', Employee::STATUS_ACTIVE)
            ->when($departmentId, function($query) use ($departmentId) {
                return $query->where('IDPB', $departmentId);
            })
            ->count();
        
        // Số nhân viên đã chấm công (đúng giờ, đi muộn, về sớm, làm thêm giờ, nghỉ phép, công tác)
        $attendedCount = Attendance::byDate($date)
            ->whereIn('TrangThai', [
                Attendance::ATTENDANCE_ONTIME, 
                Attendance::ATTENDANCE_LATE,
                Attendance::ATTENDANCE_EARLY_LEAVE,
                Attendance::ATTENDANCE_OVERTIME,
                Attendance::ATTENDANCE_LEAVE,
                Attendance::ATTENDANCE_BUSINESS
            ])
            ->when($departmentId, function($query) use ($departmentId) {
                return $query->whereHas('employee', function($q) use ($departmentId) {
                    $q->where('IDPB', $departmentId);
                });
            })
            ->count();
        
        // Số nhân viên đi muộn hoặc về sớm
        $lateEarlyCount = Attendance::byDate($date)
            ->whereIn('TrangThai', [
                Attendance::ATTENDANCE_LATE,
                Attendance::ATTENDANCE_EARLY_LEAVE
            ])
            ->when($departmentId, function($query) use ($departmentId) {
                return $query->whereHas('employee', function($q) use ($departmentId) {
                    $q->where('IDPB', $departmentId);
                });
            })
            ->count();
        
        // Số nhân viên vắng mặt
        $absentCount = Attendance::byDate($date)
            ->where('TrangThai', Attendance::ATTENDANCE_ABSENT)
            ->when($departmentId, function($query) use ($departmentId) {
                return $query->whereHas('employee', function($q) use ($departmentId) {
                    $q->where('IDPB', $departmentId);
                });
            })
            ->count();
        
        // Vì có thể chưa có dữ liệu trong DB, chỉ tạo giá trị mẫu để test
        if ($totalEmployees == 0) {
            $totalEmployees = 20;
            $attendedCount = 17;
            $lateEarlyCount = 2;
            $absentCount = 3;
        }
        
        // Tỷ lệ đi làm
        $attendanceRate = $totalEmployees > 0 ? round(($attendedCount / $totalEmployees) * 100) : 0;
        
        // Thống kê theo phòng ban
        $departmentStats = [];
        $departments = Department::where('TrangThai', Department::STATUS_ACTIVE)->get();
        
        foreach ($departments as $dept) {
            $departmentStats[] = [
                'name' => $dept->TenPB,
                'totalEmployees' => rand(5, 15),  // Giá trị mẫu
                'attendedCount' => rand(4, 13),   // Giá trị mẫu
                'lateEarlyCount' => rand(0, 3),   // Giá trị mẫu
                'absentCount' => rand(0, 2),      // Giá trị mẫu
                'attendanceRate' => rand(70, 100) // Giá trị mẫu
            ];
        }
        
        // Tạo dữ liệu xu hướng
        $trendData = $this->getTrendData($date);
        
        return [
            'totalEmployees' => $totalEmployees,
            'attendedCount' => $attendedCount,
            'lateEarlyCount' => $lateEarlyCount,
            'absentCount' => $absentCount,
            'attendanceRate' => $attendanceRate,
            'departmentStats' => $departmentStats,
            'trendData' => $trendData
        ];
    }
    
    /**
     * Lấy dữ liệu xu hướng chấm công trong 30 ngày gần đây
     */
    private function getTrendData($currentDate)
    {
        $trendData = [];
        
        // Tạo dữ liệu giả cho 30 ngày
        for ($i = 29; $i >= 0; $i--) {
            $date = (clone $currentDate)->subDays($i);
            $trendData[] = [
                'date' => $date->format('d/m'),
                'rate' => mt_rand(70, 100), // Tỷ lệ chấm công random từ 70-100%
                'lateEarlyCount' => mt_rand(0, 5) // Số người đi muộn/về sớm random từ 0-5
            ];
        }
        
        return $trendData;
    }
}