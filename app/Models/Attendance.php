<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    // Sử dụng lại bảng bangcong nhưng với định nghĩa riêng cho chức năng chấm công
    protected $table = 'bangcong';
    protected $primaryKey = 'MABC';
    public $timestamps = false;

    // Định nghĩa các trạng thái bản ghi
    const STATUS_ACTIVE = 1;      // Đang hoạt động
    const STATUS_INACTIVE = 0;    // Không hoạt động
    const STATUS_DELETED = 9;     // Đã xóa mềm - sử dụng 9 để tránh xung đột

    // Định nghĩa các trạng thái chấm công
    const ATTENDANCE_ONTIME = 1;      // Đúng giờ
    const ATTENDANCE_LATE = 2;        // Đi muộn
    const ATTENDANCE_EARLY_LEAVE = 3; // Về sớm
    const ATTENDANCE_OVERTIME = 4;    // Làm thêm giờ
    const ATTENDANCE_ABSENT = 0;      // Vắng mặt
    const ATTENDANCE_LEAVE = 5;       // Nghỉ phép
    const ATTENDANCE_BUSINESS = 6;    // Công tác

    // Trạng thái chấm công (thủ công/tự động)
    const MANUAL_ATTENDANCE = 0;     // Chấm công thủ công
    const AUTO_ATTENDANCE = 1;       // Chấm công tự động
    
    protected $fillable = [
        'Nam',
        'Thang',
        'Ngay',
        'Giovao',
        'Phutvao',
        'GioRa',
        'PhutRa',
        'MaNV',
        'IDLC',
        'TrangThai',
        'TrangThaiChamCong',
        'GhiChu'
    ];

    protected $casts = [
        'TrangThai' => 'integer',
        'TrangThaiChamCong' => 'integer',
        'Nam' => 'integer',
        'Thang' => 'integer',
        'Ngay' => 'integer',
        'Giovao' => 'integer',
        'Phutvao' => 'integer',
        'GioRa' => 'integer',
        'PhutRa' => 'integer',
    ];

    /**
     * Relationship với nhân viên
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MaNV', 'MaNV');
    }

    /**
     * Relationship với loại công
     */
    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'IDLC', 'IDLC');
    }

    /**
     * Scope để lọc theo ngày cụ thể
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('Nam', $date->year)
                    ->where('Thang', $date->month)
                    ->where('Ngay', $date->day);
    }

    /**
     * Scope để lấy chỉ các bản ghi đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('TrangThai', '!=', self::STATUS_DELETED);
    }

    /**
     * Kiểm tra xem bản ghi có bị xóa mềm không
     */
    public function isDeleted()
    {
        return $this->TrangThai == self::STATUS_DELETED;
    }

    /**
     * Lấy ngày giờ vào định dạng
     */
    public function getCheckInTimeAttribute()
    {
        if ($this->Giovao == 0 && $this->Phutvao == 0) {
            return '--';
        }
        return sprintf("%02d:%02d", $this->Giovao, $this->Phutvao);
    }

    /**
     * Lấy ngày giờ ra định dạng
     */
    public function getCheckOutTimeAttribute()
    {
        if ($this->GioRa == 0 && $this->PhutRa == 0) {
            return '--';
        }
        return sprintf("%02d:%02d", $this->GioRa, $this->PhutRa);
    }

    /**
     * Lấy ngày định dạng
     */
    public function getFormattedDateAttribute()
    {
        return sprintf("%02d/%02d/%04d", $this->Ngay, $this->Thang, $this->Nam);
    }

    /**
     * Lấy trạng thái hiển thị
     */
    public function getStatusTextAttribute()
    {
        switch($this->TrangThai) {
            case self::ATTENDANCE_ONTIME:
                return 'Đúng giờ';
            case self::ATTENDANCE_LATE:
                return 'Đi muộn';
            case self::ATTENDANCE_EARLY_LEAVE:
                return 'Về sớm';
            case self::ATTENDANCE_OVERTIME:
                return 'Làm thêm giờ';
            case self::ATTENDANCE_ABSENT:
                return 'Vắng mặt';
            case self::ATTENDANCE_LEAVE:
                return 'Nghỉ phép';
            case self::ATTENDANCE_BUSINESS:
                return 'Công tác';
            case self::STATUS_DELETED:
                return 'Đã xóa';
            default:
                return 'Không xác định';
        }
    }

    /**
     * Lấy class CSS cho trạng thái
     */
    public function getStatusClassAttribute()
    {
        switch($this->TrangThai) {
            case self::ATTENDANCE_ONTIME:
                return 'bg-success';
            case self::ATTENDANCE_LATE:
            case self::ATTENDANCE_EARLY_LEAVE:
                return 'bg-warning';
            case self::ATTENDANCE_OVERTIME:
                return 'bg-info';
            case self::ATTENDANCE_ABSENT:
                return 'bg-danger';
            case self::ATTENDANCE_LEAVE:
                return 'bg-primary';
            case self::ATTENDANCE_BUSINESS:
                return 'bg-secondary';
            case self::STATUS_DELETED:
                return 'bg-secondary';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Lấy tổng thời gian làm việc
     */
    public function getTotalWorkTimeAttribute()
    {
        if ($this->Giovao == 0 && $this->Phutvao == 0 && $this->GioRa == 0 && $this->PhutRa == 0) {
            return '--';
        }
        
        $timeIn = $this->Giovao * 60 + $this->Phutvao;
        $timeOut = $this->GioRa * 60 + $this->PhutRa;
        
        // Xử lý trường hợp check-out là ngày hôm sau
        if ($timeOut < $timeIn) {
            $timeOut += 24 * 60;
        }
        
        $totalMinutes = $timeOut - $timeIn;
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        
        return $hours . ' giờ ' . $minutes . ' phút';
    }
}