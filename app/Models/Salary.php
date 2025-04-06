<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; // Thêm import này
use Illuminate\Support\Facades\DB;

class Salary extends Model
{
    use HasFactory;

    protected $table = 'luong';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // Định nghĩa các trạng thái
    const STATUS_PENDING = 0;      // Chưa thanh toán
    const STATUS_PAID = 1;         // Đã thanh toán
    const STATUS_CANCELLED = 2;    // Đã hủy

    protected $fillable = [
        'MaNV',
        'Thang',
        'Nam',
        'LuongCoBan',
        'TongNgayCong',
        'TongTien',
        'TrangThai',
        'NgayTinh',
        'GhiChu'
    ];

    protected $casts = [
        'Thang' => 'integer',
        'Nam' => 'integer',
        'LuongCoBan' => 'float',
        'TongNgayCong' => 'float',
        'TongTien' => 'float',
        'TrangThai' => 'integer',
        'NgayTinh' => 'datetime'
    ];

    /**
     * Relationship với nhân viên
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MaNV', 'MaNV');
    }

    /**
     * Scope để lọc theo tháng, năm
     */
    public function scopeByMonth($query, $month, $year)
    {
        return $query->where('Thang', $month)
                    ->where('Nam', $year);
    }

    /**
     * Scope để lọc theo trạng thái
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('TrangThai', $status);
    }

    /**
     * Lấy tên trạng thái hiển thị
     */
    public function getStatusNameAttribute()
    {
        switch ($this->TrangThai) {
            case self::STATUS_PENDING:
                return 'Chưa thanh toán';
            case self::STATUS_PAID:
                return 'Đã thanh toán';
            case self::STATUS_CANCELLED:
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }

    /**
     * Lấy class màu cho trạng thái
     */
    public function getStatusClassAttribute()
    {
        switch ($this->TrangThai) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_PAID:
                return 'success';
            case self::STATUS_CANCELLED:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Lấy tổng phụ cấp của nhân viên trong tháng
     */
    public function getTotalAllowanceAttribute()
    {
        return EmployeeAllowance::getTotalAllowanceByEmployee($this->MaNV, $this->Thang, $this->Nam);
    }

    /**
     * Lấy tổng thưởng của nhân viên trong tháng
     */
    public function getTotalRewardAttribute()
    {
        try {
            // Nếu bảng kt/kl không có cột SoTien, trả về 0
            if (!Schema::hasColumn('kt/kl', 'SoTien')) {
                return 0;
            }
            
            return RewardPenalty::where('MaNV', $this->MaNV)
                ->whereYear('Ngay', $this->Nam)
                ->whereMonth('Ngay', $this->Thang)
                ->where('LoaiKT/KL', RewardPenalty::TYPE_REWARD)
                ->sum('SoTien');
        } catch (\Exception $e) {
            // Bỏ qua lỗi, không ghi log
            return 0;
        }
    }

    /**
     * Lấy tổng phạt của nhân viên trong tháng
     */
    public function getTotalPenaltyAttribute()
    {
        try {
            // Nếu bảng kt/kl không có cột SoTien, trả về 0
            if (!Schema::hasColumn('kt/kl', 'SoTien')) {
                return 0;
            }
            
            return RewardPenalty::where('MaNV', $this->MaNV)
                ->whereYear('Ngay', $this->Nam)
                ->whereMonth('Ngay', $this->Thang)
                ->where('LoaiKT/KL', RewardPenalty::TYPE_PENALTY)
                ->sum('SoTien');
        } catch (\Exception $e) {
            // Bỏ qua lỗi, không ghi log
            return 0;
        }
    }

    /**
     * Lấy tổng tăng ca của nhân viên trong tháng
     */
    public function getTotalOvertimeAttribute()
    {
        $overtime = Overtime::getTotalOvertimeByEmployee($this->MaNV, $this->Thang, $this->Nam);
        return $overtime['total_hours'] ?? 0;
    }

    /**
     * Lấy tổng số tiền tạm ứng của nhân viên trong tháng
     */
    public function getTotalAdvanceAttribute()
    {
        return AdvanceSalary::getTotalAdvanceByEmployee($this->MaNV, $this->Thang, $this->Nam);
    }

    /**
     * Lấy tổng số ngày công của nhân viên trong tháng
     */
    public function getWorkingDaysAttribute()
    {
        return Attendance::where('MaNV', $this->MaNV)
            ->where('Nam', $this->Nam)
            ->where('Thang', $this->Thang)
            ->whereIn('TrangThai', [
                Attendance::ATTENDANCE_ONTIME,
                Attendance::ATTENDANCE_LATE,
                Attendance::ATTENDANCE_EARLY_LEAVE,
                Attendance::ATTENDANCE_OVERTIME,
                Attendance::ATTENDANCE_BUSINESS
            ])
            ->count();
    }

    /**
     * Tính lương cho nhân viên
     */
    public static function calculateSalary($employeeId, $month, $year)
    {
        // Lấy thông tin nhân viên
        $employee = Employee::findOrFail($employeeId);
        
        // Tính tổng số ngày công
        $workingDays = Attendance::where('MaNV', $employeeId)
            ->where('Nam', $year)
            ->where('Thang', $month)
            ->whereIn('TrangThai', [
                Attendance::ATTENDANCE_ONTIME,
                Attendance::ATTENDANCE_LATE,
                Attendance::ATTENDANCE_EARLY_LEAVE,
                Attendance::ATTENDANCE_OVERTIME,
                Attendance::ATTENDANCE_BUSINESS
            ])
            ->count();
        
        // Tính lương cơ bản (giả sử 22 ngày làm việc trong tháng)
        $luongCoBan = $employee->LuongCoBan ?? 0;
        
        // Tính lương theo ngày công
        $luongTheoNgayCong = ($luongCoBan / 22) * $workingDays;
        
        // Tính tổng phụ cấp
        try {
            $totalAllowance = EmployeeAllowance::getTotalAllowanceByEmployee($employeeId, $month, $year);
        } catch (\Exception $e) {
            $totalAllowance = 0;
        }
        
        // Tính tổng thưởng - xử lý trường hợp không có cột SoTien
        try {
            if (Schema::hasColumn('kt/kl', 'SoTien')) {
                $totalReward = RewardPenalty::where('MaNV', $employeeId)
                    ->whereYear('Ngay', $year)
                    ->whereMonth('Ngay', $month)
                    ->where('LoaiKT/KL', RewardPenalty::TYPE_REWARD)
                    ->sum('SoTien');
            } else {
                $totalReward = 0;
            }
        } catch (\Exception $e) {
            $totalReward = 0;
        }
        
        // Tính tổng phạt - xử lý trường hợp không có cột SoTien
        try {
            if (Schema::hasColumn('kt/kl', 'SoTien')) {
                $totalPenalty = RewardPenalty::where('MaNV', $employeeId)
                    ->whereYear('Ngay', $year)
                    ->whereMonth('Ngay', $month)
                    ->where('LoaiKT/KL', RewardPenalty::TYPE_PENALTY)
                    ->sum('SoTien');
            } else {
                $totalPenalty = 0;
            }
        } catch (\Exception $e) {
            $totalPenalty = 0;
        }
        
        // Tính tổng tiền tăng ca
        try {
            $overtime = Overtime::getTotalOvertimeByEmployee($employeeId, $month, $year);
            $overtimePay = ($luongCoBan / (22 * 8)) * ($overtime['total_actual_hours'] ?? 0);
        } catch (\Exception $e) {
            $overtimePay = 0;
        }
        
        // Tính tổng tạm ứng
        try {
            $totalAdvance = AdvanceSalary::getTotalAdvanceByEmployee($employeeId, $month, $year);
        } catch (\Exception $e) {
            $totalAdvance = 0;
        }
        
        // Tính tổng tiền lương
        $tongTien = $luongTheoNgayCong + $totalAllowance + $totalReward - $totalPenalty + $overtimePay - $totalAdvance;
        
        // Kiểm tra nếu đã có bản ghi lương này
        $salary = self::where('MaNV', $employeeId)
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->first();
        
        if (!$salary) {
            $salary = new self();
            $salary->MaNV = $employeeId;
            $salary->Thang = $month;
            $salary->Nam = $year;
        }
        
        $salary->LuongCoBan = $luongCoBan;
        $salary->TongNgayCong = $workingDays;
        $salary->TongTien = $tongTien;
        $salary->TrangThai = self::STATUS_PENDING;
        $salary->NgayTinh = now();
        $salary->save();
        
        return $salary;
    }

    /**
     * Tính lương hàng loạt cho nhiều nhân viên
     */
    public static function batchCalculateSalaries($month, $year, $departmentId = null, $employeeIds = [])
    {
        // Query nhân viên
        $employeeQuery = Employee::where('TrangThai', Employee::STATUS_ACTIVE);
        
        // Lọc theo phòng ban nếu có
        if ($departmentId) {
            $employeeQuery->where('IDPB', $departmentId);
        }
        
        // Lọc theo danh sách nhân viên cụ thể nếu có
        if (!empty($employeeIds)) {
            $employeeQuery->whereIn('MaNV', $employeeIds);
        }
        
        // Lấy danh sách nhân viên
        $employees = $employeeQuery->get();
        
        $results = [];
        
        foreach ($employees as $employee) {
            try {
                $salary = self::calculateSalary($employee->MaNV, $month, $year);
                $results[] = [
                    'employee' => $employee->TenNV,
                    'status' => 'success',
                    'message' => 'Tính lương thành công'
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'employee' => $employee->TenNV,
                    'status' => 'error',
                    'message' => 'Lỗi: ' . $e->getMessage()
                ];
            }
        }
        
        return $results;
    }

    /**
     * Lấy chi tiết lương đầy đủ
     */
    public function getFullSalaryDetails()
    {
        // Sử dụng try-catch để xử lý lỗi tiềm ẩn
        try {
            // Lấy thông tin từ các bảng liên quan
            $workingDays = $this->TongNgayCong; // Sử dụng giá trị từ DB
            
            try {
                $totalAllowance = $this->getTotalAllowanceAttribute();
            } catch (\Exception $e) {
                $totalAllowance = 0;
            }
            
            try {
                $totalReward = $this->getTotalRewardAttribute();
            } catch (\Exception $e) {
                $totalReward = 0;
            }
            
            try {
                $totalPenalty = $this->getTotalPenaltyAttribute();
            } catch (\Exception $e) {
                $totalPenalty = 0;
            }
            
            try {
                $overtimeHours = $this->getTotalOvertimeAttribute();
            } catch (\Exception $e) {
                $overtimeHours = 0;
            }
            
            try {
                $totalAdvance = $this->getTotalAdvanceAttribute();
            } catch (\Exception $e) {
                $totalAdvance = 0;
            }
            
            // Tính lương theo công thức 
            $luongTheoNgayCong = ($this->LuongCoBan / 22) * $workingDays;
            $overtimePay = ($this->LuongCoBan / (22 * 8)) * $overtimeHours;
            
            // Trả về đầy đủ thông tin
            return [
                'employee' => $this->employee,
                'basic_info' => [
                    'month' => $this->Thang,
                    'year' => $this->Nam,
                    'status' => $this->getStatusNameAttribute(),
                    'calculate_date' => $this->NgayTinh,
                    'note' => $this->GhiChu
                ],
                'salary_components' => [
                    'base_salary' => $this->LuongCoBan,
                    'working_days' => $workingDays,
                    'salary_by_days' => $luongTheoNgayCong,
                    'allowance' => $totalAllowance,
                    'reward' => $totalReward,
                    'penalty' => $totalPenalty,
                    'overtime_hours' => $overtimeHours,
                    'overtime_pay' => $overtimePay,
                    'advance' => $totalAdvance
                ],
                'total' => $this->TongTien
            ];
        } catch (\Exception $e) {
            // Trả về cấu trúc tối thiểu nếu có lỗi, không ghi log
            return [
                'employee' => $this->employee,
                'basic_info' => [
                    'month' => $this->Thang,
                    'year' => $this->Nam,
                    'status' => $this->getStatusNameAttribute(),
                    'calculate_date' => $this->NgayTinh,
                    'note' => $this->GhiChu
                ],
                'salary_components' => [
                    'base_salary' => $this->LuongCoBan,
                    'working_days' => $this->TongNgayCong,
                    'salary_by_days' => ($this->LuongCoBan / 22) * $this->TongNgayCong,
                    'allowance' => 0,
                    'reward' => 0,
                    'penalty' => 0,
                    'overtime_hours' => 0,
                    'overtime_pay' => 0,
                    'advance' => 0
                ],
                'total' => $this->TongTien
            ];
        }
    }
}