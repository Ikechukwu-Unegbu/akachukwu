<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        try {
            Gate::before(function ($user, $ability) {
                return $user->role == 'superadmin' ? true : null;
            });

            foreach ($this->getPermissions() as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            }

            Gate::define('viewLogViewer', function (?User $user) {
                return $user && $user->isSuperAdmin();
            });
        } catch (\Exception $e) {

        }
    }

    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
