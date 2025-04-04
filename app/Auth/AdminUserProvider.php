<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

class AdminUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Lưu ý chúng ta cần sử dụng trường Password thay vì password
        $plain = $credentials['password'];
        
        // Kiểm tra password trực tiếp với dạng plain text
        return $plain === $user->getAuthPassword();
    }
}