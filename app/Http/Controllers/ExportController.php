<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\ShiftPattern;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export data to Excel-compatible CSV format with UTF-8 BOM.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $type = $request->query('type', 'employees');
        $timestamp = date('Ymd_His');
        $filename = "attendx_{$type}_backup_{$timestamp}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($type) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Microsoft Excel handles special characters & Khmer correctly
            fputs($handle, "\xEF\xBB\xBF");

            switch ($type) {
                case 'attendance':
                    fputcsv($handle, [
                        'Log ID', 'Employee Name', 'Email', 'Department', 'Event Type', 
                        'Timestamp', 'Device', 'Status', 'Location / Coordinates / IP'
                    ]);
                    $logs = AttendanceLog::with('employee.department')->orderBy('event_timestamp', 'desc')->get();
                    foreach ($logs as $log) {
                        $loc = $log->location_data ?? [];
                        $locStr = isset($loc['latitude'], $loc['longitude']) 
                            ? "Lat: {$loc['latitude']}, Lng: {$loc['longitude']}" 
                            : ($loc['ip'] ?? 'N/A');
                        fputcsv($handle, [
                            $log->log_id,
                            $log->employee->full_name ?? 'N/A',
                            $log->employee->email ?? 'N/A',
                            $log->employee->department->department_name ?? 'Unassigned',
                            $log->event_type,
                            $log->event_timestamp,
                            $log->source_device ?? 'Web',
                            $log->status,
                            $locStr
                        ]);
                    }
                    break;

                case 'leaves':
                    fputcsv($handle, [
                        'Request ID', 'Employee Name', 'Email', 'Leave Type', 
                        'Start Date', 'End Date', 'Days', 'Status', 'Reason', 
                        'Last Edited By', 'Last Edited At'
                    ]);
                    $leaves = LeaveRequest::with(['employee', 'leaveType', 'editor'])->orderBy('created_at', 'desc')->get();
                    foreach ($leaves as $leave) {
                        $days = 0;
                        if ($leave->start_date && $leave->end_date) {
                            $days = $leave->start_date->diffInDays($leave->end_date) + 1;
                        }
                        fputcsv($handle, [
                            $leave->request_id,
                            $leave->employee->full_name ?? 'N/A',
                            $leave->employee->email ?? 'N/A',
                            $leave->leaveType->type_name ?? 'Leave',
                            $leave->start_date ? $leave->start_date->format('Y-m-d') : '',
                            $leave->end_date ? $leave->end_date->format('Y-m-d') : '',
                            $days,
                            $leave->status,
                            $leave->edit_reason ?? 'N/A',
                            $leave->editor->full_name ?? 'N/A',
                            $leave->last_edited_at ? $leave->last_edited_at->format('Y-m-d H:i') : 'N/A'
                        ]);
                    }
                    break;

                case 'organization':
                    fputcsv($handle, ['Category', 'Item ID', 'Name / Title', 'Details / Working Hours', 'Assigned Staff Count']);
                    
                    // Departments
                    foreach (Department::withCount('employees')->orderBy('department_name')->get() as $d) {
                        fputcsv($handle, ['Department', $d->department_id, $d->department_name, 'Head: ' . ($d->manager->full_name ?? 'Unassigned'), $d->employees_count]);
                    }
                    // Roles
                    foreach (Role::withCount('employees')->orderBy('access_level', 'desc')->get() as $r) {
                        fputcsv($handle, ['Role', $r->role_id, $r->role_name, 'Access Level: ' . $r->access_level, $r->employees_count]);
                    }
                    // Shift Patterns
                    foreach (ShiftPattern::withCount('employees')->orderBy('shift_name')->get() as $s) {
                        fputcsv($handle, ['Shift Pattern', $s->shift_pattern_id, $s->shift_name, "{$s->start_time} - {$s->end_time} (Grace: {$s->grace_period_mins}m)", $s->employees_count]);
                    }
                    break;

                case 'employees':
                default:
                    fputcsv($handle, [
                        'Employee ID', 'Full Name', 'Email', 'Department', 'Role', 
                        'Shift Schedule', 'Hire Date', 'Status', 'Base Annual Leave', 
                        'Seniority Bonus Days', 'Additional Days', 'Remaining Leave Days'
                    ]);
                    $employees = Employee::withoutAdmins()->with(['department', 'role', 'shiftPattern'])->orderBy('full_name')->get();
                    foreach ($employees as $emp) {
                        fputcsv($handle, [
                            $emp->employee_id,
                            $emp->full_name,
                            $emp->email,
                            $emp->department->department_name ?? 'Unassigned',
                            $emp->role->role_name ?? 'Staff',
                            $emp->shiftPattern->shift_name ?? 'Standard Schedule',
                            $emp->hire_date ? $emp->hire_date->format('Y-m-d') : '',
                            $emp->is_active ? 'Active' : 'Inactive',
                            $emp->base_annual_leave_days,
                            $emp->seniority_bonus_days,
                            $emp->additional_leave_days ?? 0,
                            $emp->remaining_annual_leave_days
                        ]);
                    }
                    break;
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export / Print data as PDF-ready HTML page.
     */
    public function exportPdf(Request $request)
    {
        $type = $request->query('type', 'employees');

        $data = [];
        $title = 'AttendX Workforce Data Backup';

        switch ($type) {
            case 'attendance':
                $title = 'Attendance Logs Backup Report';
                $data = AttendanceLog::with('employee.department')->orderBy('event_timestamp', 'desc')->get();
                break;

            case 'leaves':
                $title = 'Leave Requests Backup Report';
                $data = LeaveRequest::with(['employee', 'leaveType', 'editor'])->orderBy('created_at', 'desc')->get();
                break;

            case 'organization':
                $title = 'Organization Structure Backup Report';
                $data = [
                    'departments' => Department::with(['manager'])->withCount('employees')->orderBy('department_name')->get(),
                    'roles' => Role::withCount('employees')->orderBy('access_level', 'desc')->get(),
                    'shifts' => ShiftPattern::withCount('employees')->orderBy('shift_name')->get(),
                ];
                break;

            case 'employees':
            default:
                $title = 'Employees Master Backup Report';
                $data = Employee::withoutAdmins()->with(['department', 'role', 'shiftPattern'])->orderBy('full_name')->get();
                break;
        }

        return view('dashboard.exports.pdf_report', [
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'exportedAt' => now(),
            'exportedBy' => auth()->user()->name ?? 'Administrator',
        ]);
    }
}
