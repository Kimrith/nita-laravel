<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\ShiftPattern;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display organization departments and shift schedule patterns.
     */
    public function index()
    {
        $departments = Department::with('manager')
            ->withCount('employees')
            ->orderBy('department_name')
            ->get();

        $shiftPatterns = ShiftPattern::withCount('employees')
            ->orderBy('shift_name')
            ->get();

        return view('dashboard.partials.organization', [
            'activeTab' => 'organization',
            'departments' => $departments,
            'shiftPatterns' => $shiftPatterns,
        ]);
    }
}
