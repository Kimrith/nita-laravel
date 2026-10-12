<div class="w-full bg-white border border-slate-200 rounded-2xl p-3 sm:p-4 shadow-sm lg:sticky lg:top-24">
    <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center justify-between">
        <span>Navigation</span>
        <span class="text-[10px] text-slate-400 lg:hidden">Menu</span>
    </div>
    
    <nav class="space-y-1 mt-1">
        <!-- Overview Link -->
        <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? 'overview') === 'overview' ? 'bg-brand-50 text-brand-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 font-medium' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Overview</span>
        </a>

        <!-- Employees Link -->
        <a href="{{ route('dashboard.employees') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'employees' ? 'bg-brand-50 text-brand-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 font-medium' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Employees</span>
            @if(isset($stats['total_employees']))
                <span class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-full {{ ($activeTab ?? '') === 'employees' ? 'bg-brand-100 text-brand-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $stats['total_employees'] }}
                </span>
            @endif
        </a>

        <!-- Attendance Logs Link -->
        <a href="{{ route('dashboard.attendance') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'attendance' ? 'bg-brand-50 text-brand-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 font-medium' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span>Attendance Logs</span>
        </a>

        <!-- Leave Requests Link -->
        <a href="{{ route('dashboard.leaves') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'leaves' ? 'bg-brand-50 text-brand-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 font-medium' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Leave Requests</span>
            @if(isset($stats['pending_leaves']) && $stats['pending_leaves'] > 0)
                <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                    {{ $stats['pending_leaves'] }}
                </span>
            @endif
        </a>

        <!-- Organization Link -->
        <a href="{{ route('dashboard.organization') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'organization' ? 'bg-brand-50 text-brand-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 font-medium' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span>Organization</span>
        </a>
    </nav>

    <!-- Today's Attendance Widget (Shown when stats are available) -->
    @if(isset($stats) && isset($stats['total_employees']))
        <div class="mt-6 pt-4 border-t border-slate-100">
            <div class="px-3 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                    <span>Today's Attendance</span>
                    <span class="font-bold text-slate-800">
                        {{ $stats['total_employees'] > 0 ? round(($stats['today_clocked_in'] / $stats['total_employees']) * 100) : 0 }}%
                    </span>
                </div>
                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: {{ $stats['total_employees'] > 0 ? min(100, round(($stats['today_clocked_in'] / $stats['total_employees']) * 100)) : 0 }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1.5">
                    <span>{{ $stats['today_clocked_in'] }} Present</span>
                    <span>{{ max(0, $stats['total_employees'] - $stats['today_clocked_in']) }} Absent</span>
                </div>
            </div>
        </div>
    @endif
</div>
