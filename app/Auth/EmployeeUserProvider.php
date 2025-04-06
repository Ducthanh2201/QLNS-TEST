<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class EmployeeUserProvider extends EloquentUserProvider
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
        // Lấy mật khẩu từ credentials
        $plain = $credentials['password'];
        
        // Kiểm tra mật khẩu trực tiếp với dạng plain text
        return $plain === $user->getAuthPassword();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || 
            (count($credentials) === 1 && 
             array_key_exists('password', $credentials))) {
            return null;
        }

        if (isset($credentials['employee_id'])) {
            $credentials['MaNV'] = $credentials['employee_id'];
            unset($credentials['employee_id']);
        }

        return parent::retrieveByCredentials($credentials);
    }
}