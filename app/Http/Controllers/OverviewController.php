<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\ShiftPattern;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    /**
     * Display the main dashboard overview page.
     */
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('is_active', true)->count(),
            'today_clocked_in' => AttendanceLog::whereDate('event_timestamp', $today)
                ->where('event_type', 'Clock-In')
                ->distinct('employee_id')
                ->count('employee_id'),
            'pending_leaves' => LeaveRequest::where('status', 'Pending')->count(),
            'total_departments' => Department::count(),
            'total_shifts' => ShiftPattern::count(),
        ];

        $recentLogs = AttendanceLog::with('employee')
            ->orderBy('event_timestamp', 'desc')
            ->take(10)
            ->get();

        $leaveRequests = LeaveRequest::with(['employee.department', 'leaveType'])
            ->orderBy('submitted_date', 'desc')
            ->take(5)
            ->get();

        $employees = Employee::orderBy('full_name')->get();
        $leaveTypes = LeaveType::all();

        return view('dashboard.partials.overview', [
            'activeTab' => 'overview',
            'stats' => $stats,
            'recentLogs' => $recentLogs,
            'leaveRequests' => $leaveRequests,
            'employees' => $employees,
            'leaveTypes' => $leaveTypes,
        ]);
    }
}
