<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RewardPenalty extends Model
{
    use HasFactory;

    protected $table = 'kt/kl';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // Định nghĩa các loại khen thưởng/kỷ luật
    const TYPE_REWARD = 1;  // Khen thưởng
    const TYPE_PENALTY = 0; // Kỷ luật

    protected $fillable = [
        'SoKTKL',
        'TieuDe',
        'NoiDung',
        'Ngay',
        'MaNV',
        'LoaiKT/KL',
        'SoTien',
    ];

    protected $casts = [
        'Ngay' => 'datetime',
        'LoaiKT/KL' => 'integer',
        'SoTien' => 'float',
        'SoKTKL' => 'integer',  // Thêm dòng này để đảm bảo SoKTKL được xử lý là integer
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
        return $this->attributes['LoaiKT/KL'] == self::TYPE_REWARD;
    }

    /**
     * Kiểm tra xem có phải kỷ luật không
     */
    public function isPenalty()
    {
        return $this->attributes['LoaiKT/KL'] == self::TYPE_PENALTY;
    }

    /**
     * Lấy tên loại (khen thưởng/kỷ luật)
     */
    public function getTypeNameAttribute()
    {
        return $this->isReward() ? 'Khen thưởng' : 'Kỷ luật';
    }

    /**
     * Lấy class CSS cho loại
     */
    public function getTypeClassAttribute()
    {
        return $this->isReward() ? 'badge-reward' : 'badge-discipline';
    }

    /**
     * Format số tiền với dấu + hoặc -
     */
    public function getFormattedAmountAttribute()
    {
        if ($this->isReward()) {
            return '+' . number_format($this->SoTien, 0, ',', '.') . ' ₫';
        } else {
            return '-' . number_format($this->SoTien, 0, ',', '.') . ' ₫';
        }
    }

    /**
     * Format ngày
     */
    public function getFormattedDateAttribute()
    {
        return $this->Ngay ? $this->Ngay->format('d/m/Y') : '';
    }

    /**
     * Scope để lấy chỉ khen thưởng
     */
    public function scopeRewards($query)
    {
        return $query->where('LoaiKT/KL', self::TYPE_REWARD);
    }

    /**
     * Scope để lấy chỉ kỷ luật
     */
    public function scopePenalties($query)
    {
        return $query->where('LoaiKT/KL', self::TYPE_PENALTY);
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
            $query->where('LoaiKT/KL', $type);
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
            $query->where('LoaiKT/KL', $type);
        }
        
        return $query->count();
    }
}