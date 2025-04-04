<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Auth\AdminUserProvider;
use App\Auth\EmployeeUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Đăng ký provider tùy chỉnh cho admin
        Auth::provider('admin_provider', function ($app, array $config) {
            return new AdminUserProvider($app['hash'], $config['model']);
        });
        
        // Đăng ký custom user provider cho employee
        Auth::provider('employee_provider', function ($app, array $config) {
            return new EmployeeUserProvider($app['hash'], $config['model']);
        });
    }
}