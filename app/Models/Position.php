<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'chucvu';
    protected $primaryKey = 'IDCV';
    public $timestamps = false;

    // Định nghĩa các trạng thái
    const STATUS_ACTIVE = 1;    // Đang hoạt động
    const STATUS_INACTIVE = 0;  // Không hoạt động

    protected $fillable = [
        'TenCV',
        'TrangThai'
    ];

    /**
     * Relationship với nhân viên
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'IDCV', 'IDCV');
    }

    /**
     * Scope để lấy chỉ chức vụ đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('TrangThai', self::STATUS_ACTIVE);
    }

    /**
     * Scope để lấy chỉ chức vụ không hoạt động
     */
    public function scopeInactive($query)
    {
        return $query->where('TrangThai', self::STATUS_INACTIVE);
    }

    /**
     * Đếm số nhân viên có chức vụ này
     */
    public function getEmployeeCountAttribute()
    {
        return $this->employees()->count();
    }
}