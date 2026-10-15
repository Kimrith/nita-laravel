@extends('layouts.app')

@section('title', 'Employees Directory — AttendX Dashboard')

@section('content')
<div class="space-y-4">
    <!-- Pending Account Registrations Section (Admin Only) -->
    @if(isset($pendingRegistrations) && $pendingRegistrations->count() > 0)
        <div class="w-full bg-white dark:bg-slate-900 border-2 border-amber-300 dark:border-amber-800/80 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <div class="p-4 sm:p-5 bg-amber-50/60 dark:bg-amber-950/40 border-b border-amber-200/80 dark:border-amber-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-amber-500/20">
                        ⏳
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>{{ __('Pending User Registrations') }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white">
                                {{ $pendingRegistrations->count() }}
                            </span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Self-registered employee accounts awaiting your approval before dashboard access is permitted.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">{{ __('Applicant Name') }}</th>
                            <th class="px-4 py-3">{{ __('Email Address') }}</th>
                            <th class="px-4 py-3">{{ __('Registered At') }}</th>
                            <th class="px-4 py-3">{{ __('Requested Role') }}</th>
                            <th class="px-4 py-3">{{ __('Status') }}</th>
                            <th class="px-4 sm:px-5 py-3 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($pendingRegistrations as $pending)
                            <tr class="hover:bg-amber-50/30 dark:hover:bg-slate-800/40 transition">
                                <td class="px-4 sm:px-5 py-3 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $pending->name }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300 font-mono text-xs">
                                    {{ $pending->email }}
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $pending->created_at ? $pending->created_at->format('M d, Y H:i') : 'Just now' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ ucfirst($pending->role) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        {{ __('Pending Approval') }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Approve Button Form -->
                                        <form method="POST" action="{{ route('admin.registrations.approve', $pending->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>{{ __('Approve') }}</span>
                                            </button>
                                        </form>

                                        <!-- Reject Button Form -->
                                        <form method="POST" action="{{ route('admin.registrations.reject', $pending->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Are you sure you want to reject this registration request?')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 shadow-xs transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>{{ __('Reject') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header with Search -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">{{ __('Employee Directory') }}</h3>
                <p class="text-xs text-slate-400">{{ __('All registered workforce members') }} ({{ $employees->total() }})</p>
            </div>
            <!-- Search Bar & Add Button -->
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <form method="GET" action="{{ route('dashboard.employees') }}" class="relative flex-1 sm:w-72">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           onkeyup="filterTable(this, 'employeeDirectoryTable')" 
                           placeholder="{{ __('Search employees...') }}" 
                           class="w-full text-xs pl-9 pr-8 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @if(request('search'))
                        <a href="{{ route('dashboard.employees') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5" title="{{ __('Clear search') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
                <button type="button" onclick="openModal('employeeModal')" class="inline-flex items-center justify-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('Add Employee') }}</span>
                </button>
            </div>
        </div>

        <!-- Responsive Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm" id="employeeDirectoryTable">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Employee') }}</th>
                        <th class="px-4 py-3.5">{{ __('Department') }}</th>
                        <th class="px-4 py-3.5">{{ __('Role') }}</th>
                        <th class="px-4 py-3.5">{{ __('Shift Pattern') }}</th>
                        <th class="px-4 py-3.5">{{ __('Hire Date') }}</th>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Status') }}</th>
                        <th class="px-4 sm:px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        @continue($emp->email === 'admin@example.com' || ($emp->user && $emp->user->isAdmin()))
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5 cursor-pointer" onclick="showEmployeeDetail({{ json_encode([
                                'id' => $emp->employee_id,
                                'name' => $emp->full_name,
                                'email' => $emp->email,
                                'image' => $emp->profile_image,
                                'is_active' => (bool) $emp->is_active,
                                'department' => $emp->department->department_name ?? __('Unassigned'),
                                'department_head' => $emp->department?->manager?->full_name ?? __('Unassigned'),
                                'role' => $emp->role->role_name ?? __('Staff'),
                                'access_level' => (int) ($emp->role->access_level ?? 1),
                                'shift_name' => $emp->shiftPattern->shift_name ?? __('Standard Schedule'),
                                'shift_time' => $emp->shiftPattern ? substr($emp->shiftPattern->start_time, 0, 5) . ' – ' . substr($emp->shiftPattern->end_time, 0, 5) : '08:00 – 17:00',
                                'grace_period' => (int) ($emp->shiftPattern->grace_period_mins ?? 15),
                                'is_flexible' => (bool) ($emp->shiftPattern->is_flexible ?? false),
                                'hire_date' => $emp->hire_date ? \Carbon\Carbon::parse($emp->hire_date)->format('M d, Y') : 'N/A',
                                'years' => (int) $emp->years_of_service,
                                'months' => (int) $emp->months_of_service,
                                'base_leave' => (float) $emp->base_annual_leave_days,
                                'seniority_leave' => (int) $emp->seniority_bonus_days,
                                'bonus_leave' => (int) ($emp->additional_leave_days ?? 0),
                                'total_leave' => (float) $emp->total_annual_leave_entitlement,
                                'used_leave' => (float) $emp->used_annual_leave_days,
                                'remaining_leave' => (float) $emp->remaining_annual_leave_days,
                                'notes' => (string) ($emp->permission_notes ?? ''),
                            ]) }})">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $emp->profile_image }}" alt="{{ $emp->full_name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm flex-shrink-0 bg-slate-100" onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 truncate hover:text-brand-600 transition">{{ $emp->full_name }}</div>
                                        <div class="text-xs text-slate-400 truncate">{{ $emp->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $emp->department->department_name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 border border-brand-200">
                                    {{ $emp->role->role_name ?? __('Staff') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $emp->shiftPattern->shift_name ?? __('Standard Schedule') }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $emp->shiftPattern ? substr($emp->shiftPattern->start_time, 0, 5) . ' - ' . substr($emp->shiftPattern->end_time, 0, 5) : '08:00 - 17:00' }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($emp->hire_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                @if($emp->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            onclick="showEmployeeDetail({{ json_encode([
                                                'id' => $emp->employee_id,
                                                'name' => $emp->full_name,
                                                'email' => $emp->email,
                                                'image' => $emp->profile_image,
                                                'is_active' => (bool) $emp->is_active,
                                                'department' => $emp->department->department_name ?? __('Unassigned'),
                                                'department_head' => $emp->department?->manager?->full_name ?? __('Unassigned'),
                                                'role' => $emp->role->role_name ?? __('Staff'),
                                                'access_level' => (int) ($emp->role->access_level ?? 1),
                                                'shift_name' => $emp->shiftPattern->shift_name ?? __('Standard Schedule'),
                                                'shift_time' => $emp->shiftPattern ? substr($emp->shiftPattern->start_time, 0, 5) . ' – ' . substr($emp->shiftPattern->end_time, 0, 5) : '08:00 – 17:00',
                                                'grace_period' => (int) ($emp->shiftPattern->grace_period_mins ?? 15),
                                                'is_flexible' => (bool) ($emp->shiftPattern->is_flexible ?? false),
                                                'hire_date' => $emp->hire_date ? \Carbon\Carbon::parse($emp->hire_date)->format('M d, Y') : 'N/A',
                                                'years' => (int) $emp->years_of_service,
                                                'months' => (int) $emp->months_of_service,
                                                'base_leave' => (float) $emp->base_annual_leave_days,
                                                'seniority_leave' => (int) $emp->seniority_bonus_days,
                                                'bonus_leave' => (int) ($emp->additional_leave_days ?? 0),
                                                'total_leave' => (float) $emp->total_annual_leave_entitlement,
                                                'used_leave' => (float) $emp->used_annual_leave_days,
                                                'remaining_leave' => (float) $emp->remaining_annual_leave_days,
                                                'notes' => (string) ($emp->permission_notes ?? ''),
                                            ]) }})"
                                            class="px-2.5 py-1.5 rounded-lg text-brand-700 bg-brand-50 hover:bg-brand-100 dark:bg-brand-950/40 dark:text-brand-300 dark:hover:bg-brand-900/60 font-semibold text-xs transition inline-flex items-center gap-1 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>{{ __('View Detail') }}</span>
                                    </button>

                                    <!-- Delete Employee Button with Backup Warning -->
                                    <button type="button" 
                                            onclick="confirmDeleteWithBackup({
                                                url: '{{ route('employees.destroy', $emp->employee_id) }}',
                                                title: '{{ addslashes($emp->full_name) }} ({{ $emp->email }})',
                                                type: 'Employee',
                                                exportType: 'employees'
                                            })"
                                            class="px-2.5 py-1.5 rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-300 dark:hover:bg-rose-900/60 font-semibold text-xs transition inline-flex items-center gap-1 shadow-sm cursor-pointer"
                                            title="{{ __('Delete Employee') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>{{ __('Delete') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">{{ __('No employees registered.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @include('components.share.pagination', ['paginator' => $employees])
    </div>
</div>

<!-- Minimal Modal: View Employee Profile & Details -->
<div id="employeeDetailModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all animate-in fade-in zoom-in-95">
        
        <!-- Minimal Header -->
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img id="empModalImage" src="{{ \App\Models\Employee::DEFAULT_AVATAR }}" alt="Employee Profile" class="w-11 h-11 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-sm flex-shrink-0 bg-slate-100" onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 id="empModalName" class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-tight truncate">Employee Name</h3>
                        <span id="empModalId" class="text-xs font-mono px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">#0</span>
                    </div>
                    <p id="empModalEmail" class="text-xs text-slate-400 dark:text-slate-400 mt-0.5 truncate">email@example.com</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 flex-shrink-0">
                <span id="empModalStatusBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                    <span id="empModalStatusDot" class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span id="empModalStatusText">{{ __('Active') }}</span>
                </span>
                <button type="button" onclick="closeModal('employeeDetailModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-5 space-y-3.5 text-xs text-slate-700 dark:text-slate-200">
            
            <!-- Organization & Role Grid (2 Columns) -->
            <div class="grid grid-cols-2 gap-2.5">
                <!-- Department Card -->
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <div class="text-xs uppercase font-bold text-slate-400 dark:text-slate-500 flex items-center gap-1 mb-1">
                        <span>🏢</span> {{ __('Department') }}
                    </div>
                    <div id="empModalDept" class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">Technology</div>
                    <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate">{{ __('Head') }}: <span id="empModalDeptHead" class="font-medium text-slate-600 dark:text-slate-300">Manager Name</span></div>
                </div>

                <!-- Role Card -->
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                    <div class="text-xs uppercase font-bold text-slate-400 dark:text-slate-500 flex items-center gap-1 mb-1">
                        <span>🛡️</span> {{ __('Role & Access') }}
                    </div>
                    <div id="empModalRole" class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">Senior Engineer</div>
                    <div class="mt-0.5">
                        <span id="empModalTierBadge" class="inline-flex px-1.5 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/50 dark:border-amber-800/50">Level 3</span>
                    </div>
                </div>
            </div>

            <!-- Working Hours & Shift Schedule -->
            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                        ⏰
                    </div>
                    <div class="min-w-0">
                        <div id="empModalShiftName" class="font-semibold text-slate-800 dark:text-slate-200 truncate">Standard Morning</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5 flex-wrap">
                            <span id="empModalShiftTime" class="font-mono text-slate-700 dark:text-slate-300">08:00 – 17:00</span>
                            <span>&bull;</span>
                            <span>{{ __('Grace') }}: <strong id="empModalGrace" class="text-slate-700 dark:text-slate-300">15</strong> {{ __('mins') }}</span>
                        </div>
                    </div>
                </div>
                <span id="empModalFlexBadge" class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex-shrink-0">
                    {{ __('Fixed Schedule') }}
                </span>
            </div>

            <!-- Tenure & Service (Hire Date & Service Length) -->
            <div class="p-2.5 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/40 flex items-center justify-between text-xs">
                <div>
                    <span class="text-xs uppercase font-bold text-indigo-500 dark:text-indigo-400 block">{{ __('Hire Date') }}</span>
                    <span id="empModalHireDate" class="font-semibold text-slate-800 dark:text-slate-200">Oct 12, 2022</span>
                </div>
                <div class="text-right">
                    <span class="text-xs uppercase font-bold text-indigo-500 dark:text-indigo-400 block">{{ __('Continuous Service') }}</span>
                    <span id="empModalServiceLength" class="font-extrabold text-indigo-700 dark:text-indigo-300">2 yrs 4 mo</span>
                </div>
            </div>

            <!-- Leave & Permission Balance Strip (Remaining, Used, Total) -->
            <div>
                <div class="text-xs uppercase font-bold text-slate-400 dark:text-slate-500 mb-1.5 flex items-center justify-between">
                    <span>{{ __('Annual Leave & Statutory Entitlements') }}</span>
                    <a href="{{ route('dashboard.leave-policies') }}" class="text-teal-600 dark:text-teal-400 hover:underline text-xs font-medium lowercase">{{ __('labor policy rules') }} &rarr;</a>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-800/40 rounded-xl p-2">
                        <span class="text-xs uppercase font-semibold text-emerald-700 dark:text-emerald-400 block">{{ __('Remaining') }}</span>
                        <span id="empModalRemainingLeave" class="text-base font-black text-emerald-700 dark:text-emerald-300">18.0d</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl p-2">
                        <span class="text-xs uppercase font-semibold text-slate-400 dark:text-slate-400 block">{{ __('Approved Used') }}</span>
                        <span id="empModalUsedLeave" class="text-base font-bold text-rose-600 dark:text-rose-400">0d</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl p-2">
                        <span class="text-xs uppercase font-semibold text-slate-400 dark:text-slate-400 block">{{ __('Total Entitled') }}</span>
                        <span id="empModalTotalLeave" class="text-base font-black text-slate-900 dark:text-white">18.0d</span>
                    </div>
                </div>
                <div class="mt-1.5 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 px-1">
                    <span>{{ __('Base') }}: <strong id="empModalBaseLeave" class="text-slate-700 dark:text-slate-300">18.0d</strong></span>
                    <span>{{ __('Seniority') }}: <strong id="empModalSeniorityLeave" class="text-teal-600 dark:text-teal-400">+0d</strong></span>
                    <span>{{ __('Admin Bonus') }}: <strong id="empModalBonusLeave" class="text-indigo-600 dark:text-indigo-400">+0d</strong></span>
                </div>
            </div>

            <!-- Notes Container -->
            <div id="empModalNotesContainer" class="hidden">
                <span class="text-xs uppercase font-bold text-slate-400 dark:text-slate-500 block mb-1">{{ __('Permission Notes') }}</span>
                <div id="empModalNotes" class="p-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 rounded-lg text-xs text-slate-600 dark:text-slate-300 font-mono whitespace-pre-wrap max-h-16 overflow-y-auto"></div>
            </div>

        </div>

        <!-- Minimal Footer -->
        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <a href="{{ route('dashboard.leaves') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ __('View Leave Requests') }}</span>
            </a>
            <button type="button" onclick="closeModal('employeeDetailModal')" class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-semibold rounded-xl transition">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

<script>
function showEmployeeDetail(emp) {
    const avatarEl = document.getElementById('empModalImage');
    if (avatarEl) {
        avatarEl.src = emp.image || '{{ \App\Models\Employee::DEFAULT_AVATAR }}';
    }
    document.getElementById('empModalName').innerText = emp.name;
    document.getElementById('empModalId').innerText = '#' + emp.id;
    document.getElementById('empModalEmail').innerText = emp.email;

    // Status Badge
    const statusText = document.getElementById('empModalStatusText');
    const statusDot = document.getElementById('empModalStatusDot');
    const statusBadge = document.getElementById('empModalStatusBadge');
    if (emp.is_active) {
        statusText.innerText = '{{ __('Active') }}';
        statusDot.className = 'w-1.5 h-1.5 rounded-full bg-emerald-500';
        statusBadge.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40';
    } else {
        statusText.innerText = '{{ __('Inactive') }}';
        statusDot.className = 'w-1.5 h-1.5 rounded-full bg-slate-400';
        statusBadge.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700';
    }

    // Organization
    document.getElementById('empModalDept').innerText = emp.department || '{{ __('Unassigned') }}';
    document.getElementById('empModalDeptHead').innerText = emp.department_head || '{{ __('Unassigned') }}';
    document.getElementById('empModalRole').innerText = emp.role || '{{ __('Staff') }}';
    
    // Tier
    const tierBadge = document.getElementById('empModalTierBadge');
    const lvl = emp.access_level || 1;
    let tierName = '{{ __('Staff') }}';
    if (lvl >= 5) tierName = '{{ __('Superadmin') }}';
    else if (lvl >= 4) tierName = '{{ __('Executive') }}';
    else if (lvl >= 3) tierName = '{{ __('Manager') }}';
    else if (lvl >= 2) tierName = '{{ __('Supervisor') }}';
    tierBadge.innerText = '{{ __('Level') }} ' + lvl + ' • ' + tierName;

    // Shift
    document.getElementById('empModalShiftName').innerText = emp.shift_name;
    document.getElementById('empModalShiftTime').innerText = emp.shift_time;
    document.getElementById('empModalGrace').innerText = emp.grace_period;

    const flexBadge = document.getElementById('empModalFlexBadge');
    if (emp.is_flexible) {
        flexBadge.innerText = '{{ __('Flexible') }}';
        flexBadge.className = 'px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/50 flex-shrink-0';
    } else {
        flexBadge.innerText = '{{ __('Fixed Schedule') }}';
        flexBadge.className = 'px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex-shrink-0';
    }

    // Tenure & Service
    document.getElementById('empModalHireDate').innerText = emp.hire_date;
    document.getElementById('empModalServiceLength').innerText = emp.years + ' {{ __('yrs') }} ' + (emp.months % 12) + ' {{ __('mo') }}';

    // Leaves
    document.getElementById('empModalRemainingLeave').innerText = emp.remaining_leave + 'd';
    document.getElementById('empModalUsedLeave').innerText = emp.used_leave + 'd';
    document.getElementById('empModalTotalLeave').innerText = emp.total_leave + 'd';
    document.getElementById('empModalBaseLeave').innerText = emp.base_leave + 'd';
    document.getElementById('empModalSeniorityLeave').innerText = '+' + emp.seniority_leave + 'd';
    document.getElementById('empModalBonusLeave').innerText = '+' + emp.bonus_leave + 'd';

    // Notes
    const notesContainer = document.getElementById('empModalNotesContainer');
    const notesText = document.getElementById('empModalNotes');
    if (emp.notes && emp.notes.trim().length > 0) {
        notesText.innerText = emp.notes.trim();
        notesContainer.classList.remove('hidden');
    } else {
        notesContainer.classList.add('hidden');
    }

    openModal('employeeDetailModal');
}
</script>
@endsection
