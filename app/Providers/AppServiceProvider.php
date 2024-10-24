<?php

namespace App\Providers;

use App\Http\View\Composers\SidebarComposer;
use App\Models\Department;
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
        
        View::share('departments', $departments);

        view()->composer('components.main', SidebarComposer::class);

        // dd($department);
    }
}
