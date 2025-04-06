<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'nhanvien';
    protected $primaryKey = 'MaNV';
    protected $keyType = 'int';
    public $incrementing = true;
    
    // Tắt timestamps
    public $timestamps = false;
    
    // Định nghĩa các trạng thái
    const STATUS_ACTIVE = 1;      // Đang làm việc
    const STATUS_INACTIVE = 0;    // Nghỉ làm
    const STATUS_DELETED = 2;     // Đã xóa (xóa mềm)
    
    protected $fillable = [
        'MaNV',
        'TenNV',
        'IDCV',
        'IDPB',
        'Password',
        'NgaySinh',
        'GioiTinh',
        'DiaChi',
        'DienThoai',
        'email',
        'CCCD',
        'TrangThai',
        'HinhAnh'
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'NgaySinh' => 'date',
        'GioiTinh' => 'boolean'
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Relationship với chức vụ
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'IDCV', 'IDCV');
    }
    
    /**
     * Relationship với bảng phòng ban
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'IDPB', 'IDPB');
    }

    /**
     * Relationship với bảng công/chấm công
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'MaNV', 'MaNV');
    }

    /**
     * Scope để chỉ lấy nhân viên còn hoạt động (không bị xóa mềm)
     */
    public function scopeActive($query)
    {
        return $query->where('TrangThai', '!=', self::STATUS_DELETED);
    }
    
    /**
     * Kiểm tra nhân viên có đang hoạt động
     */
    public function isActive()
    {
        return $this->TrangThai == self::STATUS_ACTIVE;
    }

    /**
     * Relationship với bảng nv_pc (nhân viên - phụ cấp)
     */
    public function employeeAllowances()
    {
        return $this->hasMany(EmployeeAllowance::class, 'MaNV', 'MaNV');
    }

    /**
     * Lấy danh sách phụ cấp của nhân viên
     */
    public function allowances()
    {
        return $this->belongsToMany(
            Allowance::class,
            'nv_pc',
            'MaNV',
            'IDPC',
            'MaNV',
            'IDPC'
        )->withPivot('ID', 'Ngay', 'NoiDung', 'SoTien');
    }

    /**
     * Lấy tổng phụ cấp theo tháng, năm
     */
    public function getTotalAllowance($month, $year)
    {
        return $this->employeeAllowances()
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->sum('SoTien');
    }

    /**
     * Lấy danh sách phụ cấp theo tháng, năm
     */
    public function getAllowancesByMonth($month, $year)
    {
        return $this->employeeAllowances()
            ->with('allowance')
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->orderBy('Ngay', 'asc')
            ->get();
    }

    /**
     * Relationship với bảng tăng ca
     */
    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'MaNV', 'MaNV');
    }

    /**
     * Lấy tổng số giờ tăng ca theo tháng, năm
     */
    public function getTotalOvertime($month, $year)
    {
        $overtimes = $this->overtimes()
            ->with('shiftType')
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->get();
            
        $totalHours = 0;
        $totalActualHours = 0;
        
        foreach ($overtimes as $overtime) {
            $totalHours += $overtime->SoGio;
            $totalActualHours += $overtime->SoGio * ($overtime->shiftType ? $overtime->shiftType->HeSo : 1);
        }
        
        return [
            'total_hours' => $totalHours,
            'total_actual_hours' => $totalActualHours
        ];
    }

    /**
     * Lấy danh sách tăng ca theo tháng, năm
     */
    public function getOvertimesByMonth($month, $year)
    {
        return $this->overtimes()
            ->with('shiftType')
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->orderBy('Ngay', 'asc')
            ->get();
    }

    /**
     * Relationship với bảng ứng lương
     */
    public function advanceSalaries()
    {
        return $this->hasMany(AdvanceSalary::class, 'MaNV', 'MaNV');
    }

    /**
     * Relationship với bảng khen thưởng/kỷ luật
     */
    public function rewardPenalties()
    {
        return $this->hasMany(RewardPenalty::class, 'MaNV', 'MaNV');
    }

    /**
     * Lấy danh sách ứng lương theo tháng, năm
     */
    public function getAdvanceSalariesByMonth($month, $year)
    {
        return $this->advanceSalaries()
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->orderBy('Ngay', 'asc')
            ->get();
    }

    /**
     * Lấy tổng số tiền ứng lương theo tháng, năm
     */
    public function getTotalAdvanceSalary($month, $year)
    {
        return $this->advanceSalaries()
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->sum('SoTien');
    }

    /**
     * Lấy danh sách khen thưởng theo tháng, năm
     */
    public function getRewardsByMonth($month, $year)
    {
        return $this->rewardPenalties()
            ->rewards()
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->orderBy('Ngay', 'asc')
            ->get();
    }

    /**
     * Lấy danh sách kỷ luật theo tháng, năm
     */
    public function getPenaltiesByMonth($month, $year)
    {
        return $this->rewardPenalties()
            ->penalties()
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->orderBy('Ngay', 'asc')
            ->get();
    }

    /**
     * Đếm số lần khen thưởng theo tháng, năm
     */
    public function countRewards($month, $year)
    {
        return $this->rewardPenalties()
            ->rewards()
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->count();
    }

    /**
     * Đếm số lần kỷ luật theo tháng, năm
     */
    public function countPenalties($month, $year)
    {
        return $this->rewardPenalties()
            ->penalties()
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->count();
    }
}