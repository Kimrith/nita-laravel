@extends('layouts.app')

@section('title', 'Organization & Shifts — AttendX Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        <!-- Departments Card -->
        <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Departments</h3>
                    <p class="text-xs text-slate-400">Department managers and current staff headcount</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                    {{ $departments->count() }} Units
                </span>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[400px] text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Department Name</th>
                            <th class="px-4 py-3">Manager</th>
                            <th class="px-4 sm:px-5 py-3">Staff Enrolled</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($departments as $dept)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 sm:px-5 py-3 font-semibold text-slate-800">{{ $dept->department_name }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $dept->manager->full_name ?? '—' }}
                                </td>
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ $dept->employees_count }} staff
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-400">No departments configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Shift Patterns Card -->
        <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Shift Patterns</h3>
                    <p class="text-xs text-slate-400">Configured shift timing & grace policies</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                    {{ $shiftPatterns->count() }} Schedules
                </span>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[450px] text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Shift Name</th>
                            <th class="px-4 py-3">Working Hours</th>
                            <th class="px-4 py-3">Grace</th>
                            <th class="px-4 sm:px-5 py-3">Flexibility</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($shiftPatterns as $shift)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 sm:px-5 py-3 font-semibold text-slate-800">{{ $shift->shift_name }}</td>
                                <td class="px-4 py-3 font-mono text-slate-700 whitespace-nowrap">
                                    {{ substr($shift->start_time, 0, 5) }} &ndash; {{ substr($shift->end_time, 0, 5) }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $shift->grace_period_mins }} mins</td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @if($shift->is_flexible)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">Flexible</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">Fixed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">No shift schedules configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
