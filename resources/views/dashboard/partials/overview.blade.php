@extends('layouts.app')

@section('title', 'Overview — AttendX Dashboard')

@section('content')
    @if(isset($stats))
        @include('components.share.stats')
    @endif

    <div class="space-y-6">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            
            <!-- Live Attendance Feed Card -->
            <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Live Attendance Feed') }}</h3>
                        <p class="text-xs text-slate-400">{{ __('Recent punch activity across the organization') }}</p>
                    </div>
                    <button type="button" onclick="openModal('clockModal')" class="text-xs font-semibold px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        {{ __('+ Punch') }}
                    </button>
                </div>
                
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[500px] text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-4 sm:px-5 py-3">{{ __('Employee') }}</th>
                                <th class="px-3 py-3">{{ __('Event') }}</th>
                                <th class="px-3 py-3">{{ __('Time') }}</th>
                                <th class="px-3 sm:px-5 py-3">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $log->employee->profile_image ?? \App\Models\Employee::DEFAULT_AVATAR }}" alt="{{ $log->employee->full_name ?? 'Employee' }}" class="w-7 h-7 rounded-full object-cover border border-slate-200 flex-shrink-0 bg-slate-100" onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-800 truncate">{{ $log->employee->full_name ?? 'N/A' }}</div>
                                            <div class="text-xs text-slate-400 truncate">{{ $log->employee->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if($log->event_type === 'Clock-In')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">{{ __('Clock-In') }}</span>
                                    @elseif($log->event_type === 'Clock-Out')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">{{ __('Clock-Out') }}</span>
                                    @elseif($log->event_type === 'On-Leave')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">{{ __('On-Leave') }}</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ __($log->event_type) }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($log->event_timestamp)->format('h:i A') }}</div>
                                    <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($log->event_timestamp)->format('M d') }}</div>
                                </td>
                                <td class="px-3 sm:px-5 py-3 whitespace-nowrap">
                                    @if($log->status === 'Verified')
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ __('Verified') }}
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-amber-700">{{ __($log->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">{{ __('No attendance activity recorded yet.') }}</td>
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
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Recent Leave Requests') }}</h3>
                    <p class="text-xs text-slate-400">{{ __('Staff absence pipeline and reviews') }}</p>
                </div>
                <button type="button" onclick="openModal('leaveModal')" class="text-xs font-semibold px-2.5 sm:px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                    {{ __('+ Apply') }}
                </button>
            </div>
            
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[450px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">{{ __('Applicant') }}</th>
                            <th class="px-3 py-3">{{ __('Type') }}</th>
                            <th class="px-3 py-3">{{ __('Dates') }}</th>
                            <th class="px-3 sm:px-5 py-3">{{ __('Status') }}</th>
                            <th class="px-3 sm:px-5 py-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leaveRequests ?? [] as $leave)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <div class="font-semibold text-slate-800">{{ $leave->employee->full_name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">{{ $leave->employee->department->department_name ?? 'General' }}</div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
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
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">{{ __('Approved') }}</span>
                                    @elseif($leave->status === 'Rejected')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">{{ __('Rejected') }}</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">{{ __('Pending') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-5 py-3 whitespace-nowrap text-right">
                                    @if($leave->status === 'Pending')
                                        <div class="inline-flex items-center gap-1">
                                            <form action="{{ route('leave.status', $leave->request_id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="p-1 rounded-lg text-emerald-600 hover:bg-emerald-50 transition" title="{{ __('Approve') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('leave.status', $leave->request_id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="p-1 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="{{ __('Reject') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('dashboard.leaves') }}" class="text-xs font-semibold text-slate-400 hover:text-brand-600 transition inline-flex items-center gap-0.5">
                                            <span>{{ __('Review') }}</span>
                                            <span>&rarr;</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">{{ __('No leave requests found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
