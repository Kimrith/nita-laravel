@extends('layouts.app')

@section('title', 'Attendance Logs — AttendX Dashboard')

@section('content')
<div class="space-y-4">
    <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header with Action -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Attendance Log History</h3>
                <p class="text-xs text-slate-400">Complete record of check-in, check-out, and break intervals</p>
            </div>
            <button type="button" onclick="openModal('clockModal')" class="inline-flex items-center justify-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Record Punch</span>
            </button>
        </div>

        <!-- Responsive Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">Log ID</th>
                        <th class="px-4 py-3.5">Staff Member</th>
                        <th class="px-4 py-3.5">Timestamp</th>
                        <th class="px-4 py-3.5">Event Type</th>
                        <th class="px-4 py-3.5">Source Device</th>
                        <th class="px-4 py-3.5">Location / Data</th>
                        <th class="px-4 sm:px-5 py-3.5">Verification</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5 font-mono text-[11px] text-slate-400">
                                #{{ $log->log_id }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900">{{ $log->employee->full_name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->employee->department->department_name ?? 'Staff' }}</div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($log->event_timestamp)->format('Y-m-d H:i:s') }}</div>
                                <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($log->event_timestamp)->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($log->event_type === 'Clock-In')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">Clock-In</span>
                                @elseif($log->event_type === 'Clock-Out')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">Clock-Out</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800">{{ $log->event_type }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-mono bg-slate-100 text-slate-700">
                                    {{ $log->source_device }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($log->location_data)
                                    <span class="inline-block font-mono text-[10px] bg-slate-50 border border-slate-200 px-2 py-0.5 rounded text-slate-600">
                                        @if(isset($log->location_data['latitude']))
                                            Lat: {{ round($log->location_data['latitude'], 3) }}, Lng: {{ round($log->location_data['longitude'], 3) }}
                                        @else
                                            IP: {{ $log->location_data['ip'] ?? 'Local' }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                @if($log->status === 'Verified')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified
                                    </span>
                                @elseif($log->status === 'Invalid')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700">Invalid</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
