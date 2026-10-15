<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ShiftPattern;
use Illuminate\Support\Facades\Schema;
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
        View::composer(['layouts.app', 'components.modals.*', 'admin.*', 'dashboard.*'], function ($view) {
            try {
                if (Schema::hasTable('departments')) {
                    $view->with('allDepartments', Department::orderBy('department_name')->get());
                }
                if (Schema::hasTable('roles')) {
                    $view->with('allRoles', Role::orderBy('access_level', 'desc')->orderBy('role_name')->get());
                }
                if (Schema::hasTable('shift_patterns')) {
                    $view->with('allShiftPatterns', ShiftPattern::orderBy('shift_name')->get());
                }
                if (Schema::hasTable('employees')) {
                    $view->with('allEmployees', Employee::withoutAdmins()->where('is_active', true)->orderBy('full_name')->get());
                }
                if (Schema::hasTable('leave_types')) {
                    $view->with('allLeaveTypes', \App\Models\LeaveType::orderBy('type_name')->get());
                }
                if (Schema::hasTable('leave_requests')) {
                    $todayStr = now()->toDateString();
                    $view->with('activeLeaveEmployeeIds', \App\Models\LeaveRequest::where('status', 'Approved')
                        ->whereDate('start_date', '<=', $todayStr)
                        ->whereDate('end_date', '>=', $todayStr)
                        ->pluck('employee_id')
                        ->toArray());
                }
            } catch (\Throwable $e) {
                // Failsafe during setup/migration
            }
        });
    }
}
