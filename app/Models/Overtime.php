<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Overtime extends Model
{
    use HasFactory;

    protected $table = 'tangca';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'Nam',
        'Thang',
        'Ngay',
        'SoGio',
        'MaNV',
        'IDLoaiCa',
    ];

    protected $casts = [
        'SoGio' => 'float',
    ];

    /**
     * Relationship với nhân viên
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MaNV', 'MaNV');
    }

    /**
     * Relationship với loại ca
     */
    public function shiftType()
    {
        return $this->belongsTo(ShiftType::class, 'IDLoaiCa', 'IDLoaiCa');
    }

    /**
     * Lấy ngày tháng dạng Carbon
     */
    public function getDateAttribute()
    {
        return Carbon::createFromDate($this->Nam, $this->Thang, $this->Ngay);
    }

    /**
     * Tính số giờ thực tế sau khi nhân với hệ số
     */
    public function getActualHoursAttribute()
    {
        return $this->SoGio * ($this->shiftType ? $this->shiftType->HeSo : 1);
    }

    /**
     * Tính tổng số giờ tăng ca theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @return array Mảng chứa số giờ thực tế và số giờ sau khi tính hệ số
     */
    public static function getTotalOvertimeByEmployee($employeeId, $month, $year)
    {
        $overtimes = self::with('shiftType')
            ->where('MaNV', $employeeId)
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->get();
            
        $totalHours = 0;
        $totalActualHours = 0;
        
        foreach ($overtimes as $overtime) {
            $totalHours += $overtime->SoGio;
            $totalActualHours += $overtime->getActualHoursAttribute();
        }
        
        return [
            'total_hours' => $totalHours,
            'total_actual_hours' => $totalActualHours
        ];
    }

    /**
     * Lấy danh sách tăng ca theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @return Collection
     */
    public static function getOvertimesByEmployee($employeeId, $month, $year)
    {
        return self::with('shiftType')
            ->where('MaNV', $employeeId)
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->orderBy('Ngay', 'asc')
            ->get();
    }
}