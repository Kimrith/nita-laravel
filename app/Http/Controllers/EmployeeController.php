<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ShiftPattern;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index()
    {
        $employees = Employee::with(['department', 'role', 'shiftPattern'])
            ->orderBy('full_name')
            ->get();

        $departments = Department::orderBy('department_name')->get();
        $roles = Role::orderBy('role_name')->get();
        $shiftPatterns = ShiftPattern::orderBy('shift_name')->get();

        return view('dashboard.partials.employees', [
            'activeTab' => 'employees',
            'employees' => $employees,
            'departments' => $departments,
            'roles' => $roles,
            'shiftPatterns' => $shiftPatterns,
        ]);
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|unique:employees,email|max:191',
            'department_id' => 'nullable|exists:departments,department_id',
            'role_id' => 'nullable|exists:roles,role_id',
            'shift_pattern_id' => 'nullable|exists:shift_patterns,shift_pattern_id',
            'hire_date' => 'required|date',
            'is_active' => 'nullable|boolean',
        ]);

        Employee::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'department_id' => $validated['department_id'] ?? null,
            'role_id' => $validated['role_id'] ?? null,
            'shift_pattern_id' => $validated['shift_pattern_id'] ?? null,
            'hire_date' => $validated['hire_date'],
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Employee ' . $validated['full_name'] . ' added successfully!');
    }
}
