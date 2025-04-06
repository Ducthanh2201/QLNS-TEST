<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeKeeping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Hiển thị lịch sử chấm công của nhân viên
     */
    public function index(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        // Lấy lịch sử chấm công trong tháng
        $attendances = TimeKeeping::where('MaNV', $employee->MaNV)
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->orderBy('Ngay', 'desc')
            ->paginate(15);
        
        // Tính số ngày đi làm, đi muộn, nghỉ phép
        $workdays = $attendances->where('TrangThai', TimeKeeping::ATTENDANCE_ONTIME)->count();
        $latedays = $attendances->where('TrangThai', TimeKeeping::ATTENDANCE_LATE)->count();
        $earlydays = $attendances->where('TrangThai', TimeKeeping::ATTENDANCE_EARLY_LEAVE)->count();
        $leavedays = $attendances->where('TrangThai', TimeKeeping::ATTENDANCE_LEAVE)->count();
        $absentdays = $attendances->where('TrangThai', TimeKeeping::ATTENDANCE_ABSENT)->count();
        
        return view('employees.attendance', compact(
            'attendances', 
            'month', 
            'year', 
            'workdays', 
            'latedays', 
            'earlydays', 
            'leavedays',
            'absentdays'
        ));
    }

    /**
     * Xử lý check-in
     */
    public function checkIn(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        // Đặt múi giờ Việt Nam (Asia/Ho_Chi_Minh)
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        
        // Kiểm tra xem đã chấm công hôm nay chưa
        $attendance = TimeKeeping::where('MaNV', $employee->MaNV)
            ->where('Ngay', $now->day)
            ->where('Thang', $now->month)
            ->where('Nam', $now->year)
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->first();
        
        if (!$attendance) {
            // Chưa có bản ghi chấm công, tạo mới
            $attendance = new TimeKeeping();
            $attendance->MaNV = $employee->MaNV;
            $attendance->Ngay = $now->day;
            $attendance->Thang = $now->month;
            $attendance->Nam = $now->year;
            $attendance->IDLC = 1; // ID loại công mặc định
            $attendance->TrangThaiChamCong = TimeKeeping::MANUAL_ATTENDANCE; // Chấm công thủ công
            
            // Thêm giá trị mặc định là 0 cho GioRa và PhutRa
            $attendance->GioRa = 0;
            $attendance->PhutRa = 0;
            
            // Thêm giá trị mặc định cho GhiChu
            $attendance->GhiChu = '';
        } elseif ($attendance->Giovao && $attendance->Phutvao) {
            // Đã check-in rồi
            return redirect()->back()->with('error', 'Bạn đã check-in hôm nay rồi!');
        }
        
        // Cập nhật giờ vào
        $attendance->Giovao = $now->hour;
        $attendance->Phutvao = $now->minute;
        
        // Tự động xác định trạng thái chấm công
        if ($now->hour > 8 || ($now->hour == 8 && $now->minute > 15)) {
            $attendance->TrangThai = TimeKeeping::ATTENDANCE_LATE; // Đi muộn
        } else {
            $attendance->TrangThai = TimeKeeping::ATTENDANCE_ONTIME; // Đúng giờ
        }
        
        $attendance->save();
        
        return redirect()->back()->with('success', 'Check-in thành công lúc ' . $now->format('H:i'));
    }

    /**
     * Xử lý check-out
     */
    public function checkOut(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        // Đặt múi giờ Việt Nam (Asia/Ho_Chi_Minh)
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        
        // Kiểm tra xem đã chấm công hôm nay chưa
        $attendance = TimeKeeping::where('MaNV', $employee->MaNV)
            ->where('Ngay', $now->day)
            ->where('Thang', $now->month)
            ->where('Nam', $now->year)
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->first();
        
        if (!$attendance) {
            // Chưa có bản ghi chấm công
            return redirect()->back()->with('error', 'Bạn chưa check-in hôm nay!');
        }
        
        if (!$attendance->Giovao && !$attendance->Phutvao) {
            // Chưa check-in
            return redirect()->back()->with('error', 'Bạn phải check-in trước khi check-out!');
        }
        
        if ($attendance->GioRa && $attendance->PhutRa) {
            // Đã check-out rồi
            return redirect()->back()->with('error', 'Bạn đã check-out hôm nay rồi!');
        }
        
        // Cập nhật giờ ra
        $attendance->GioRa = $now->hour;
        $attendance->PhutRa = $now->minute;
        
        // Nếu trước đó là đi muộn, giữ nguyên trạng thái
        if ($attendance->TrangThai != TimeKeeping::ATTENDANCE_LATE) {
            // Tự động xác định trạng thái chấm công
            if ($now->hour < 17) {
                $attendance->TrangThai = TimeKeeping::ATTENDANCE_EARLY_LEAVE; // Về sớm
            } elseif ($now->hour > 18) {
                $attendance->TrangThai = TimeKeeping::ATTENDANCE_OVERTIME; // Làm thêm giờ
            } else {
                $attendance->TrangThai = TimeKeeping::ATTENDANCE_ONTIME; // Đúng giờ
            }
        }
        
        $attendance->save();
        
        return redirect()->back()->with('success', 'Check-out thành công lúc ' . $now->format('H:i'));
    }

    /**
     * Lấy thông tin chấm công hôm nay
     */
    public function getTodayAttendance()
    {
        $employee = Auth::guard('employee')->user();
        // Đặt múi giờ Việt Nam (Asia/Ho_Chi_Minh)
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        
        $attendance = TimeKeeping::where('MaNV', $employee->MaNV)
            ->where('Ngay', $now->day)
            ->where('Thang', $now->month)
            ->where('Nam', $now->year)
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->first();
            
        return $attendance;
    }
}