<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    use HasFactory;

    protected $table = 'loaica';
    protected $primaryKey = 'IDLoaiCa';
    public $timestamps = false;

    protected $fillable = [
        'TenLoaiCa',
        'HeSo',
    ];

    protected $casts = [
        'HeSo' => 'float',
    ];

    /**
     * Relationship với bảng tăng ca
     */
    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'IDLoaiCa', 'IDLoaiCa');
    }
}