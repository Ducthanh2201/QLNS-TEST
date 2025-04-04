<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeKeeping extends Model
{
    use HasFactory;

    protected $table = 'bangcong';
    protected $primaryKey = 'MABC';
    public $timestamps = false;

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
     * Tính giờ làm việc
     */
    public function getWorkHoursAttribute()
    {
        $startTime = $this->Giovao * 60 + $this->Phutvao;
        $endTime = $this->GioRa * 60 + $this->PhutRa;

        if ($endTime < $startTime) {
            // Trường hợp ca đêm, qua ngày mới
            $endTime += 24 * 60;
        }

        $minutes = $endTime - $startTime;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return [
            'hours' => $hours,
            'minutes' => $mins,
            'total_minutes' => $minutes
        ];
    }

    /**
     * Lấy ngày giờ vào dạng Carbon
     */
    public function getTimeInAttribute()
    {
        return \Carbon\Carbon::createFromDate($this->Nam, $this->Thang, $this->Ngay)
            ->setHour($this->Giovao)
            ->setMinute($this->Phutvao);
    }

    /**
     * Lấy ngày giờ ra dạng Carbon
     */
    public function getTimeOutAttribute()
    {
        return \Carbon\Carbon::createFromDate($this->Nam, $this->Thang, $this->Ngay)
            ->setHour($this->GioRa)
            ->setMinute($this->PhutRa);
    }
}