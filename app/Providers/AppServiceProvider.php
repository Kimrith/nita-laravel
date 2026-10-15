<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ShiftPattern;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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

        // Rate limit: Login endpoint (3 requests per 1 minute)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function (Request $request, array $headers) {
                $seconds = $headers['Retry-After'] ?? 60;
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => __('Too many login attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]),
                    ], 429, $headers);
                }

                return response()->view('errors.429', [
                    'seconds' => $seconds,
                    'action' => 'login',
                    'returnUrl' => route('login'),
                ], 429, $headers);
            });
        });

        // Rate limit: Registration endpoint (3 requests per 1 minute)
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function (Request $request, array $headers) {
                $seconds = $headers['Retry-After'] ?? 60;
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => __('Too many registration attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]),
                    ], 429, $headers);
                }

                return response()->view('errors.429', [
                    'seconds' => $seconds,
                    'action' => 'register',
                    'returnUrl' => route('register'),
                ], 429, $headers);
            });
        });
    }
}
