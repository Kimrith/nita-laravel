@extends('layouts.app')

@section('title', 'Overview — AttendX Dashboard')

@section('content')
    <!-- Top KPI Metrics -->
    @if(isset($stats))
        @include('dashboard.partials.stats')
    @endif

    <div class="space-y-6">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            
            <!-- Live Attendance Feed Card -->
            <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Live Attendance Feed</h3>
                        <p class="text-xs text-slate-400">Recent punch activity across the organization</p>
                    </div>
                    <button type="button" onclick="openModal('clockModal')" class="text-xs font-semibold px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        + Punch
                    </button>
                </div>
                
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[500px] text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-4 sm:px-5 py-3">Employee</th>
                                <th class="px-3 py-3">Event</th>
                                <th class="px-3 py-3">Time</th>
                                <th class="px-3 py-3">Device</th>
                                <th class="px-3 sm:px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px] flex-shrink-0">
                                            {{ substr($log->employee->full_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-800 truncate">{{ $log->employee->full_name ?? 'N/A' }}</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $log->employee->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if($log->event_type === 'Clock-In')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">Clock-In</span>
                                    @elseif($log->event_type === 'Clock-Out')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Clock-Out</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">{{ $log->event_type }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($log->event_timestamp)->format('h:i A') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($log->event_timestamp)->format('M d') }}</div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-slate-600 font-mono text-[11px]">{{ $log->source_device }}</span>
                                </td>
                                <td class="px-3 sm:px-5 py-3 whitespace-nowrap">
                                    @if($log->status === 'Verified')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified
                                        </span>
                                    @else
                                        <span class="text-[10px] font-semibold text-amber-700">{{ $log->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">No attendance activity recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Leave Requests Card -->
        <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Recent Leave Requests</h3>
                    <p class="text-xs text-slate-400">Staff absence pipeline and reviews</p>
                </div>
                <button type="button" onclick="openModal('leaveModal')" class="text-xs font-semibold px-2.5 sm:px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                    + Apply
                </button>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[450px] text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Applicant</th>
                            <th class="px-3 py-3">Type</th>
                            <th class="px-3 py-3">Dates</th>
                            <th class="px-3 sm:px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leaveRequests ?? [] as $leave)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <div class="font-semibold text-slate-800">{{ $leave->employee->full_name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $leave->employee->department->department_name ?? 'General' }}</div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $leave->leaveType->type_name ?? 'Leave' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-slate-700 font-medium">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-5 py-3 whitespace-nowrap">
                                    @if($leave->status === 'Approved')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                                    @elseif($leave->status === 'Rejected')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-800">Rejected</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400">No leave requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
