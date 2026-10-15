<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Services\GeoLocationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display attendance logs and check-in history.
     */
    public function index(Request $request)
    {
        // Auto-sync active approved leaves into attendance logs as On-Leave
        $today = Carbon::today();
        $activeApprovedLeaves = LeaveRequest::with(['leaveType', 'employee.attendanceLogs'])
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        foreach ($activeApprovedLeaves as $leave) {
            $loc = $leave->location_data ?? [];
            if (!isset($loc['latitude']) && $leave->employee) {
                $latest = $leave->employee->attendanceLogs()->where('event_type', '!=', 'On-Leave')->latest('event_timestamp')->first();
                if ($latest && $latest->location_data) {
                    $loc = $latest->location_data;
                }
            }
            if (!isset($loc['latitude'])) {
                $loc = GeoLocationService::resolveLocation($loc['ip'] ?? '127.0.0.1');
            }
            $loc['leave_request_id'] = $leave->request_id;
            $loc['note'] = 'Approved Leave: ' . ($leave->leaveType->type_name ?? 'Leave');

            AttendanceLog::firstOrCreate(
                [
                    'employee_id' => $leave->employee_id,
                    'event_type' => 'On-Leave',
                    'event_timestamp' => $today->copy()->setTime(8, 0, 0),
                ],
                [
                    'source_device' => 'Leave Approval',
                    'location_data' => $loc,
                    'status' => 'Verified',
                ]
            );
        }

        $search = trim((string) $request->input('search'));

        $logsQuery = AttendanceLog::with('employee.department')
            ->orderBy('event_timestamp', 'desc');

        if ($search !== '') {
            $logsQuery->where(function ($q) use ($search) {
                $q->where('log_id', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('source_device', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhereHas('department', function ($dq) use ($search) {
                             $dq->where('department_name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        $recentLogs = $logsQuery->paginate(10)->withQueryString();

        $employees = Employee::withoutAdmins()->orderBy('full_name')->get();

        return view('dashboard.partials.attendance', [
            'activeTab' => 'attendance',
            'recentLogs' => $recentLogs,
            'employees' => $employees,
        ]);
    }

    /**
     * Record a new attendance punch.
     */
    public function recordAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'event_type' => 'required|in:Clock-In,Clock-Out,Break-Start,Break-End',
            'source_device' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Restriction: Employee cannot record attendance if on Approved Leave
        $today = Carbon::today()->toDateString();
        $approvedLeave = LeaveRequest::with(['leaveType', 'employee'])
            ->where('employee_id', $validated['employee_id'])
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($approvedLeave) {
            $leaveCategory = $approvedLeave->leaveType->type_name ?? 'Leave';
            $empName = $approvedLeave->employee->full_name ?? 'Employee';
            return redirect()->back()->withErrors([
                'attendance_restriction' => "Punch Denied: {$empName} is currently on Approved Leave ({$leaveCategory}) from {$approvedLeave->start_date->format('M d, Y')} to {$approvedLeave->end_date->format('M d, Y')} and cannot record attendance."
            ]);
        }

        $locationData = GeoLocationService::resolveLocation(
            $request->ip(),
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null
        );
        $locationData['user_agent'] = $request->userAgent();

        AttendanceLog::create([
            'employee_id' => $validated['employee_id'],
            'event_timestamp' => Carbon::now(),
            'event_type' => $validated['event_type'],
            'source_device' => $validated['source_device'] ?? 'Web',
            'location_data' => $locationData,
            'status' => 'Verified',
        ]);

        return redirect()->back()->with('success', 'Attendance recorded successfully!');
    }

    /**
     * Update attendance log status (Verified, Invalid, Pending).
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Verified,Invalid,Pending',
        ]);

        $log = AttendanceLog::with('employee')->findOrFail($id);
        $oldStatus = $log->status;
        $newStatus = $validated['status'];

        $log->update(['status' => $newStatus]);

        // Audit Trail entry
        try {
            \App\Models\AuditTrail::create([
                'table_name' => 'attendance_logs',
                'record_id' => $log->log_id,
                'changed_by' => null,
                'change_timestamp' => now(),
                'change_description' => "Attendance Log #{$log->log_id} for " . ($log->employee->full_name ?? 'Employee') . " status changed from {$oldStatus} to {$newStatus}.",
            ]);
        } catch (\Throwable $e) {
            // Ignore audit trail failures
        }

        return redirect()->back()->with('success', "Attendance log #{$log->log_id} status updated to {$newStatus}.");
    }

    /**
     * Delete an attendance log record permanently.
     */
    public function destroy($id)
    {
        $log = AttendanceLog::with('employee')->findOrFail($id);
        $empName = $log->employee->full_name ?? 'Employee';
        $logId = $log->log_id;

        $log->delete();

        return redirect()->back()->with('success', "Attendance log #{$logId} for {$empName} has been permanently deleted.");
    }
}
