<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    use HasFactory;

    protected $table = 'nv_pc';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'MaNV',
        'IDPC',
        'Ngay',
        'NoiDung',
        'SoTien',
    ];

    protected $casts = [
        'Ngay' => 'datetime',
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
     * Relationship với phụ cấp
     */
    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'IDPC', 'IDPC');
    }

    /**
     * Tính tổng phụ cấp theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @return float
     */
    public static function getTotalAllowanceByEmployee($employeeId, $month, $year)
    {
        return self::where('MaNV', $employeeId)
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->sum('SoTien');
    }

    /**
     * Lấy danh sách phụ cấp theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @return Collection
     */
    public static function getAllowancesByEmployee($employeeId, $month, $year)
    {
        return self::with('allowance')
            ->where('MaNV', $employeeId)
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month)
            ->orderBy('Ngay', 'asc')
            ->get();
    }
}