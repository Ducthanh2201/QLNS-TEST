<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdvanceSalary extends Model
{
    use HasFactory;

    protected $table = 'ungluong';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'Nam',
        'Thang',
        'Ngay',
        'SoTien',
        'MaNV',
    ];

    protected $casts = [
        'SoTien' => 'float',
    ];

    /**
     * Relationship với nhân viên
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MaNV', 'MaNV');
    }

    /**
     * Lấy ngày tháng dạng Carbon
     */
    public function getDateAttribute()
    {
        return Carbon::createFromDate($this->Nam, $this->Thang, $this->Ngay);
    }

    /**
     * Tính tổng số tiền ứng lương theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @return float
     */
    public static function getTotalAdvanceByEmployee($employeeId, $month, $year)
    {
        return self::where('MaNV', $employeeId)
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->sum('SoTien');
    }

    /**
     * Lấy danh sách ứng lương theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @return Collection
     */
    public static function getAdvancesByEmployee($employeeId, $month, $year)
    {
        return self::where('MaNV', $employeeId)
            ->where('Thang', $month)
            ->where('Nam', $year)
            ->orderBy('Ngay', 'asc')
            ->get();
    }
}