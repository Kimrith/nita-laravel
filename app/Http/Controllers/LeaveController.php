<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display leave requests and balances.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::with(['employee.department', 'leaveType', 'approver'])
            ->orderBy('submitted_date', 'desc')
            ->get();

        $leaveTypes = LeaveType::all();
        $employees = Employee::orderBy('full_name')->get();

        return view('dashboard.partials.leaves', [
            'activeTab' => 'leaves',
            'leaveRequests' => $leaveRequests,
            'leaveTypes' => $leaveTypes,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a new leave request application.
     */
    public function storeLeave(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'leave_type_id' => 'required|exists:leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'Pending',
            'submitted_date' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully!');
    }
}
