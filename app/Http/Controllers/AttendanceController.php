<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display attendance logs and check-in history.
     */
    public function index()
    {
        $recentLogs = AttendanceLog::with('employee.department')
            ->orderBy('event_timestamp', 'desc')
            ->take(25)
            ->get();

        $employees = Employee::orderBy('full_name')->get();

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
            'source_device' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $locationData = null;
        if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
            $locationData = [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'source' => 'browser_geolocation'
            ];
        } else {
            $locationData = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ];
        }

        AttendanceLog::create([
            'employee_id' => $validated['employee_id'],
            'event_timestamp' => Carbon::now(),
            'event_type' => $validated['event_type'],
            'source_device' => $validated['source_device'],
            'location_data' => $locationData,
            'status' => 'Verified',
        ]);

        return redirect()->back()->with('success', 'Attendance recorded successfully!');
    }
}
