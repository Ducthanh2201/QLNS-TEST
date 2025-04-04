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

    protected $fillable = [
        'TenCV'
    ];

    /**
     * Relationship với nhân viên
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'IDCV', 'IDCV');
    }
}