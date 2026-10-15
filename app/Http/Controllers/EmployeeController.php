<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ShiftPattern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees and pending registrations for admin.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $query = Employee::withoutAdmins()
            ->with(['department.manager', 'role', 'shiftPattern', 'user']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhereHas('department', function ($dq) use ($search) {
                      $dq->where('department_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('role', function ($rq) use ($search) {
                      $rq->where('role_name', 'like', "%{$search}%");
                  });
            });
        }

        $employees = $query->orderBy('full_name')
            ->paginate(10)
            ->withQueryString();

        $pendingRegistrations = User::where('status', 'pending')
            ->with('employee')
            ->orderBy('created_at', 'desc')
            ->get();

        $departments = Department::orderBy('department_name')->get();
        $roles = Role::orderBy('role_name')->get();
        $shiftPatterns = ShiftPattern::orderBy('shift_name')->get();

        return view('dashboard.partials.employees', [
            'activeTab' => 'employees',
            'employees' => $employees,
            'pendingRegistrations' => $pendingRegistrations,
            'departments' => $departments,
            'roles' => $roles,
            'shiftPatterns' => $shiftPatterns,
        ]);
    }

    /**
     * Store a newly created employee in storage (Admin creation).
     * Requirements:
     * - Name and email required
     * - Default password '12345678' (hashed)
     * - Role automatically 'employee'
     * - Status automatically 'approved'
     * - Can log in immediately without extra approval
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|unique:employees,email|unique:users,email|max:191',
            'department_id' => 'nullable|exists:departments,department_id',
            'role_id' => 'nullable|exists:roles,role_id',
            'shift_pattern_id' => 'nullable|exists:shift_patterns,shift_pattern_id',
            'hire_date' => 'required|date',
            'additional_leave_days' => 'nullable|integer|min:0|max:60',
            'permission_notes' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'email.unique' => 'This email address is already in use by another user or employee.',
        ]);

        $cleanEmail = strtolower(trim($validated['email']));

        // 1. Create User account with default password '12345678', role 'employee', status 'approved'
        $user = User::create([
            'name' => trim($validated['full_name']),
            'email' => $cleanEmail,
            'password' => Hash::make('12345678'),
            'role' => 'employee',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        // 2. Create Employee profile linked to the newly created user
        Employee::create([
            'user_id' => $user->id,
            'full_name' => trim($validated['full_name']),
            'email' => $cleanEmail,
            'profile_image' => Employee::DEFAULT_AVATAR,
            'department_id' => $validated['department_id'] ?? null,
            'role_id' => $validated['role_id'] ?? null,
            'shift_pattern_id' => $validated['shift_pattern_id'] ?? null,
            'hire_date' => $validated['hire_date'],
            'additional_leave_days' => $validated['additional_leave_days'] ?? 0,
            'permission_notes' => $validated['permission_notes'] ?? null,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', "Employee '{$validated['full_name']}' created successfully with approved status and default password '12345678'.");
    }

    /**
     * Approve a self-registered user account.
     */
    public function approveRegistration(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'approved',
            'email_verified_at' => $user->email_verified_at ?: now(),
        ]);

        // Activate or create employee record
        $employee = Employee::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if ($employee) {
            $employee->update([
                'user_id' => $user->id,
                'is_active' => true,
            ]);
        } else {
            Employee::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'hire_date' => now()->toDateString(),
                'profile_image' => Employee::DEFAULT_AVATAR,
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', "Account for '{$user->name}' ({$user->email}) has been approved. The user can now log in!");
    }

    /**
     * Reject a self-registered user account.
     */
    public function rejectRegistration(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'rejected',
        ]);

        // Deactivate linked employee record if present
        $employee = Employee::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if ($employee) {
            $employee->update(['is_active' => false]);
        }

        return redirect()->back()->with('success', "Account registration for '{$user->name}' ({$user->email}) has been rejected.");
    }

    /**
     * Delete an employee record permanently.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $name = $employee->full_name;

        // Reset manager_id on departments if this employee was the manager
        Department::where('manager_id', $id)->update(['manager_id' => null]);

        // Clean up linked attendance logs & leave requests
        $employee->attendanceLogs()->delete();
        $employee->leaveRequests()->delete();

        // Delete associated user account if it is an employee role
        if ($employee->user_id) {
            $user = User::find($employee->user_id);
            if ($user && $user->role === 'employee') {
                $user->delete();
            }
        }

        $employee->delete();

        return redirect()->back()->with('success', "Employee '{$name}' has been permanently deleted.");
    }
}
