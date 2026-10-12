@extends('layouts.app')

@section('title', 'Leave Requests — AttendX Dashboard')

@section('content')
<div class="space-y-4">
    <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header with Action -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Leave Requests & Absences</h3>
                <p class="text-xs text-slate-400">Formal applications, duration, and supervisory approval status</p>
            </div>
            <button type="button" onclick="openModal('leaveModal')" class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Request Leave</span>
            </button>
        </div>

        <!-- Responsive Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">Req ID</th>
                        <th class="px-4 py-3.5">Employee</th>
                        <th class="px-4 py-3.5">Leave Type</th>
                        <th class="px-4 py-3.5">Start Date</th>
                        <th class="px-4 py-3.5">End Date</th>
                        <th class="px-4 py-3.5">Approver</th>
                        <th class="px-4 py-3.5">Submitted</th>
                        <th class="px-4 sm:px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaveRequests as $req)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5 font-mono text-[11px] text-slate-400">#{{ $req->request_id }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900">{{ $req->employee->full_name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $req->employee->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $req->leaveType->type_name ?? 'Leave' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-800 font-medium whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->start_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-800 font-medium whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->end_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                {{ $req->approver->full_name ?? 'Pending Assignment' }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->submitted_date)->diffForHumans() }}
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                @if($req->status === 'Approved')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                                @elseif($req->status === 'Rejected')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">Rejected</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400">No leave applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
