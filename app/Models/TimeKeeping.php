<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeKeeping extends Model
{
    use HasFactory;

    protected $table = 'bangcong';
    protected $primaryKey = 'MABC';
    public $timestamps = false;

    // Định nghĩa các trạng thái bản ghi
    const STATUS_ACTIVE = 1;      // Đang hoạt động
    const STATUS_INACTIVE = 0;    // Không hoạt động
    const STATUS_DELETED = 9;     // Đã xóa mềm - Thay đổi giá trị này từ 2 thành 9

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
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Trước khi lưu bản ghi, kiểm tra và điều chỉnh trạng thái nếu cần
        static::saving(function ($timeKeeping) {
            // Tính thời gian làm việc
            $startTime = $timeKeeping->Giovao * 60 + $timeKeeping->Phutvao;
            $endTime = $timeKeeping->GioRa * 60 + $timeKeeping->PhutRa;
            
            if ($endTime < $startTime) {
                $endTime += 24 * 60;
            }
            
            $minutes = $endTime - $startTime;
            
            // Nếu có giờ làm việc nhưng trạng thái là vắng mặt, tự động điều chỉnh
            if ($minutes > 0 && $timeKeeping->TrangThai == self::ATTENDANCE_ABSENT) {
                if ($minutes > 9 * 60) {
                    $timeKeeping->TrangThai = self::ATTENDANCE_OVERTIME;
                } else if ($timeKeeping->Giovao > 8 || ($timeKeeping->Giovao == 8 && $timeKeeping->Phutvao > 15)) {
                    $timeKeeping->TrangThai = self::ATTENDANCE_LATE;
                } else if (($timeKeeping->GioRa < 17 || ($timeKeeping->GioRa == 17 && $timeKeeping->PhutRa < 0)) && 
                          ($timeKeeping->GioRa > 9 || ($timeKeeping->GioRa == 9 && $timeKeeping->PhutRa > 0))) {
                    $timeKeeping->TrangThai = self::ATTENDANCE_EARLY_LEAVE;
                } else {
                    $timeKeeping->TrangThai = self::ATTENDANCE_ONTIME;
                }
            }
        });
    }

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
     * Scope để lấy chỉ các bản ghi đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('TrangThai', self::STATUS_ACTIVE);
    }

    /**
     * Lọc bản ghi không bị xóa mềm
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('TrangThai', '!=', self::STATUS_DELETED);
    }

    /**
     * Lọc bản ghi đã bị xóa mềm
     */
    public function scopeDeleted($query)
    {
        return $query->where('TrangThai', self::STATUS_DELETED);
    }

    /**
     * Tính giờ làm việc
     */
    public function getWorkHoursAttribute()
    {
        // Đảm bảo các trường không null
        $giovao = $this->Giovao ?: 0;
        $phutvao = $this->Phutvao ?: 0;
        $giora = $this->GioRa ?: 0;
        $phutra = $this->PhutRa ?: 0;
        
        $startTime = $giovao * 60 + $phutvao;
        $endTime = $giora * 60 + $phutra;

        // Nếu giờ ra nhỏ hơn giờ vào, cộng thêm 24h (ca đêm)
        if ($endTime < $startTime) {
            $endTime += 24 * 60;
        }

        $minutes = $endTime - $startTime;
        
        // Trường hợp có lỗi dữ liệu, không cho phép số âm
        if ($minutes < 0) {
            $minutes = 0;
        }
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return [
            'hours' => $hours,
            'minutes' => $mins,
            'total_minutes' => $minutes,
            'formatted' => $hours . 'h' . ($mins > 0 ? ' ' . $mins . 'm' : '')
        ];
    }

    /**
     * Lấy ngày giờ vào dạng Carbon
     */
    public function getTimeInAttribute()
    {
        if ($this->Giovao === null || $this->Phutvao === null) {
            return null;
        }
        
        return Carbon::createFromDate($this->Nam, $this->Thang, $this->Ngay)
            ->setHour($this->Giovao)
            ->setMinute($this->Phutvao);
    }

    /**
     * Lấy ngày giờ ra dạng Carbon
     */
    public function getTimeOutAttribute()
    {
        if ($this->GioRa === null || $this->PhutRa === null) {
            return null;
        }
        
        $date = Carbon::createFromDate($this->Nam, $this->Thang, $this->Ngay)
            ->setHour($this->GioRa)
            ->setMinute($this->PhutRa);
            
        // Nếu giờ ra nhỏ hơn giờ vào, đó là ca đêm - tăng thêm 1 ngày
        if ($this->GioRa < $this->Giovao) {
            $date->addDay();
        }
        
        return $date;
    }

    /**
     * Lấy trạng thái chấm công dạng text
     */
    public function getAttendanceStatusTextAttribute()
    {
        if ($this->TrangThai == self::STATUS_DELETED) {
            return 'Đã xóa';
        }
        
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
            default:
                return 'Không xác định';
        }
    }

    /**
     * Lấy class CSS cho trạng thái chấm công
     */
    public function getAttendanceStatusClassAttribute()
    {
        if ($this->TrangThai == self::STATUS_DELETED) {
            return 'bg-secondary';
        }
        
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
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Xuất giờ vào theo định dạng HH:MM
     */
    public function getFormattedTimeInAttribute()
    {
        if ($this->Giovao === null || $this->Phutvao === null) {
            return '-';
        }
        
        return sprintf('%02d:%02d', $this->Giovao, $this->Phutvao);
    }

    /**
     * Xuất giờ ra theo định dạng HH:MM
     */
    public function getFormattedTimeOutAttribute()
    {
        if ($this->GioRa === null || $this->PhutRa === null) {
            return '-';
        }
        
        return sprintf('%02d:%02d', $this->GioRa, $this->PhutRa);
    }

    /**
     * Lấy ngày theo định dạng dd/mm/yyyy
     */
    public function getFormattedDateAttribute()
    {
        return sprintf('%02d/%02d/%04d', $this->Ngay, $this->Thang, $this->Nam);
    }

    /**
     * Accessor để tự động tính trạng thái dựa trên giờ vào/ra
     */
    public function getCalculatedStatusAttribute()
    {
        // Nếu không có giờ vào/ra
        if (($this->Giovao == 0 && $this->Phutvao == 0) && 
            ($this->GioRa == 0 && $this->PhutRa == 0)) {
            return self::ATTENDANCE_ABSENT;
        }
        
        $startTime = $this->Giovao * 60 + $this->Phutvao;
        $endTime = $this->GioRa * 60 + $this->PhutRa;
        
        if ($endTime < $startTime) {
            $endTime += 24 * 60;
        }
        
        $minutes = $endTime - $startTime;
        
        // Trường hợp có thời gian làm việc nhưng trạng thái là vắng mặt
        if ($minutes > 0 && $this->TrangThai == self::ATTENDANCE_ABSENT) {
            if ($minutes > 9 * 60) {
                return self::ATTENDANCE_OVERTIME;
            } else if ($this->Giovao > 8 || ($this->Giovao == 8 && $this->Phutvao > 15)) {
                return self::ATTENDANCE_LATE;
            } else if (($this->GioRa < 17 || ($this->GioRa == 17 && $this->PhutRa < 0)) && 
                      ($this->GioRa > 9 || ($this->GioRa == 9 && $this->PhutRa > 0))) {
                return self::ATTENDANCE_EARLY_LEAVE;
            } else {
                return self::ATTENDANCE_ONTIME;
            }
        }
        
        return $this->TrangThai;
    }

    // Thêm method để kiểm tra xem bản ghi có bị xóa mềm không
    public function isDeleted()
    {
        return $this->TrangThai == self::STATUS_DELETED;
    }
}