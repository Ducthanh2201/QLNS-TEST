<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;

    protected $table = 'loaicong';
    protected $primaryKey = 'IDLC';
    public $timestamps = false;

    // Định nghĩa các trạng thái
    const STATUS_ACTIVE = 1;    // Đang hoạt động
    const STATUS_INACTIVE = 0;  // Không hoạt động

    protected $fillable = [
        'TenLC',
        'HeSo',
        'TrangThai'
    ];

    protected $casts = [
        'HeSo' => 'float',
        'TrangThai' => 'integer',
    ];

    /**
     * Relationship với bảng công
     */
    public function timeKeepings()
    {
        return $this->hasMany(TimeKeeping::class, 'IDLC', 'IDLC');
    }

    /**
     * Scope để lấy chỉ các loại công đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('TrangThai', self::STATUS_ACTIVE);
    }
}