@extends('layouts.app')

@section('title', 'Employees Directory — AttendX Dashboard')

@section('content')
<div class="space-y-4">
    <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header with Search -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Employee Directory</h3>
                <p class="text-xs text-slate-400">All registered workforce members ({{ $employees->count() }})</p>
            </div>
            <!-- Search Bar & Add Button -->
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" onkeyup="filterTable(this, 'employeeDirectoryTable')" placeholder="Search employees..." class="w-full text-xs pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                <button type="button" onclick="openModal('employeeModal')" class="inline-flex items-center justify-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Employee</span>
                </button>
            </div>
        </div>

        <!-- Responsive Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-xs" id="employeeDirectoryTable">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">Employee</th>
                        <th class="px-4 py-3.5">Department</th>
                        <th class="px-4 py-3.5">Role</th>
                        <th class="px-4 py-3.5">Shift Pattern</th>
                        <th class="px-4 py-3.5">Hire Date</th>
                        <th class="px-4 sm:px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                        {{ substr($emp->full_name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 truncate">{{ $emp->full_name }}</div>
                                        <div class="text-[11px] text-slate-400 truncate">{{ $emp->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                    {{ $emp->department->department_name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-brand-50 text-brand-700 border border-brand-200">
                                    {{ $emp->role->role_name ?? 'Staff' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $emp->shiftPattern->shift_name ?? 'Standard' }}</div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $emp->shiftPattern ? substr($emp->shiftPattern->start_time, 0, 5) . ' - ' . substr($emp->shiftPattern->end_time, 0, 5) : '08:00 - 17:00' }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($emp->hire_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                @if($emp->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">No employees registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
