<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;

    protected $table = 'phucap';
    protected $primaryKey = 'IDPC';
    public $timestamps = false;

    protected $fillable = [
        'TenPC',
        'SoTien',
    ];

    /**
     * Relationship với bảng nv_pc (nhân viên - phụ cấp)
     */
    public function employeeAllowances()
    {
        return $this->hasMany(EmployeeAllowance::class, 'IDPC', 'IDPC');
    }

    /**
     * Lấy danh sách nhân viên có phụ cấp này
     */
    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'nv_pc',
            'IDPC',
            'MaNV',
            'IDPC',
            'MaNV'
        )->withPivot('Ngay', 'NoiDung', 'SoTien');
    }
}