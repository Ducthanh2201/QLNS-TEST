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

    protected $fillable = [
        'TenLC',
        'HeSo',
    ];

    /**
     * Relationship với bảng công
     */
    public function timeKeepings()
    {
        return $this->hasMany(TimeKeeping::class, 'IDLC', 'IDLC');
    }
}