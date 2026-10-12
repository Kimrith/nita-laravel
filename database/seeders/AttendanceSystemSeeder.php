<?php

namespace Database\Seeders;

use App\Models\AttendanceLog;
use App\Models\AuditTrail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Role;
use App\Models\ShiftPattern;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::create(['role_name' => 'Admin', 'access_level' => 3]);
        $managerRole = Role::create(['role_name' => 'Department Manager', 'access_level' => 2]);
        $staffRole = Role::create(['role_name' => 'Staff', 'access_level' => 1]);

        // 2. Shift Patterns
        $morningShift = ShiftPattern::create([
            'shift_name' => 'Morning Shift (Standard)',
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'is_flexible' => false,
            'grace_period_mins' => 15,
        ]);

        $eveningShift = ShiftPattern::create([
            'shift_name' => 'Evening Shift',
            'start_time' => '13:00:00',
            'end_time' => '21:00:00',
            'is_flexible' => true,
            'grace_period_mins' => 30,
        ]);

        // 3. Departments
        $engineering = Department::create(['department_name' => 'Engineering']);
        $hr = Department::create(['department_name' => 'Human Resources']);
        $marketing = Department::create(['department_name' => 'Marketing']);

        // 4. Leave Types
        $sickLeave = LeaveType::create(['type_name' => 'Sick', 'is_paid' => true]);
        $vacationLeave = LeaveType::create(['type_name' => 'Vacation', 'is_paid' => true]);
        $personalLeave = LeaveType::create(['type_name' => 'Personal', 'is_paid' => false]);

        // 5. Employees
        $manager = Employee::create([
            'full_name' => 'Sarah Connor',
            'email' => 'sarah.connor@example.com',
            'department_id' => $engineering->department_id,
            'role_id' => $managerRole->role_id,
            'shift_pattern_id' => $morningShift->shift_pattern_id,
            'hire_date' => Carbon::now()->subYears(2)->toDateString(),
            'is_active' => true,
        ]);
        $engineering->update(['manager_id' => $manager->employee_id]);

        $emp1 = Employee::create([
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'department_id' => $engineering->department_id,
            'role_id' => $staffRole->role_id,
            'shift_pattern_id' => $morningShift->shift_pattern_id,
            'hire_date' => Carbon::now()->subMonths(8)->toDateString(),
            'is_active' => true,
        ]);

        $emp2 = Employee::create([
            'full_name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'department_id' => $hr->department_id,
            'role_id' => $staffRole->role_id,
            'shift_pattern_id' => $eveningShift->shift_pattern_id,
            'hire_date' => Carbon::now()->subYear()->toDateString(),
            'is_active' => true,
        ]);

        // 6. Attendance Logs
        $today = Carbon::today();
        AttendanceLog::create([
            'employee_id' => $emp1->employee_id,
            'event_timestamp' => $today->copy()->setTime(8, 2, 0),
            'event_type' => 'Clock-In',
            'source_device' => 'Mobile',
            'location_data' => ['latitude' => 11.5564, 'longitude' => 104.9282, 'accuracy_m' => 10],
            'status' => 'Verified',
        ]);
        AttendanceLog::create([
            'employee_id' => $emp1->employee_id,
            'event_timestamp' => $today->copy()->setTime(12, 1, 0),
            'event_type' => 'Break-Start',
            'source_device' => 'Web',
            'location_data' => ['ip' => '192.168.1.45'],
            'status' => 'Verified',
        ]);
        AttendanceLog::create([
            'employee_id' => $emp1->employee_id,
            'event_timestamp' => $today->copy()->setTime(13, 0, 0),
            'event_type' => 'Break-End',
            'source_device' => 'Web',
            'location_data' => ['ip' => '192.168.1.45'],
            'status' => 'Verified',
        ]);

        // 7. Leave Requests
        LeaveRequest::create([
            'employee_id' => $emp1->employee_id,
            'start_date' => Carbon::tomorrow()->toDateString(),
            'end_date' => Carbon::tomorrow()->addDays(2)->toDateString(),
            'leave_type_id' => $vacationLeave->leave_type_id,
            'status' => 'Approved',
            'approver_id' => $manager->employee_id,
            'submitted_date' => Carbon::now()->subDays(3),
        ]);

        // 8. Audit Trail
        AuditTrail::create([
            'table_name' => 'leave_requests',
            'record_id' => 1,
            'changed_by' => $manager->employee_id,
            'change_timestamp' => Carbon::now()->subDays(2),
            'change_description' => 'Approved vacation leave request for John Doe (3 days).',
        ]);
    }
}
