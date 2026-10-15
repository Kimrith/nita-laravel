@extends('layouts.app')

@section('title', 'Attendance Logs — AttendX Dashboard')

@section('content')
<div class="space-y-4">
    <div class="w-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header with Action -->
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Attendance Log History') }}</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('Complete record of check-in, check-out, and break intervals') }} ({{ $recentLogs->total() }})</p>
            </div>
            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <form method="GET" action="{{ route('dashboard.attendance') }}" class="relative flex-1 sm:w-72">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           onkeyup="filterTable(this, 'attendanceTable')" 
                           placeholder="{{ __('Search attendance logs...') }}" 
                           class="w-full text-xs pl-9 pr-8 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @if(request('search'))
                        <a href="{{ route('dashboard.attendance') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5" title="{{ __('Clear search') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
                <button type="button" onclick="openModal('clockModal')" class="inline-flex items-center justify-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('Record Punch') }}</span>
                </button>
            </div>
        </div>

        <!-- Responsive Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm" id="attendanceTable">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Log ID') }}</th>
                        <th class="px-4 py-3.5">{{ __('Staff Member') }}</th>
                        <th class="px-4 py-3.5">{{ __('Timestamp') }}</th>
                        <th class="px-4 py-3.5">{{ __('Event Type') }}</th>
                        <th class="px-4 py-3.5">{{ __('Location / Data') }}</th>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Verification') }}</th>
                        <th class="px-4 sm:px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5 font-mono text-xs text-slate-400">
                                #{{ $log->log_id }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $log->employee->profile_image ?? \App\Models\Employee::DEFAULT_AVATAR }}" alt="{{ $log->employee->full_name ?? 'Employee' }}" class="w-7 h-7 rounded-full object-cover border border-slate-200 flex-shrink-0 bg-slate-100" onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 truncate">{{ $log->employee->full_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-400 truncate">{{ $log->employee->department->department_name ?? 'Staff' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($log->event_timestamp)->format('Y-m-d H:i:s') }}</div>
                                <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($log->event_timestamp)->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($log->event_type === 'Clock-In')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">{{ __('Clock-In') }}</span>
                                @elseif($log->event_type === 'Clock-Out')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">{{ __('Clock-Out') }}</span>
                                @elseif($log->event_type === 'On-Leave')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> {{ __('On-Leave') }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ __($log->event_type) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($log->location_data)
                                    @php
                                        $loc = $log->location_data;
                                        if ($loc && (!isset($loc['latitude']) || !isset($loc['longitude']) || !is_numeric($loc['latitude']))) {
                                            $resolved = \App\Services\GeoLocationService::resolveLocation($loc['ip'] ?? null);
                                            $loc = array_merge($loc, $resolved);
                                        }
                                        $hasCoords = isset($loc['latitude'], $loc['longitude']) && is_numeric($loc['latitude']) && is_numeric($loc['longitude']);
                                        $address = $loc['address'] ?? ($hasCoords ? \App\Services\GeoLocationService::getShortAddress($loc['latitude'], $loc['longitude']) : null);
                                    @endphp
                                    @if($hasCoords)
                                        <a href="https://www.google.com/maps?q={{ $loc['latitude'] }},{{ $loc['longitude'] }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           title="{{ $loc['full_address'] ?? $address ?? 'View on Google Maps' }} (Click to open map)"
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-50 hover:bg-indigo-50/80 border border-slate-200/80 hover:border-indigo-300 text-slate-700 hover:text-indigo-700 transition shadow-2xs group">
                                            <svg class="w-3.5 h-3.5 text-rose-500 group-hover:scale-110 transition-transform flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="max-w-[190px] truncate">
                                                {{ $address ?: 'Lat: ' . round($loc['latitude'], 3) . ', Lng: ' . round($loc['longitude'], 3) }}
                                            </span>
                                            <svg class="w-2.5 h-2.5 text-slate-400 group-hover:text-indigo-500 transition-colors flex-shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="inline-block font-mono text-xs bg-slate-50 border border-slate-200 px-2 py-0.5 rounded text-slate-600">
                                            IP: {{ $loc['ip'] ?? 'Local' }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                @if($log->status === 'Verified')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ __('Verified') }}
                                    </span>
                                @elseif($log->status === 'Invalid')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> {{ __('Invalid') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> {{ __('Pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if($log->status === 'Pending')
                                        <!-- Verify Form Button -->
                                        <form action="{{ route('attendance.status', $log->log_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow-emerald-500/20 transition" title="{{ __('Verify') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>{{ __('Verify') }}</span>
                                            </button>
                                        </form>

                                        <!-- Mark Invalid Form Button -->
                                        <form action="{{ route('attendance.status', $log->log_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Invalid">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 hover:border-rose-300 shadow-sm transition" title="{{ __('Mark Invalid') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>{{ __('Invalid') }}</span>
                                            </button>
                                        </form>
                                    @elseif($log->status === 'Verified')
                                        <!-- Invalidate Form Button -->
                                        <form action="{{ route('attendance.status', $log->log_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Invalid">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="{{ __('Mark Invalid') }}">
                                                <svg class="w-3 h-3 text-rose-500 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>{{ __('Invalid') }}</span>
                                            </button>
                                        </form>

                                        <!-- Reset to Pending -->
                                        <form action="{{ route('attendance.status', $log->log_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Pending">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="{{ __('Reset') }}">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                <span>{{ __('Reset') }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Verify Form Button -->
                                        <form action="{{ route('attendance.status', $log->log_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="{{ __('Verify') }}">
                                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>{{ __('Verify') }}</span>
                                            </button>
                                        </form>

                                        <!-- Reset to Pending -->
                                        <form action="{{ route('attendance.status', $log->log_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Pending">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="{{ __('Reset') }}">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                <span>{{ __('Reset') }}</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Attendance Log Button with Backup Warning -->
                                    <button type="button" 
                                            onclick="confirmDeleteWithBackup({
                                                url: '{{ route('attendance.destroy', $log->log_id) }}',
                                                title: 'Attendance #{{ $log->log_id }} — {{ addslashes($log->employee->full_name ?? 'Employee') }} ({{ $log->event_type }} at {{ $log->event_timestamp }})',
                                                type: 'Attendance Log',
                                                exportType: 'attendance'
                                            })"
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-300 transition cursor-pointer shadow-2xs" 
                                            title="{{ __('Delete Attendance Log') }}">
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
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">{{ __('No logs found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @include('components.share.pagination', ['paginator' => $recentLogs])
    </div>
</div>
@endsection
