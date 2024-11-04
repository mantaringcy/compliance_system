<?php

namespace App\Providers;

use App\Http\View\Composers\SidebarComposer;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $departments = Department::all();
        $roles = Role::all();
        
        View::share('departments', $departments);
        View::share('roles', $roles);

        view()->composer('components.main', SidebarComposer::class);

        // dd($department);
    }
}
