<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'phongban';
    protected $primaryKey = 'IDPB';
    public $timestamps = false;

    // Định nghĩa các trạng thái
    const STATUS_ACTIVE = 1;    // Đang hoạt động
    const STATUS_INACTIVE = 0;  // Không hoạt động

    protected $fillable = [
        'TenPB',
        'MoTa',
        'TrangThai'
    ];

    protected $casts = [
        'TrangThai' => 'integer',
    ];

    /**
     * Relationship với nhân viên
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'IDPB', 'IDPB');
    }

    /**
     * Scope để lấy chỉ các phòng ban đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('TrangThai', self::STATUS_ACTIVE);
    }
}