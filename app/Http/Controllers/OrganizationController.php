<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ShiftPattern;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display organization departments, roles, and shift schedule patterns.
     */
    public function index()
    {
        $departments = Department::with('manager')
            ->withCount('employees')
            ->orderBy('department_name')
            ->get();

        $roles = Role::withCount('employees')
            ->orderBy('access_level', 'desc')
            ->orderBy('role_name')
            ->get();

        $shiftPatterns = ShiftPattern::withCount('employees')
            ->orderBy('shift_name')
            ->get();

        $employees = Employee::withoutAdmins()
            ->where('is_active', true)
            ->orderBy('full_name')
            ->get();

        return view('dashboard.partials.organization', [
            'activeTab' => 'organization',
            'departments' => $departments,
            'roles' => $roles,
            'shiftPatterns' => $shiftPatterns,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created Department.
     */
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:100|unique:departments,department_name',
            'manager_id' => 'nullable|exists:employees,employee_id',
        ]);

        $department = Department::create([
            'department_name' => trim($validated['department_name']),
            'manager_id' => $validated['manager_id'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Department "' . $department->department_name . '" created successfully!');
    }

    /**
     * Store a newly created Role.
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:100|unique:roles,role_name',
            'access_level' => 'required|integer|min:1|max:10',
        ]);

        $role = Role::create([
            'role_name' => trim($validated['role_name']),
            'access_level' => (int) $validated['access_level'],
        ]);

        return redirect()->back()->with('success', 'Role "' . $role->role_name . '" (Level ' . $role->access_level . ') created successfully!');
    }

    /**
     * Store a newly created Shift Pattern.
     */
    public function storeShiftPattern(Request $request)
    {
        $validated = $request->validate([
            'shift_name' => 'required|string|max:100|unique:shift_patterns,shift_name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'is_flexible' => 'nullable|boolean',
            'grace_period_mins' => 'required|integer|min:0|max:240',
        ]);

        $startTime = strlen($validated['start_time']) === 5 ? $validated['start_time'] . ':00' : $validated['start_time'];
        $endTime = strlen($validated['end_time']) === 5 ? $validated['end_time'] . ':00' : $validated['end_time'];

        $shift = ShiftPattern::create([
            'shift_name' => trim($validated['shift_name']),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_flexible' => $request->has('is_flexible'),
            'grace_period_mins' => (int) $validated['grace_period_mins'],
        ]);

        return redirect()->back()->with('success', 'Shift Pattern "' . $shift->shift_name . '" created successfully!');
    }

    /**
     * Delete a department permanently.
     */
    public function destroyDepartment($id)
    {
        $dept = Department::findOrFail($id);
        $name = $dept->department_name;

        // Disassociate employees assigned to this department
        Employee::where('department_id', $id)->update(['department_id' => null]);

        $dept->delete();

        return redirect()->back()->with('success', "Department '{$name}' has been permanently deleted.");
    }

    /**
     * Delete a job role permanently.
     */
    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        $name = $role->role_name;

        // Disassociate employees assigned to this role
        Employee::where('role_id', $id)->update(['role_id' => null]);

        $role->delete();

        return redirect()->back()->with('success', "Role '{$name}' has been permanently deleted.");
    }

    /**
     * Delete a shift pattern permanently.
     */
    public function destroyShiftPattern($id)
    {
        $shift = ShiftPattern::findOrFail($id);
        $name = $shift->shift_name;

        // Disassociate employees assigned to this shift pattern
        Employee::where('shift_pattern_id', $id)->update(['shift_pattern_id' => null]);

        $shift->delete();

        return redirect()->back()->with('success', "Shift Pattern '{$name}' has been permanently deleted.");
    }
}
