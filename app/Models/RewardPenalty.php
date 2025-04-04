<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RewardPenalty extends Model
{
    use HasFactory;

    protected $table = 'ktkl';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // Định nghĩa các loại khen thưởng/kỷ luật
    const TYPE_REWARD = 1;  // Khen thưởng
    const TYPE_PENALTY = 0; // Kỷ luật

    protected $fillable = [
        'SoKTKL',
        'NoiDung',
        'Ngay',
        'MaNV',
        'LoaiKTKL',
    ];

    protected $casts = [
        'Ngay' => 'datetime',
        'LoaiKTKL' => 'integer',
    ];

    /**
     * Relationship với nhân viên
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'MaNV', 'MaNV');
    }

    /**
     * Kiểm tra xem có phải khen thưởng không
     */
    public function isReward()
    {
        return $this->LoaiKTKL == self::TYPE_REWARD;
    }

    /**
     * Kiểm tra xem có phải kỷ luật không
     */
    public function isPenalty()
    {
        return $this->LoaiKTKL == self::TYPE_PENALTY;
    }

    /**
     * Lấy tên loại (khen thưởng/kỷ luật)
     */
    public function getTypeNameAttribute()
    {
        return $this->isReward() ? 'Khen thưởng' : 'Kỷ luật';
    }

    /**
     * Scope để lấy chỉ khen thưởng
     */
    public function scopeRewards($query)
    {
        return $query->where('LoaiKTKL', self::TYPE_REWARD);
    }

    /**
     * Scope để lấy chỉ kỷ luật
     */
    public function scopePenalties($query)
    {
        return $query->where('LoaiKTKL', self::TYPE_PENALTY);
    }

    /**
     * Lấy danh sách khen thưởng/kỷ luật theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @param int|null $type Loại (1: khen thưởng, 0: kỷ luật, null: tất cả)
     * @return Collection
     */
    public static function getByEmployee($employeeId, $month, $year, $type = null)
    {
        $query = self::where('MaNV', $employeeId)
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month);
            
        if ($type !== null) {
            $query->where('LoaiKTKL', $type);
        }
        
        return $query->orderBy('Ngay', 'asc')->get();
    }

    /**
     * Đếm số lần khen thưởng/kỷ luật theo nhân viên, tháng, năm
     * 
     * @param int $employeeId
     * @param int $month
     * @param int $year
     * @param int|null $type Loại (1: khen thưởng, 0: kỷ luật, null: tất cả)
     * @return int
     */
    public static function countByEmployee($employeeId, $month, $year, $type = null)
    {
        $query = self::where('MaNV', $employeeId)
            ->whereYear('Ngay', $year)
            ->whereMonth('Ngay', $month);
            
        if ($type !== null) {
            $query->where('LoaiKTKL', $type);
        }
        
        return $query->count();
    }
}