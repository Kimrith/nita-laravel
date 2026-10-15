@extends('layouts.app')

@section('title', 'Organization & Shifts — AttendX Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ __('Organization & Workforce Policies') }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ __('Manage operational departments, hierarchical job roles, and working shift schedules') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Add Department Button -->
            <button type="button" onclick="openModal('departmentModal')" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm hover:shadow-indigo-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ __('Add Department') }}</span>
            </button>

            <!-- Add Role Button -->
            <button type="button" onclick="openModal('roleModal')" class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm hover:shadow-amber-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ __('Add Role') }}</span>
            </button>

            <!-- Add Shift Pattern Button -->
            <button type="button" onclick="openModal('shiftPatternModal')" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm hover:shadow-emerald-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ __('Add Shift Pattern') }}</span>
            </button>
        </div>
    </div>

    <!-- Quick Metrics Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    🏢
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Departments') }}</div>
                    <div class="text-lg font-bold text-slate-800">{{ $departments->count() }} <span class="text-xs font-normal text-slate-400">{{ __('active units') }}</span></div>
                </div>
            </div>
            <button type="button" onclick="openModal('departmentModal')" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold p-1 hover:bg-indigo-50 rounded-lg transition">{{ __('+ Add') }}</button>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    🛡️
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Job Roles') }}</div>
                    <div class="text-lg font-bold text-slate-800">{{ isset($roles) ? $roles->count() : 0 }} <span class="text-xs font-normal text-slate-400">{{ __('positions') }}</span></div>
                </div>
            </div>
            <button type="button" onclick="openModal('roleModal')" class="text-xs text-amber-600 hover:text-amber-800 font-semibold p-1 hover:bg-amber-50 rounded-lg transition">{{ __('+ Add') }}</button>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    ⏰
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Shift Patterns') }}</div>
                    <div class="text-lg font-bold text-slate-800">{{ $shiftPatterns->count() }} <span class="text-xs font-normal text-slate-400">{{ __('schedules') }}</span></div>
                </div>
            </div>
            <button type="button" onclick="openModal('shiftPatternModal')" class="text-xs text-emerald-600 hover:text-emerald-800 font-semibold p-1 hover:bg-emerald-50 rounded-lg transition">{{ __('+ Add') }}</button>
        </div>
    </div>

    <!-- Main Grid: Departments & Roles Side-by-Side -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        <!-- Departments Card -->
        <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Departments') }}</h3>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                            {{ $departments->count() }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ __('Assigned department heads & current staff counts') }}</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="text" onkeyup="filterTable(this, 'departmentsTable')" placeholder="{{ __('Filter departments...') }}" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition w-36 sm:w-44">
                    <button type="button" onclick="openModal('departmentModal')" class="text-xs font-semibold px-2.5 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition whitespace-nowrap">
                        + {{ __('New') }}
                    </button>
                </div>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[400px] text-left text-sm" id="departmentsTable">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">{{ __('Department') }}</th>
                            <th class="px-4 py-3">{{ __('Department Head') }}</th>
                            <th class="px-4 sm:px-5 py-3">{{ __('Staff Enrolled') }}</th>
                            <th class="px-4 sm:px-5 py-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($departments as $dept)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <div class="font-semibold text-slate-800">{{ $dept->department_name }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    @if($dept->manager)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $dept->manager->profile_image }}" alt="{{ $dept->manager->full_name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200 flex-shrink-0" onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                                            <span class="font-medium text-slate-800">{{ $dept->manager->full_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">{{ __('Unassigned') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $dept->employees_count }} {{ __('staff') }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-right whitespace-nowrap">
                                    <button type="button" 
                                            onclick="confirmDeleteWithBackup({
                                                url: '{{ route('departments.destroy', $dept->department_id) }}',
                                                title: 'Department: {{ addslashes($dept->department_name) }}',
                                                type: 'Department',
                                                exportType: 'organization'
                                            })"
                                            class="px-2 py-1 rounded-lg text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-300 text-xs font-semibold inline-flex items-center gap-1 transition shadow-2xs cursor-pointer"
                                            title="{{ __('Delete Department') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>{{ __('Delete') }}</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">{{ __('No departments configured yet. Click "+ Add Department" to create one.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Roles & Permissions Card -->
        <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Job Roles & Access') }}</h3>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700">
                            {{ isset($roles) ? $roles->count() : 0 }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ __('Job positions and authorization access levels') }}</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="text" onkeyup="filterTable(this, 'rolesTable')" placeholder="{{ __('Filter roles...') }}" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition w-36 sm:w-44">
                    <button type="button" onclick="openModal('roleModal')" class="text-xs font-semibold px-2.5 py-1.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 transition whitespace-nowrap">
                        + {{ __('New') }}
                    </button>
                </div>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[400px] text-left text-sm" id="rolesTable">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">{{ __('Role Position') }}</th>
                            <th class="px-4 py-3">{{ __('Access Level') }}</th>
                            <th class="px-4 sm:px-5 py-3">{{ __('Assigned Staff') }}</th>
                            <th class="px-4 sm:px-5 py-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($roles ?? [] as $role)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 sm:px-5 py-3 font-semibold text-slate-800">{{ $role->role_name }}</td>
                                <td class="px-4 py-3">
                                    @if($role->access_level >= 5)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            {{ __('Level') }} {{ $role->access_level }} &bull; {{ __('Superadmin') }}
                                        </span>
                                    @elseif($role->access_level >= 4)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                            {{ __('Level') }} {{ $role->access_level }} &bull; {{ __('Executive') }}
                                        </span>
                                    @elseif($role->access_level >= 3)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ __('Level') }} {{ $role->access_level }} &bull; {{ __('Manager') }}
                                        </span>
                                    @elseif($role->access_level >= 2)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ __('Level') }} {{ $role->access_level }} &bull; {{ __('Supervisor') }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                            {{ __('Level') }} {{ $role->access_level }} &bull; {{ __('Staff') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $role->employees_count }} {{ __('staff') }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-right whitespace-nowrap">
                                    <button type="button" 
                                            onclick="confirmDeleteWithBackup({
                                                url: '{{ route('roles.destroy', $role->role_id) }}',
                                                title: 'Role: {{ addslashes($role->role_name) }}',
                                                type: 'Job Role',
                                                exportType: 'organization'
                                            })"
                                            class="px-2 py-1 rounded-lg text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-300 text-xs font-semibold inline-flex items-center gap-1 transition shadow-2xs cursor-pointer"
                                            title="{{ __('Delete Role') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>{{ __('Delete') }}</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">{{ __('No roles configured yet. Click "+ Add Role" to create one.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Shift Patterns Card (Full Width) -->
    <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Shift Patterns & Working Hours') }}</h3>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                        {{ $shiftPatterns->count() }} {{ __('Schedules') }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('Work timing policies, arrival grace windows, and schedule flexibility') }}</p>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="text" onkeyup="filterTable(this, 'shiftsTable')" placeholder="{{ __('Filter shifts...') }}" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition w-36 sm:w-48">
                <button type="button" onclick="openModal('shiftPatternModal')" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition whitespace-nowrap">
                    + {{ __('Add Shift Pattern') }}
                </button>
            </div>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[550px] text-left text-sm" id="shiftsTable">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3">{{ __('Shift Name') }}</th>
                        <th class="px-4 py-3">{{ __('Working Hours') }}</th>
                        <th class="px-4 py-3">{{ __('Grace Tolerance') }}</th>
                        <th class="px-4 py-3">{{ __('Flexibility') }}</th>
                        <th class="px-4 sm:px-5 py-3">{{ __('Assigned Staff') }}</th>
                        <th class="px-4 sm:px-5 py-3 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shiftPatterns as $shift)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3 font-semibold text-slate-800">{{ $shift->shift_name }}</td>
                            <td class="px-4 py-3 font-mono text-slate-700 whitespace-nowrap">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-xs font-semibold text-slate-800">
                                    {{ substr($shift->start_time, 0, 5) }} &ndash; {{ substr($shift->end_time, 0, 5) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                <span class="font-medium text-slate-700">{{ $shift->grace_period_mins }}</span> {{ __('mins') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($shift->is_flexible)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ __('Flexible') }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        {{ __('Fixed Schedule') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $shift->employees_count }} {{ __('staff') }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-right whitespace-nowrap">
                                <button type="button" 
                                        onclick="confirmDeleteWithBackup({
                                            url: '{{ route('shift-patterns.destroy', $shift->shift_pattern_id) }}',
                                            title: 'Shift: {{ addslashes($shift->shift_name) }}',
                                            type: 'Shift Schedule',
                                            exportType: 'organization'
                                        })"
                                        class="px-2 py-1 rounded-lg text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-300 text-xs font-semibold inline-flex items-center gap-1 transition shadow-2xs cursor-pointer"
                                        title="{{ __('Delete Shift') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>{{ __('Delete') }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">{{ __('No shift schedules configured yet. Click "+ Add Shift Pattern" to create one.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
