@extends('layouts.app')

@section('title', 'Leave Requests — AttendX Dashboard')

@section('content')
<div class="space-y-4">

    <!-- Categories Strip -->
    @if(isset($leaveTypes) && $leaveTypes->count() > 0)
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 overflow-x-auto py-0.5">
                <span class="text-xs font-bold text-slate-700 flex-shrink-0 mr-1">{{ __('Leave Policies') }}:</span>
                @foreach($leaveTypes as $type)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $type->is_paid ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'bg-slate-100 text-slate-700' }} flex-shrink-0">
                        <span>{{ $type->type_name }}</span>
                        <span class="text-xs font-normal opacity-75">({{ $type->is_paid ? __('Paid') : __('Unpaid') }})</span>
                    </span>
                @endforeach
            </div>
            @if($isAdmin)
                <button type="button" onclick="openModal('leaveTypeModal')" class="text-xs text-teal-600 hover:text-teal-800 font-semibold p-1 hover:bg-teal-50 rounded-lg transition whitespace-nowrap self-start sm:self-auto">
                    + {{ __('Add Leave Category') }}
                </button>
            @endif
        </div>
    @endif

    <!-- Cambodia Labor Law Statutory Permissions Overview Strip (Minimal UI) -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-4.5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 transition hover:border-slate-300 dark:hover:border-slate-700">
        <div class="space-y-1.5 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 text-xs font-semibold border border-teal-200/60 dark:border-teal-800/40">
                <span>🇰🇭</span>
                <span>{{ __('Cambodia Labor Law Statutory Permissions') }}</span>
                <span class="opacity-40">•</span>
                <span class="font-normal opacity-90">{{ __('Articles 166-171, 182-183') }}</span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ __('Employees are entitled to 18 days of paid annual leave per year (1.5 days/month after 1 year of service), increasing by +1 day for every 3 years of continuous service. Plus 7 days special leave, 90 days maternity (50%), and 21-22 public holidays.') }}
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('dashboard.leave-policies') }}" class="inline-flex items-center gap-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 dark:bg-teal-950/60 dark:hover:bg-teal-900/60 dark:text-teal-300 text-xs font-semibold px-3 py-2 rounded-xl transition border border-teal-200/80 dark:border-teal-800/50 whitespace-nowrap shadow-xs">
                <svg class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>{{ __('View Policy Handbook') }}</span>
            </a>
            <button type="button" onclick="switchLeaveTab('balances'); document.getElementById('leaveUnifiedContainer').scrollIntoView({behavior: 'smooth'});" class="inline-flex items-center gap-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-xs font-medium px-3 py-2 rounded-xl transition border border-slate-200 dark:border-slate-700 whitespace-nowrap">
                <span>{{ __('Employee Balances') }}</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs font-bold bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-300">{{ $employees->count() }}</span>
            </button>
        </div>
    </div>

    <!-- Single Unified Table Container with Tabs -->
    <div id="leaveUnifiedContainer" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Header with Segmented Tabs & Contextual Action Controls -->
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Segmented Tab Controls -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="inline-flex items-center p-1 bg-slate-100 dark:bg-slate-800/80 rounded-xl border border-slate-200/60 dark:border-slate-700/60 shadow-2xs">
                    <button type="button" 
                            id="tab-btn-requests" 
                            onclick="switchLeaveTab('requests')" 
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <svg class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>{{ __('Leave Requests & Absences') }}</span>
                        <span id="tab-badge-requests" class="px-1.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/40">
                            {{ $leaveRequests->total() }}
                        </span>
                    </button>
                    <button type="button" 
                            id="tab-btn-balances" 
                            onclick="switchLeaveTab('balances')" 
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-medium transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>{{ __('Employee Statutory Balances & Permissions') }}</span>
                        <span id="tab-badge-balances" class="px-1.5 py-0.5 rounded-full text-xs font-bold bg-slate-200/70 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                            {{ $employees->count() }}
                        </span>
                    </button>
                </div>

                <!-- Contextual Subtitle -->
                <p id="tabSubtitle" class="text-xs text-slate-400 dark:text-slate-500 hidden sm:block">
                    {{ __('Formal applications, duration, and supervisory approval status') }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 w-full md:w-auto">
                <!-- Search Bar for Leaves and Staff Balances -->
                <form method="GET" action="{{ route('dashboard.leaves') }}" id="leaveSearchForm" class="relative flex-1 sm:w-64" onsubmit="const ti = document.getElementById('leaveTabInput'); if(ti) ti.value = currentLeaveTab;">
                    <input type="hidden" name="tab" id="leaveTabInput" value="{{ request('tab', 'requests') }}">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" 
                           name="search" 
                           id="leaveSearchInput"
                           value="{{ request('search') }}" 
                           onkeyup="filterActiveLeaveTable(this)" 
                           placeholder="{{ __('Search leaves...') }}" 
                           class="w-full text-xs pl-9 pr-8 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition">
                    @if(request('search'))
                        <a href="{{ route('dashboard.leaves', request('tab') ? ['tab' => request('tab')] : []) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5" title="{{ __('Clear search') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>

                <!-- Contextual Actions: Leave Requests Tab -->
                <div id="tab-actions-requests" class="flex flex-wrap items-center gap-2">
                    @if($isAdmin)
                        <button type="button" onclick="openModal('leaveTypeModal')" class="inline-flex items-center justify-center gap-1.5 bg-teal-50 hover:bg-teal-100 dark:bg-teal-950/60 dark:hover:bg-teal-900/60 text-teal-700 dark:text-teal-300 text-xs font-semibold px-3 py-2 rounded-xl transition border border-teal-200/60 dark:border-teal-800/40 shadow-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ __('Add Leave Category') }}</span>
                        </button>
                    @endif
                    <button type="button" onclick="openModal('leaveModal')" class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition shadow-xs hover:shadow-emerald-500/25 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>{{ __('Request Leave') }}</span>
                    </button>
                </div>

                <!-- Contextual Actions: Balances Tab (Hidden by default) -->
                <div id="tab-actions-balances" class="hidden flex flex-wrap items-center gap-2">
                    <a href="{{ route('dashboard.leave-policies') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-700 dark:text-teal-300 bg-teal-50 hover:bg-teal-100 dark:bg-teal-950/60 dark:hover:bg-teal-900/60 px-3 py-2 rounded-xl transition border border-teal-200/60 dark:border-teal-800/40 shadow-xs whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ __('Labor Law Rules') }}</span>
                    </a>
                    @if($isAdmin)
                        <button type="button" onclick="openModal('employeeModal')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 px-3.5 py-2 rounded-xl transition shadow-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ __('New Employee') }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab Content 1: Leave Requests Table -->
        <div id="tab-content-requests" class="w-full">

        <!-- Responsive Table Container -->
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm" id="leaveRequestsTable">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Req ID') }}</th>
                        <th class="px-4 py-3.5 whitespace-nowrap min-w-[200px]">{{ __('Employee') }}</th>
                        <th class="px-4 py-3.5">{{ __('Leave Type') }}</th>
                        <th class="px-4 py-3.5">{{ __('Start Date') }}</th>
                        <th class="px-4 py-3.5">{{ __('End Date') }}</th>
                        <th class="px-4 py-3.5">{{ __('Location / Data') }}</th>
                        <th class="px-4 py-3.5">{{ __('Approver') }}</th>
                        <th class="px-4 py-3.5">{{ __('Submitted') }}</th>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Status') }}</th>
                        <th class="px-4 sm:px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaveRequests as $req)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5 font-mono text-xs text-slate-400">#{{ $req->request_id }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap min-w-[200px]">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $req->employee->full_name ?? 'N/A' }}</span>
                                    @if($req->last_edited_by && $req->editor)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 shadow-2xs cursor-help flex-shrink-0" 
                                              title="{{ __('Edited by:') }} {{ $req->editor->full_name }} @if($req->last_edited_at) ({{ $req->last_edited_at->diffForHumans() }}) @endif @if($req->edit_reason) — {{ $req->edit_reason }} @endif">
                                            <svg class="w-2.5 h-2.5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span>{{ __('Edited') }}</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400 flex items-center gap-1.5 mt-0.5">
                                    <span>{{ $req->employee->email ?? '' }}</span>
                                    @if($req->last_edited_by && $req->editor)
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">
                                            &bull; {{ __('by') }} <span class="text-slate-500 dark:text-slate-400 font-medium">{{ $req->editor->full_name }}</span>
                                            @if($req->last_edited_at)
                                                <span>({{ $req->last_edited_at->diffForHumans() }})</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $req->leaveType->type_name ?? 'Leave' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-800 font-medium whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->start_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-800 font-medium whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->end_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @php
                                    $loc = $req->location_data;
                                    if (!$loc && $req->employee && $req->employee->attendanceLogs) {
                                        $latestLog = $req->employee->attendanceLogs->first();
                                        $loc = $latestLog?->location_data;
                                    }
                                @endphp
                                @if($loc)
                                    @php
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
                                    <span class="text-slate-300 font-mono text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                {{ $req->approver->full_name ?? 'Pending Assignment' }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->submitted_date)->diffForHumans() }}
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                @if($req->status === 'Approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ __('Approved') }}
                                    </span>
                                @elseif($req->status === 'Rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> {{ __('Rejected') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> {{ __('Pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <!-- Edit Button (Available to both Admin and Employee) -->
                                    <button type="button" 
                                            onclick="openEditLeaveModal({{ json_encode([
                                                'id' => $req->request_id,
                                                'employee_id' => $req->employee_id,
                                                'employee_name' => $req->employee->full_name ?? 'N/A',
                                                'employee_email' => $req->employee->email ?? '',
                                                'leave_type_id' => $req->leave_type_id,
                                                'start_date' => $req->start_date ? $req->start_date->format('Y-m-d') : '',
                                                'end_date' => $req->end_date ? $req->end_date->format('Y-m-d') : '',
                                                'status' => $req->status,
                                                'edit_reason' => $req->edit_reason ?? '',
                                                'last_edited_by' => $req->editor->full_name ?? null,
                                                'last_edited_at' => $req->last_edited_at ? $req->last_edited_at->format('M d, Y H:i') : null,
                                            ]) }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 shadow-2xs transition cursor-pointer" 
                                            title="{{ __('Edit Leave Request') }}">
                                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        <span>{{ __('Edit') }}</span>
                                    </button>

                                    @if($isAdmin)
                                        @if($req->status === 'Pending')
                                            <!-- Approve Form Button -->
                                            <form action="{{ route('leave.status', $req->request_id) }}" method="POST" class="inline">
                                                 @csrf
                                                 @method('PATCH')
                                                 <input type="hidden" name="status" value="Approved">
                                                 <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow-emerald-500/20 transition" title="{{ __('Approve') }}">
                                                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                     </svg>
                                                     <span>{{ __('Approve') }}</span>
                                                 </button>
                                             </form>
     
                                             <!-- Reject Form Button -->
                                             <form action="{{ route('leave.status', $req->request_id) }}" method="POST" class="inline">
                                                 @csrf
                                                 @method('PATCH')
                                                 <input type="hidden" name="status" value="Rejected">
                                                 <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 shadow-sm transition" title="{{ __('Reject') }}">
                                                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                     </svg>
                                                     <span>{{ __('Reject') }}</span>
                                                 </button>
                                             </form>
                                         @elseif($req->status === 'Approved')
                                             <!-- Switch to Reject -->
                                             <form action="{{ route('leave.status', $req->request_id) }}" method="POST" class="inline">
                                                 @csrf
                                                 @method('PATCH')
                                                 <input type="hidden" name="status" value="Rejected">
                                                 <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="{{ __('Reject') }}">
                                                     <span>{{ __('Reject') }}</span>
                                                 </button>
                                             </form>
                                             <!-- Reset to Pending -->
                                             <form action="{{ route('leave.status', $req->request_id) }}" method="POST" class="inline">
                                                 @csrf
                                                 @method('PATCH')
                                                 <input type="hidden" name="status" value="Pending">
                                                 <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="{{ __('Reset') }}">
                                                     <span>{{ __('Reset') }}</span>
                                                 </button>
                                             </form>
                                        @else
                                            <!-- Switch to Approve -->
                                            <form action="{{ route('leave.status', $req->request_id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Switch to Approved">
                                                    <span>Approve</span>
                                                </button>
                                            </form>
                                            <!-- Reset to Pending -->
                                            <form action="{{ route('leave.status', $req->request_id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Pending">
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Reset to Pending">
                                                    <span>Reset</span>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Delete Leave Request Button with Backup Warning -->
                                        <button type="button" 
                                                onclick="confirmDeleteWithBackup({
                                                    url: '{{ route('leaves.destroy', $req->request_id) }}',
                                                    title: 'Leave #{{ $req->request_id }} — {{ addslashes($req->employee->full_name ?? 'Employee') }} ({{ $req->leaveType->type_name ?? 'Leave' }})',
                                                    type: 'Leave Request',
                                                    exportType: 'leaves'
                                                })"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 transition cursor-pointer shadow-2xs" 
                                                title="{{ __('Delete Leave Request') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>{{ __('Delete') }}</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-slate-400">No leave applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            <!-- Pagination Bar -->
            @include('components.share.pagination', ['paginator' => $leaveRequests])
        </div>

        <!-- Tab Content 2: Employee Statutory Balances & Permissions Table (Hidden by default) -->
        <div id="tab-content-balances" class="w-full hidden">
            <!-- Employee Balances Table Container -->
            <div id="employee-permissions-section" class="w-full overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm" id="employeePermissionsTable">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-4 sm:px-5 py-3.5">{{ __('Employee') }}</th>
                        <th class="px-4 py-3.5">{{ __('Hire Date') }}</th>
                        <th class="px-4 py-3.5">{{ __('Continuous Service') }}</th>
                        <th class="px-4 py-3.5">{{ __('Base Statutory') }}</th>
                        <th class="px-4 py-3.5">{{ __('Seniority (+1d/3y)') }}</th>
                        <th class="px-4 py-3.5">{{ __('Granted Bonus') }}</th>
                        <th class="px-4 py-3.5">{{ __('Total Entitled') }}</th>
                        <th class="px-4 py-3.5">{{ __('Approved Used') }}</th>
                        <th class="px-4 py-3.5">{{ __('Remaining Balance') }}</th>
                        <th class="px-4 sm:px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        @continue($emp->email === 'admin@example.com' || ($emp->user && $emp->user->isAdmin()))
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 sm:px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $emp->full_name }}</div>
                                <div class="text-xs text-slate-400">{{ $emp->role->role_name ?? 'Staff' }} • {{ $emp->department->department_name ?? 'General' }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700 whitespace-nowrap">
                                {{ $emp->hire_date ? $emp->hire_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $emp->years_of_service }} {{ __('yrs') }} {{ $emp->months_of_service % 12 }} {{ __('mo') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-slate-800">
                                {{ $emp->base_annual_leave_days }}d
                            </td>
                            <td class="px-4 py-3.5 text-teal-600 font-bold">
                                +{{ $emp->seniority_bonus_days }}d
                            </td>
                            <td class="px-4 py-3.5 text-indigo-600 font-bold">
                                +{{ $emp->additional_leave_days ?? 0 }}d
                            </td>
                            <td class="px-4 py-3.5 font-black text-slate-900">
                                {{ $emp->total_annual_leave_entitlement }}d
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">
                                {{ $emp->used_annual_leave_days }}d
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $emp->remaining_annual_leave_days > 5 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $emp->remaining_annual_leave_days }} {{ __('days left') }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <button type="button" 
                                        onclick="showPermissionDetail({{ json_encode([
                                            'id' => $emp->employee_id,
                                            'name' => $emp->full_name,
                                            'email' => $emp->email,
                                            'image' => $emp->profile_image,
                                            'role' => $emp->role->role_name ?? 'Staff',
                                            'department' => $emp->department->department_name ?? 'General',
                                            'hire_date' => $emp->hire_date ? $emp->hire_date->format('M d, Y') : 'N/A',
                                            'years' => $emp->years_of_service,
                                            'months' => $emp->months_of_service,
                                            'base' => $emp->base_annual_leave_days,
                                            'seniority' => $emp->seniority_bonus_days,
                                            'additional' => $emp->additional_leave_days ?? 0,
                                            'total' => $emp->total_annual_leave_entitlement,
                                            'used' => $emp->used_annual_leave_days,
                                            'remaining' => $emp->remaining_annual_leave_days,
                                            'special' => $emp->special_leave_entitlement,
                                            'maternity' => $emp->maternity_leave_entitlement,
                                            'holidays' => $emp->public_holidays_count,
                                            'notes' => $emp->permission_notes ?? '',
                                        ]) }})"
                                        class="px-2.5 py-1.5 rounded-lg text-teal-700 bg-teal-50 hover:bg-teal-100 font-semibold text-xs transition">
                                    {{ __('View Detail') }}
                                </button>
                                @if($isAdmin)
                                    <button type="button" 
                                            onclick="openIncreasePermissionModal({{ $emp->employee_id }}, '{{ addslashes($emp->full_name) }}', {{ $emp->total_annual_leave_entitlement }})"
                                            class="px-2.5 py-1.5 rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 font-semibold text-xs transition">
                                        + {{ __('Increase') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-6 text-slate-400">
                                {{ __('No employees found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Minimal Modal: View Permission Detail -->
<div id="permissionDetailModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 w-full max-w-sm sm:max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all animate-in fade-in zoom-in-95">
        
        <!-- Minimal Header -->
        <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img id="modalEmpImage" src="{{ \App\Models\Employee::DEFAULT_AVATAR }}" alt="Employee Profile" class="w-9 h-9 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-sm flex-shrink-0 bg-slate-100" onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                <div>
                    <h3 id="modalEmpName" class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Employee Name</h3>
                    <p id="modalEmpSubtitle" class="text-xs text-slate-400 dark:text-slate-400 leading-none mt-0.5">Role • Department</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('permissionDetailModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-5 space-y-3.5 text-xs text-slate-700 dark:text-slate-200">
            
            <!-- Quick Metric Strip: Remaining, Used, Total -->
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-800/40 rounded-xl p-2.5">
                    <span class="text-xs uppercase font-semibold text-emerald-700 dark:text-emerald-400 block">{{ __('Remaining') }}</span>
                    <span id="modalRemainingDays" class="text-base font-black text-emerald-700 dark:text-emerald-300">18.0d</span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl p-2.5">
                    <span class="text-xs uppercase font-semibold text-slate-400 dark:text-slate-400 block">{{ __('Approved Used') }}</span>
                    <span id="modalUsedDays" class="text-base font-bold text-rose-600 dark:text-rose-400">0d</span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl p-2.5">
                    <span class="text-xs uppercase font-semibold text-slate-400 dark:text-slate-400 block">{{ __('Total Entitled') }}</span>
                    <span id="modalTotalDays" class="text-base font-black text-slate-900 dark:text-white">18.0d</span>
                </div>
            </div>

            <!-- Compact Service & Statutory Breakdown -->
            <div class="bg-slate-50/80 dark:bg-slate-800/40 rounded-xl p-3 border border-slate-100 dark:border-slate-800/70 space-y-2">
                <div class="flex items-center justify-between text-xs pb-1.5 border-b border-slate-200/60 dark:border-slate-700/60">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Hire Date & Service') }}</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                        <span id="modalHireDate">--</span> • <span id="modalServiceYears" class="text-teal-600 dark:text-teal-400 font-bold">--</span>
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-1 pt-0.5 text-center text-xs">
                    <div>
                        <span class="text-xs text-slate-400 dark:text-slate-400 block">{{ __('Base Annual') }}</span>
                        <span id="modalBaseDays" class="font-bold text-slate-800 dark:text-slate-200">18.0d</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 dark:text-slate-400 block">{{ __('Seniority (+1d/3y)') }}</span>
                        <span id="modalSeniorityDays" class="font-bold text-teal-600 dark:text-teal-400">+0d</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 dark:text-slate-400 block">{{ __('Admin Bonus') }}</span>
                        <span id="modalAdditionalDays" class="font-bold text-indigo-600 dark:text-indigo-400">+0d</span>
                    </div>
                </div>
            </div>

            <!-- Other Statutory Leaves Pills -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                <span class="px-2 py-0.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/40 font-medium">
                    {{ __('Special Leave') }}: 7d
                </span>
                <span class="px-2 py-0.5 rounded-lg bg-pink-50 dark:bg-pink-950/40 text-pink-700 dark:text-pink-400 border border-pink-200/50 dark:border-pink-800/40 font-medium">
                    {{ __('Maternity') }}: 90d (50%)
                </span>
                <span class="px-2 py-0.5 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border border-purple-200/50 dark:border-purple-800/40 font-medium">
                    {{ __('Holidays') }}: 22d
                </span>
            </div>

            <!-- Notes / Audit Trail (Compact & Scrollable) -->
            <div id="modalNotesContainer" class="hidden">
                <span class="text-xs uppercase font-bold text-slate-400 dark:text-slate-400 block mb-1">{{ __('Permission History') }}</span>
                <div id="modalNotesText" class="p-2 bg-slate-50 dark:bg-slate-800/70 border border-slate-100 dark:border-slate-800 rounded-lg text-xs text-slate-600 dark:text-slate-300 font-mono whitespace-pre-wrap max-h-20 overflow-y-auto leading-relaxed"></div>
            </div>

        </div>

        <!-- Minimal Footer -->
        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <button type="button" id="modalIncreaseBtn" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ __('Increase Days') }}</span>
            </button>
            <button type="button" onclick="closeModal('permissionDetailModal')" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-semibold rounded-xl transition">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

<!-- Minimal Modal: Increase Permission / Grant Bonus Days -->
<div id="increasePermissionModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all animate-in fade-in zoom-in-95">
        <form id="increasePermissionForm" method="POST" action="">
            @csrf
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Increase Permission Days') }}</h3>
                    <p id="increaseEmpName" class="text-xs text-slate-400 dark:text-slate-400 truncate max-w-[240px]">--</p>
                </div>
                <button type="button" onclick="closeModal('increasePermissionModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-3 text-xs">
                <div>
                    <label for="additional_days" class="block font-bold text-slate-700 dark:text-slate-200 mb-1">{{ __('Days to Increase') }} *</label>
                    <input type="number" name="additional_days" id="additional_days" min="1" max="60" value="1" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:border-teal-500 focus:ring-teal-500 py-2 px-3">
                </div>

                <div>
                    <label for="permission_notes" class="block font-bold text-slate-700 dark:text-slate-200 mb-1">{{ __('Reason / Notes') }}</label>
                    <input type="text" name="permission_notes" id="permission_notes" placeholder="{{ __('e.g., Performance award, Special family allowance') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:border-teal-500 focus:ring-teal-500 py-2 px-3">
                </div>
            </div>

            <div class="px-5 py-3 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal('increasePermissionModal')" class="px-3.5 py-1.5 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-semibold rounded-xl">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showPermissionDetail(data) {
    const avatarEl = document.getElementById('modalEmpImage');
    if (avatarEl) avatarEl.src = data.image || '{{ \App\Models\Employee::DEFAULT_AVATAR }}';

    document.getElementById('modalEmpName').innerText = data.name;
    document.getElementById('modalEmpSubtitle').innerText = (data.role || '{{ __('Staff') }}') + ' • ' + (data.department || '{{ __('General') }}');
    document.getElementById('modalHireDate').innerText = data.hire_date;
    document.getElementById('modalServiceYears').innerText = data.years + 'y ' + (data.months % 12) + 'm';
    document.getElementById('modalBaseDays').innerText = data.base + 'd';
    document.getElementById('modalSeniorityDays').innerText = '+' + data.seniority + 'd';
    document.getElementById('modalAdditionalDays').innerText = '+' + data.additional + 'd';
    document.getElementById('modalTotalDays').innerText = data.total + 'd';
    document.getElementById('modalUsedDays').innerText = data.used + 'd';
    document.getElementById('modalRemainingDays').innerText = data.remaining + 'd';

    const incBtn = document.getElementById('modalIncreaseBtn');
    if (incBtn && data.id) {
        incBtn.onclick = function() {
            closeModal('permissionDetailModal');
            openIncreasePermissionModal(data.id, data.name, data.total);
        };
    }

    const notesContainer = document.getElementById('modalNotesContainer');
    const notesText = document.getElementById('modalNotesText');
    if (data.notes && data.notes.trim().length > 0) {
        notesText.innerText = data.notes.trim();
        notesContainer.classList.remove('hidden');
    } else {
        notesContainer.classList.add('hidden');
    }

    openModal('permissionDetailModal');
}

function openIncreasePermissionModal(empId, empName, currentTotal) {
    document.getElementById('increaseEmpName').innerText = empName + ' (' + currentTotal + 'd)';
    const form = document.getElementById('increasePermissionForm');
    form.action = "{{ url('/dashboard/employees') }}/" + empId + "/permissions";
    openModal('increasePermissionModal');
}

let currentLeaveTab = 'requests';

function filterActiveLeaveTable(input) {
    if (currentLeaveTab === 'balances') {
        filterTable(input, 'employeePermissionsTable');
    } else {
        filterTable(input, 'leaveRequestsTable');
    }
}

function switchLeaveTab(tabName) {
    currentLeaveTab = tabName;
    const btnRequests = document.getElementById('tab-btn-requests');
    const btnBalances = document.getElementById('tab-btn-balances');
    const contentRequests = document.getElementById('tab-content-requests');
    const contentBalances = document.getElementById('tab-content-balances');
    const actionsRequests = document.getElementById('tab-actions-requests');
    const actionsBalances = document.getElementById('tab-actions-balances');
    const subtitle = document.getElementById('tabSubtitle');
    const searchInput = document.getElementById('leaveSearchInput');

    if (searchInput) {
        searchInput.placeholder = tabName === 'balances' ? "{{ __('Search employees...') }}" : "{{ __('Search leaves...') }}";
        if (searchInput.value.trim() !== '') {
            filterActiveLeaveTable(searchInput);
        }
    }

    if (!btnRequests || !btnBalances) return;

    if (tabName === 'balances') {
        // Balances tab active
        btnBalances.className = "inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-white dark:bg-slate-700 text-slate-900 dark:text-white";
        btnRequests.className = "inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-medium transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white";
        
        if (contentRequests) contentRequests.classList.add('hidden');
        if (contentBalances) contentBalances.classList.remove('hidden');

        if (actionsRequests) actionsRequests.classList.add('hidden');
        if (actionsBalances) actionsBalances.classList.remove('hidden');

        if (subtitle) {
            subtitle.innerText = "{{ __('Statutory baseline: 18 days/yr (1.5 days/month after 1 year) + 1 day seniority bonus for every 3 continuous service years.') }}";
        }
    } else {
        // Requests tab active
        btnRequests.className = "inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-white dark:bg-slate-700 text-slate-900 dark:text-white";
        btnBalances.className = "inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-medium transition text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white";
        
        if (contentBalances) contentBalances.classList.add('hidden');
        if (contentRequests) contentRequests.classList.remove('hidden');

        if (actionsBalances) actionsBalances.classList.add('hidden');
        if (actionsRequests) actionsRequests.classList.remove('hidden');

        if (subtitle) {
            subtitle.innerText = "{{ __('Formal applications, duration, and supervisory approval status') }}";
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (window.location.hash === '#employee-permissions-section' || window.location.hash === '#employee-balances' || urlParams.get('tab') === 'balances') {
        switchLeaveTab('balances');
    }
});
</script>
@endsection
