<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\GeoLocationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display leave requests, categories, and balances.
     * Enforces strict scoping: employees view only their own records.
     */
    public function index(Request $request)
    {
        // Failsafe: Ensure standard Cambodia Labor Law leave categories exist
        $defaults = [
            ['type_name' => 'Annual Leave', 'is_paid' => true],
            ['type_name' => 'Special Leave', 'is_paid' => true],
            ['type_name' => 'Sick Leave', 'is_paid' => true],
            ['type_name' => 'Maternity Leave', 'is_paid' => true],
            ['type_name' => 'Unpaid Leave', 'is_paid' => false],
        ];

        foreach ($defaults as $def) {
            LeaveType::firstOrCreate(['type_name' => $def['type_name']], $def);
        }

        $user = Auth::user();
        $isAdmin = $user && $user->isAdmin();
        $search = trim((string) $request->input('search'));

        $query = LeaveRequest::with(['employee.department', 'employee.attendanceLogs', 'leaveType', 'approver', 'editor'])
            ->orderBy('submitted_date', 'desc');

        if (!$isAdmin) {
            $employee = $user?->getAssociatedEmployee();
            $employeeId = $employee ? $employee->employee_id : 0;
            $query->where('employee_id', $employeeId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('request_id', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('leaveType', function ($tq) use ($search) {
                      $tq->where('type_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('approver', function ($aq) use ($search) {
                      $aq->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $leaveRequests = $query->paginate(10)->withQueryString();
        $leaveTypes = LeaveType::orderBy('type_name')->get();

        if ($isAdmin) {
            $empQuery = Employee::withoutAdmins()
                ->with(['department', 'role', 'leaveRequests.leaveType']);

            if ($search !== '') {
                $empQuery->where(function ($eq) use ($search) {
                    $eq->where('full_name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhereHas('department', function ($dq) use ($search) {
                           $dq->where('department_name', 'like', "%{$search}%");
                       })
                       ->orWhereHas('role', function ($rq) use ($search) {
                           $rq->where('role_name', 'like', "%{$search}%");
                       });
                });
            }

            $employees = $empQuery->orderBy('full_name')->get();
        } else {
            $currentEmp = $user?->getAssociatedEmployee();
            $empQuery = $currentEmp 
                ? Employee::withoutAdmins()->with(['department', 'role', 'leaveRequests.leaveType'])->where('employee_id', $currentEmp->employee_id)
                : null;

            if ($empQuery && $search !== '') {
                $empQuery->where(function ($eq) use ($search) {
                    $eq->where('full_name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $employees = $empQuery ? $empQuery->get() : collect([]);
        }

        return view('dashboard.partials.leaves', [
            'activeTab' => 'leaves',
            'leaveRequests' => $leaveRequests,
            'leaveTypes' => $leaveTypes,
            'employees' => $employees,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Display Cambodia's Statutory Labor Law Leave Rules & Permissions Handbook.
     */
    public function policies()
    {
        $user = Auth::user();
        $isAdmin = $user && $user->isAdmin();

        if ($isAdmin) {
            $employees = Employee::withoutAdmins()
                ->with(['department', 'role', 'leaveRequests.leaveType'])
                ->orderBy('full_name')
                ->get();
        } else {
            $currentEmp = $user?->getAssociatedEmployee();
            $employees = $currentEmp
                ? Employee::withoutAdmins()->with(['department', 'role', 'leaveRequests.leaveType'])->where('employee_id', $currentEmp->employee_id)->get()
                : collect([]);
        }

        return view('dashboard.partials.leave-policies', [
            'activeTab' => 'leave-policies',
            'employees' => $employees,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Increase / adjust an employee's statutory permission & bonus leave days (Admin only).
     */
    public function increasePermission(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can adjust leave permissions.');
        }

        $validated = $request->validate([
            'additional_days' => 'required|integer|min:1|max:60',
            'permission_notes' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->additional_leave_days = ($employee->additional_leave_days ?? 0) + $validated['additional_days'];
        
        $logEntry = now()->format('Y-m-d') . ": +" . $validated['additional_days'] . " days" . 
            (!empty($validated['permission_notes']) ? " (" . $validated['permission_notes'] . ")" : "");
        
        $employee->permission_notes = $employee->permission_notes 
            ? $employee->permission_notes . "\n" . $logEntry 
            : $logEntry;
            
        $employee->save();

        return redirect()->back()->with('success', "Granted +{$validated['additional_days']} permission days to {$employee->full_name} successfully!");
    }

    /**
     * Store a new leave request application.
     * Derives employee identity from authenticated session for employees.
     */
    public function storeLeave(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->isAdmin();

        $rules = [
            'leave_type_id' => 'required|exists:leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];

        if ($isAdmin) {
            $rules['employee_id'] = 'required|exists:employees,employee_id';
        }

        $validated = $request->validate($rules);

        // Security enforcement: For employees, derive identity strictly from authenticated session
        if (!$isAdmin) {
            $currentEmployee = $user->getAssociatedEmployee();
            if (!$currentEmployee) {
                return redirect()->back()->withErrors(['employee_id' => __('No active employee profile associated with your user account.')]);
            }

            // Check Total Entitled
            if ($currentEmployee->total_annual_leave_entitlement <= 0) {
                return redirect()->back()
                    ->withErrors(['leave' => __('You are not allowed to request leave because you do not have any leave entitlement (Total Entitled is 0).')])
                    ->with('error', __('You are not allowed to request leave because you do not have any leave entitlement (Total Entitled is 0).'));
            }

            $targetEmployeeId = $currentEmployee->employee_id;
        } else {
            $targetEmployee = Employee::find($validated['employee_id']);
            if (!$targetEmployee) {
                return redirect()->back()->withErrors(['employee_id' => __('Employee record not found.')]);
            }

            if ($targetEmployee->total_annual_leave_entitlement <= 0) {
                return redirect()->back()
                    ->withErrors(['leave' => __('Cannot submit leave request: Selected employee does not have any leave entitlement (Total Entitled is 0).')])
                    ->with('error', __('Cannot submit leave request: Selected employee does not have any leave entitlement (Total Entitled is 0).'));
            }

            $targetEmployeeId = $targetEmployee->employee_id;
        }

        $locationData = GeoLocationService::resolveLocation(
            $request->ip(),
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null
        );

        LeaveRequest::create([
            'user_id' => $user->id,
            'employee_id' => $targetEmployeeId,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'Pending',
            'submitted_date' => Carbon::now(),
            'location_data' => $locationData,
        ]);

        return redirect()->back()->with('success', __('Leave request submitted successfully!'));
    }

    /**
     * Store a newly created Leave Type (Admin only).
     */
    public function storeLeaveType(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can create leave categories.');
        }

        $validated = $request->validate([
            'type_name' => 'required|string|max:100|unique:leave_types,type_name',
            'is_paid' => 'nullable|boolean',
        ]);

        $leaveType = LeaveType::create([
            'type_name' => trim($validated['type_name']),
            'is_paid' => $request->has('is_paid'),
        ]);

        return redirect()->back()->with('success', 'Leave Category "' . $leaveType->type_name . '" (' . ($leaveType->is_paid ? 'Paid' : 'Unpaid') . ') created successfully!');
    }

    /**
     * Update leave request status (Approve, Reject, or Reset) - Admin only.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can approve or reject leave requests.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected,Pending',
            'approver_id' => 'nullable|exists:employees,employee_id',
        ]);

        $leaveRequest = LeaveRequest::with(['employee.department'])->findOrFail($id);

        // Determine approver: use input, or department manager, or any supervisor/staff
        $approverId = $validated['approver_id'] ?? null;
        if (!$approverId && $leaveRequest->employee && $leaveRequest->employee->department) {
            $approverId = $leaveRequest->employee->department->manager_id;
        }
        if (!$approverId) {
            $supervisor = Employee::where('is_active', true)
                ->where('employee_id', '!=', $leaveRequest->employee_id)
                ->first();
            $approverId = $supervisor?->employee_id ?? $leaveRequest->employee_id;
        }

        $oldStatus = $leaveRequest->status;
        $newStatus = $validated['status'];

        $leaveRequest->update([
            'status' => $newStatus,
            'approver_id' => $newStatus === 'Pending' ? null : $approverId,
        ]);

        // Audit Trail entry
        try {
            \App\Models\AuditTrail::create([
                'table_name' => 'leave_requests',
                'record_id' => $leaveRequest->request_id,
                'changed_by' => $approverId,
                'change_timestamp' => now(),
                'change_description' => "Leave Request #{$leaveRequest->request_id} for {$leaveRequest->employee->full_name} status updated from {$oldStatus} to {$newStatus}.",
            ]);
        } catch (\Throwable $e) {
            // Ignore audit trail failures
        }

        // Push to Attendance Logs when Approved; remove if rejected or reset
        if ($newStatus === 'Approved') {
            $loc = $leaveRequest->location_data ?? [];
            if (!isset($loc['latitude']) && $leaveRequest->employee) {
                $latest = $leaveRequest->employee->attendanceLogs()->where('event_type', '!=', 'On-Leave')->latest('event_timestamp')->first();
                if ($latest && $latest->location_data) {
                    $loc = $latest->location_data;
                }
            }
            $loc['leave_request_id'] = $leaveRequest->request_id;
            $loc['note'] = 'Approved Leave: ' . ($leaveRequest->leaveType->type_name ?? 'Leave');

            \App\Models\AttendanceLog::updateOrCreate(
                [
                    'employee_id' => $leaveRequest->employee_id,
                    'event_type' => 'On-Leave',
                    'event_timestamp' => now()->startOfDay(),
                ],
                [
                    'source_device' => 'Leave Approval',
                    'location_data' => $loc,
                    'status' => 'Verified',
                ]
            );
        } else {
            \App\Models\AttendanceLog::where('employee_id', $leaveRequest->employee_id)
                ->where('event_type', 'On-Leave')
                ->delete();
        }

        $msg = match ($newStatus) {
            'Approved' => "Leave request #{$leaveRequest->request_id} has been Approved and pushed to Attendance Logs!",
            'Rejected' => "Leave request #{$leaveRequest->request_id} has been Rejected.",
            default => "Leave request #{$leaveRequest->request_id} has been reset to Pending.",
        };

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Update leave request details (Edit button for admin and employee).
     * If an employee edits, only allows editing their own request and tracks who edited it.
     */
    public function updateLeave(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $isAdmin = $user->isAdmin();
        $currentEmp = $user->getAssociatedEmployee();

        $leaveRequest = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($id);

        // Security authorization check:
        // Employees can only edit their own leave requests
        if (!$isAdmin) {
            if (!$currentEmp || $leaveRequest->employee_id != $currentEmp->employee_id) {
                abort(403, 'Unauthorized. You can only edit your own leave requests.');
            }
        }

        $rules = [
            'leave_type_id' => 'required|exists:leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'edit_reason' => 'nullable|string|max:255',
        ];

        if ($isAdmin) {
            $rules['status'] = 'nullable|in:Pending,Approved,Rejected';
        }

        $validated = $request->validate($rules);

        $editorId = $currentEmp ? $currentEmp->employee_id : null;
        $oldStartDate = $leaveRequest->start_date ? $leaveRequest->start_date->format('Y-m-d') : '';
        $oldEndDate = $leaveRequest->end_date ? $leaveRequest->end_date->format('Y-m-d') : '';
        $oldTypeName = $leaveRequest->leaveType->type_name ?? 'Leave';

        $updateData = [
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'last_edited_by' => $editorId,
            'last_edited_at' => now(),
            'edit_reason' => $validated['edit_reason'] ?? null,
        ];

        if ($isAdmin && !empty($validated['status'])) {
            $updateData['status'] = $validated['status'];
        }

        $leaveRequest->update($updateData);

        // Audit Trail log
        try {
            $editorName = $currentEmp ? $currentEmp->full_name : $user->name;
            \App\Models\AuditTrail::create([
                'table_name' => 'leave_requests',
                'record_id' => $leaveRequest->request_id,
                'changed_by' => $editorId,
                'change_timestamp' => now(),
                'change_description' => "Leave Request #{$leaveRequest->request_id} for {$leaveRequest->employee->full_name} edited by {$editorName}. Dates changed from {$oldStartDate} - {$oldEndDate} ({$oldTypeName})." . (!empty($validated['edit_reason']) ? " Reason: {$validated['edit_reason']}" : ""),
            ]);
        } catch (\Throwable $e) {
            // Ignore audit trail failure
        }

        return redirect()->back()->with('success', "Leave request #{$leaveRequest->request_id} updated successfully!");
    }

    /**
     * Delete a leave request permanently.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized action. Only administrators can delete leave requests.');
        }

        $leave = LeaveRequest::with('employee')->findOrFail($id);
        $empName = $leave->employee->full_name ?? 'Employee';
        $requestId = $leave->request_id;

        $leave->delete();

        return redirect()->back()->with('success', "Leave request #{$requestId} for {$empName} has been permanently deleted.");
    }
}
