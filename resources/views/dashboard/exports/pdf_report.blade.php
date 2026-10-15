<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — AttendX Official Backup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Kantumruy Pro', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-4 sm:p-8 antialiased">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="no-print max-w-6xl mx-auto mb-6 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold">
                AX
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900">{{ $title }}</h2>
                <p class="text-xs text-slate-500">Official Database Backup Export &bull; {{ $exportedAt->format('M d, Y H:i:s') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Save as PDF / Print</span>
            </button>
            <a href="{{ route('export.excel', ['type' => $type]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Download as Excel</span>
            </a>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                Close
            </button>
        </div>
    </div>

    <!-- Printable Paper Sheet -->
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        
        <!-- Header -->
        <div class="border-b border-slate-200 pb-6 mb-6 flex items-start justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">AttendX Workforce Management</h1>
                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-brand-50 text-brand-700 border border-brand-200">System Backup</span>
                </div>
                <p class="text-sm font-semibold text-slate-600">{{ $title }}</p>
                <p class="text-xs text-slate-400 mt-1">Generated pursuant to administrative deletion safeguard protocol.</p>
            </div>
            <div class="text-right text-xs space-y-1 text-slate-500">
                <div><span class="font-semibold text-slate-700">Backup Timestamp:</span> {{ $exportedAt->format('Y-m-d H:i:s') }}</div>
                <div><span class="font-semibold text-slate-700">Exported By:</span> {{ $exportedBy }}</div>
                <div><span class="font-semibold text-slate-700">Security Level:</span> Confidential &bull; Admin Authorized</div>
            </div>
        </div>

        <!-- Content Tables According to Type -->
        @if($type === 'employees')
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-2.5">ID</th>
                            <th class="p-2.5">Full Name</th>
                            <th class="p-2.5">Email</th>
                            <th class="p-2.5">Department</th>
                            <th class="p-2.5">Role</th>
                            <th class="p-2.5">Schedule</th>
                            <th class="p-2.5">Hire Date</th>
                            <th class="p-2.5">Status</th>
                            <th class="p-2.5">Base Leave</th>
                            <th class="p-2.5">Seniority Bonus</th>
                            <th class="p-2.5">Remaining</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $emp)
                            <tr class="hover:bg-slate-50">
                                <td class="p-2.5 font-mono text-slate-500">#{{ $emp->employee_id }}</td>
                                <td class="p-2.5 font-bold text-slate-900">{{ $emp->full_name }}</td>
                                <td class="p-2.5 text-slate-600">{{ $emp->email }}</td>
                                <td class="p-2.5">{{ $emp->department->department_name ?? '—' }}</td>
                                <td class="p-2.5">{{ $emp->role->role_name ?? 'Staff' }}</td>
                                <td class="p-2.5">{{ $emp->shiftPattern->shift_name ?? 'Standard' }}</td>
                                <td class="p-2.5">{{ $emp->hire_date ? $emp->hire_date->format('Y-m-d') : 'N/A' }}</td>
                                <td class="p-2.5 font-semibold {{ $emp->is_active ? 'text-emerald-700' : 'text-slate-400' }}">{{ $emp->is_active ? 'Active' : 'Inactive' }}</td>
                                <td class="p-2.5">{{ $emp->base_annual_leave_days }}d</td>
                                <td class="p-2.5">+{{ $emp->seniority_bonus_days }}d</td>
                                <td class="p-2.5 font-bold text-brand-600">{{ $emp->remaining_annual_leave_days }}d</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($type === 'attendance')
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-2.5">Log ID</th>
                            <th class="p-2.5">Employee Name</th>
                            <th class="p-2.5">Department</th>
                            <th class="p-2.5">Event</th>
                            <th class="p-2.5">Timestamp</th>
                            <th class="p-2.5">Device</th>
                            <th class="p-2.5">Status</th>
                            <th class="p-2.5">Location / IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $log)
                            @php
                                $loc = $log->location_data ?? [];
                                $locStr = isset($loc['latitude'], $loc['longitude']) 
                                    ? "Lat: {$loc['latitude']}, Lng: {$loc['longitude']}" 
                                    : ($loc['ip'] ?? 'Local');
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="p-2.5 font-mono text-slate-500">#{{ $log->log_id }}</td>
                                <td class="p-2.5 font-bold text-slate-900">{{ $log->employee->full_name ?? 'N/A' }}</td>
                                <td class="p-2.5 text-slate-600">{{ $log->employee->department->department_name ?? '—' }}</td>
                                <td class="p-2.5 font-semibold text-slate-800">{{ $log->event_type }}</td>
                                <td class="p-2.5 font-mono text-slate-600">{{ $log->event_timestamp }}</td>
                                <td class="p-2.5">{{ $log->source_device ?? 'Web' }}</td>
                                <td class="p-2.5 font-semibold {{ $log->status === 'Verified' ? 'text-emerald-700' : ($log->status === 'Invalid' ? 'text-rose-700' : 'text-amber-700') }}">
                                    {{ $log->status }}
                                </td>
                                <td class="p-2.5 text-slate-500 font-mono truncate max-w-[200px]">{{ $locStr }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($type === 'leaves')
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-2.5">ID</th>
                            <th class="p-2.5">Employee Name</th>
                            <th class="p-2.5">Leave Type</th>
                            <th class="p-2.5">Start Date</th>
                            <th class="p-2.5">End Date</th>
                            <th class="p-2.5">Status</th>
                            <th class="p-2.5">Reason</th>
                            <th class="p-2.5">Last Edited By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $req)
                            <tr class="hover:bg-slate-50">
                                <td class="p-2.5 font-mono text-slate-500">#{{ $req->request_id }}</td>
                                <td class="p-2.5 font-bold text-slate-900">{{ $req->employee->full_name ?? 'N/A' }}</td>
                                <td class="p-2.5 font-semibold text-indigo-700">{{ $req->leaveType->type_name ?? 'Leave' }}</td>
                                <td class="p-2.5">{{ $req->start_date ? $req->start_date->format('Y-m-d') : '' }}</td>
                                <td class="p-2.5">{{ $req->end_date ? $req->end_date->format('Y-m-d') : '' }}</td>
                                <td class="p-2.5 font-semibold {{ $req->status === 'Approved' ? 'text-emerald-700' : ($req->status === 'Rejected' ? 'text-rose-700' : 'text-amber-700') }}">
                                    {{ $req->status }}
                                </td>
                                <td class="p-2.5 text-slate-600">{{ $req->edit_reason ?? '—' }}</td>
                                <td class="p-2.5 text-slate-500">{{ $req->editor->full_name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($type === 'organization')
            <div class="space-y-6">
                <div>
                    <h3 class="font-bold text-sm text-slate-900 mb-2">Departments ({{ count($data['departments']) }})</h3>
                    <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">ID</th>
                                <th class="p-2.5">Department Name</th>
                                <th class="p-2.5">Head of Dept</th>
                                <th class="p-2.5">Assigned Staff</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($data['departments'] as $d)
                                <tr>
                                    <td class="p-2.5 font-mono text-slate-500">#{{ $d->department_id }}</td>
                                    <td class="p-2.5 font-bold text-slate-900">{{ $d->department_name }}</td>
                                    <td class="p-2.5 text-slate-600">{{ $d->manager->full_name ?? 'Unassigned' }}</td>
                                    <td class="p-2.5 font-semibold text-slate-700">{{ $d->employees_count }} staff</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <h3 class="font-bold text-sm text-slate-900 mb-2">Roles & Access Levels ({{ count($data['roles']) }})</h3>
                    <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">ID</th>
                                <th class="p-2.5">Role Name</th>
                                <th class="p-2.5">Access Level</th>
                                <th class="p-2.5">Assigned Staff</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($data['roles'] as $r)
                                <tr>
                                    <td class="p-2.5 font-mono text-slate-500">#{{ $r->role_id }}</td>
                                    <td class="p-2.5 font-bold text-slate-900">{{ $r->role_name }}</td>
                                    <td class="p-2.5 font-semibold text-amber-700">Level {{ $r->access_level }}</td>
                                    <td class="p-2.5 font-semibold text-slate-700">{{ $r->employees_count }} staff</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <h3 class="font-bold text-sm text-slate-900 mb-2">Shift Schedules ({{ count($data['shifts']) }})</h3>
                    <table class="w-full text-left text-xs border border-slate-200 rounded-lg">
                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">ID</th>
                                <th class="p-2.5">Shift Name</th>
                                <th class="p-2.5">Timing</th>
                                <th class="p-2.5">Grace Window</th>
                                <th class="p-2.5">Assigned Staff</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($data['shifts'] as $s)
                                <tr>
                                    <td class="p-2.5 font-mono text-slate-500">#{{ $s->shift_pattern_id }}</td>
                                    <td class="p-2.5 font-bold text-slate-900">{{ $s->shift_name }}</td>
                                    <td class="p-2.5 font-mono text-slate-700">{{ $s->start_time }} – {{ $s->end_time }}</td>
                                    <td class="p-2.5 text-slate-600">{{ $s->grace_period_mins }} minutes</td>
                                    <td class="p-2.5 font-semibold text-slate-700">{{ $s->employees_count }} staff</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Footer / Certification -->
        <div class="mt-8 pt-4 border-t border-slate-200 flex items-center justify-between text-xs text-slate-400">
            <div>AttendX Lite Workforce &bull; Automated Backup Snapshot</div>
            <div>Page 1 of 1 &bull; Document Verified</div>
        </div>

    </div>

    <script>
        // Optional: auto-trigger print dialog on open if query ?print=1
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', () => {
                setTimeout(() => window.print(), 300);
            });
        }
    </script>
</body>
</html>
