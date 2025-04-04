<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email', 
        'Password',
        'HinhAnh'
    ];

    protected $hidden = [
        'Password',
    ];

    // Ghi đè phương thức xác thực mật khẩu
    public function getAuthPassword()
    {
        return $this->Password;
    }
}