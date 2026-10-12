<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\OrganizationController;

// 1. Overview Route
Route::get('/', [OverviewController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [OverviewController::class, 'index']);

// 2. Employees Route
Route::get('/dashboard/employees', [EmployeeController::class, 'index'])->name('dashboard.employees');
Route::post('/dashboard/employees', [EmployeeController::class, 'store'])->name('employees.store');

// 3. Attendance Routes
Route::get('/dashboard/attendance', [AttendanceController::class, 'index'])->name('dashboard.attendance');
Route::post('/attendance/record', [AttendanceController::class, 'recordAttendance'])->name('attendance.record');

// 4. Leave Routes
Route::get('/dashboard/leaves', [LeaveController::class, 'index'])->name('dashboard.leaves');
Route::post('/leave/store', [LeaveController::class, 'storeLeave'])->name('leave.store');

// 5. Organization Route
Route::get('/dashboard/organization', [OrganizationController::class, 'index'])->name('dashboard.organization');
