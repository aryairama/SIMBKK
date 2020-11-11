<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('roleAdmin', function ($user) {
            return $user->roles == "admin";
        });
        Gate::define('roleOperatorSekolah', function ($user) {
            return $user->roles == "operator_sekolah";
        });
        Gate::define('roleAdminOpeartor', function ($user) {
            if ($user->roles == "admin" || $user->roles == "operator_sekolah") {
                return true;
            }
            return false;
        });
    }
}
