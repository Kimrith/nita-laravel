<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| Public / Guest Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Informational page for accounts pending administrator approval
Route::get('/pending-approval', [AuthController::class, 'pendingApproval'])->name('auth.pending');

// Logout route (requires authenticated session)
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Internationalization (i18n) Locale Switcher
Route::get('/locale/{lang}', function (string $lang) {
    if (in_array($lang, ['en', 'km'])) {
        session(['locale' => $lang]);
    }
    return redirect()->back(fallback: '/');
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Protected Application Routes (Requires Auth & Approved Status)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'approved'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Routes accessible to BOTH Employee and Admin
    |----------------------------------------------------------------------
    */
    // Leaves Management & Statutory Policies
    Route::get('/dashboard/leaves', [LeaveController::class, 'index'])->name('dashboard.leaves');
    Route::get('/admin/leaves', [LeaveController::class, 'index'])->name('admin.leaves');
    Route::get('/dashboard/leave-policies', [LeaveController::class, 'policies'])->name('dashboard.leave-policies');
    Route::get('/admin/leave-policies', [LeaveController::class, 'policies'])->name('admin.leave-policies');
    Route::post('/leave/store', [LeaveController::class, 'storeLeave'])->name('leave.store');
    Route::match(['put', 'patch', 'post'], '/dashboard/leaves/{id}/edit', [LeaveController::class, 'updateLeave'])->name('leave.update');
    Route::match(['put', 'patch', 'post'], '/admin/leaves/{id}/edit', [LeaveController::class, 'updateLeave']);

    // Root & Main Dashboard Redirection (Admins see Overview; Employees see Leaves)
    Route::get('/', [OverviewController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [OverviewController::class, 'index']);

    // Settings & Personal Profile Preferences
    Route::get('/dashboard/settings', [SettingsController::class, 'index'])->name('dashboard.settings');
    Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');

    /*
    |----------------------------------------------------------------------
    | Administrator-Only Protected Routes (Requires 'role:admin')
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // 1. Overview Dashboard (Admin alias)
        Route::get('/admin', [OverviewController::class, 'index'])->name('admin');

        // 2. Employees Management & Admin Registrations Approval
        Route::get('/dashboard/employees', [EmployeeController::class, 'index'])->name('dashboard.employees');
        Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees');
        Route::post('/dashboard/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::post('/admin/employees', [EmployeeController::class, 'store']);
        Route::delete('/dashboard/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy']);
        Route::post('/dashboard/registrations/{id}/approve', [EmployeeController::class, 'approveRegistration'])->name('admin.registrations.approve');
        Route::post('/dashboard/registrations/{id}/reject', [EmployeeController::class, 'rejectRegistration'])->name('admin.registrations.reject');

        // 3. Attendance Management
        Route::get('/dashboard/attendance', [AttendanceController::class, 'index'])->name('dashboard.attendance');
        Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');
        Route::post('/attendance/record', [AttendanceController::class, 'recordAttendance'])->name('attendance.record');
        Route::match(['patch', 'post'], '/dashboard/attendance/{id}/status', [AttendanceController::class, 'updateStatus'])->name('attendance.status');
        Route::match(['patch', 'post'], '/admin/attendance/{id}/status', [AttendanceController::class, 'updateStatus']);
        Route::delete('/dashboard/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::delete('/admin/attendance/{id}', [AttendanceController::class, 'destroy']);

        // 4. Admin-Only Leave Management Actions
        Route::post('/dashboard/leave-types', [LeaveController::class, 'storeLeaveType'])->name('leave-types.store');
        Route::post('/admin/leave-types', [LeaveController::class, 'storeLeaveType']);
        Route::match(['patch', 'post'], '/dashboard/leaves/{id}/status', [LeaveController::class, 'updateStatus'])->name('leave.status');
        Route::match(['patch', 'post'], '/admin/leaves/{id}/status', [LeaveController::class, 'updateStatus']);
        Route::delete('/dashboard/leaves/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
        Route::delete('/admin/leaves/{id}', [LeaveController::class, 'destroy']);
        Route::post('/dashboard/employees/{id}/permissions', [LeaveController::class, 'increasePermission'])->name('dashboard.employees.permissions');
        Route::post('/admin/employees/{id}/permissions', [LeaveController::class, 'increasePermission'])->name('admin.employees.permissions');

        // 5. Organization Structure
        Route::get('/dashboard/organization', [OrganizationController::class, 'index'])->name('dashboard.organization');
        Route::get('/admin/organization', [OrganizationController::class, 'index'])->name('admin.organization');
        Route::post('/dashboard/departments', [OrganizationController::class, 'storeDepartment'])->name('departments.store');
        Route::post('/admin/departments', [OrganizationController::class, 'storeDepartment']);
        Route::delete('/dashboard/departments/{id}', [OrganizationController::class, 'destroyDepartment'])->name('departments.destroy');
        Route::delete('/admin/departments/{id}', [OrganizationController::class, 'destroyDepartment']);
        Route::post('/dashboard/roles', [OrganizationController::class, 'storeRole'])->name('roles.store');
        Route::post('/admin/roles', [OrganizationController::class, 'storeRole']);
        Route::delete('/dashboard/roles/{id}', [OrganizationController::class, 'destroyRole'])->name('roles.destroy');
        Route::delete('/admin/roles/{id}', [OrganizationController::class, 'destroyRole']);
        Route::post('/dashboard/shift-patterns', [OrganizationController::class, 'storeShiftPattern'])->name('shift-patterns.store');
        Route::post('/admin/shift-patterns', [OrganizationController::class, 'storeShiftPattern']);
        Route::delete('/dashboard/shift-patterns/{id}', [OrganizationController::class, 'destroyShiftPattern'])->name('shift-patterns.destroy');
        Route::delete('/admin/shift-patterns/{id}', [OrganizationController::class, 'destroyShiftPattern']);

        // 6. Data Backup & Export Routes (Excel CSV & PDF)
        Route::get('/dashboard/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/admin/export/excel', [ExportController::class, 'exportExcel']);
        Route::get('/dashboard/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/admin/export/pdf', [ExportController::class, 'exportPdf']);
    });
});
