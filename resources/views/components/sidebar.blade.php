@php
    $authUser = auth()->user();
    $isAdmin = $authUser && $authUser->isAdmin();
@endphp

<div class="w-full h-full flex flex-col justify-between p-4 sm:p-5 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-colors">
    <div>
        <div class="px-3 py-2 text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center justify-between">
            <span>{{ __('Navigation') }}</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 lg:hidden">Menu</span>
        </div>
    
        <nav class="space-y-1 mt-1">
            {{-- 1. Overview Link (Admin Only) --}}
            @if($isAdmin)
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? 'overview') === 'overview' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>{{ __('Overview') }}</span>
                </a>

                {{-- 2. Employees Link (Admin Only) --}}
                <a href="{{ route('dashboard.employees') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'employees' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>{{ __('Employees') }}</span>
                    @if(isset($stats['total_employees']))
                        <span class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-full {{ ($activeTab ?? '') === 'employees' ? 'bg-brand-100 dark:bg-brand-900/60 text-brand-700 dark:text-brand-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                            {{ $stats['total_employees'] }}
                        </span>
                    @endif
                </a>

                {{-- 3. Attendance Logs Link (Admin Only) --}}
                <a href="{{ route('dashboard.attendance') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'attendance' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>{{ __('Attendance Logs') }}</span>
                </a>
            @endif

            {{-- 4. Leave Requests Link (Both Admin & Employee) --}}
            <a href="{{ route('dashboard.leaves') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'leaves' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ __('Leave Requests') }}</span>
                @if(isset($stats['pending_leaves']) && $stats['pending_leaves'] > 0 && $isAdmin)
                    <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300">
                        {{ $stats['pending_leaves'] }}
                    </span>
                @endif
            </a>

            {{-- 5. Leave Policies Link (Both Admin & Employee) --}}
            <a href="{{ route('dashboard.leave-policies') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'leave-policies' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>{{ __('Leave Policies') }}</span>
            </a>

            {{-- 6. Organization Link (Admin Only) --}}
            @if($isAdmin)
                <a href="{{ route('dashboard.organization') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'organization' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>{{ __('Organization') }}</span>
                </a>
            @endif

            {{-- 7. Settings Link (Both Admin & Employee) --}}
            <a href="{{ route('dashboard.settings') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ ($activeTab ?? '') === 'settings' ? 'bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>{{ __('Settings') }}</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Section: Attendance Widget (Admin) & User Account Info + Logout -->
    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
        
        <!-- Attendance Stats (Admin Only) -->
        @if($isAdmin && isset($stats) && isset($stats['total_employees']))
            <div class="px-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                    <span>{{ __("Today's Attendance") }}</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">
                        {{ $stats['total_employees'] > 0 ? round(($stats['today_clocked_in'] / $stats['total_employees']) * 100) : 0 }}%
                    </span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: {{ $stats['total_employees'] > 0 ? min(100, round(($stats['today_clocked_in'] / $stats['total_employees']) * 100)) : 0 }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1.5">
                    <span>{{ $stats['today_clocked_in'] }} {{ __('Present') }}</span>
                    <span>{{ max(0, $stats['total_employees'] - $stats['today_clocked_in']) }} {{ __('Absent') }}</span>
                </div>
            </div>
        @endif

        <!-- Authenticated User Card with Logout Action -->
        @if($authUser)
            <div class="p-2.5 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2.5">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ $authUser->profile_image }}" 
                         alt="{{ $authUser->name }}" 
                         class="w-8 h-8 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-xs flex-shrink-0 bg-slate-100" 
                         onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                    <div class="min-w-0 leading-tight">
                        <div class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate">
                            {{ $authUser->name }}
                        </div>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.2 rounded {{ $isAdmin ? 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300' : 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' }}">
                                {{ $authUser->role }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" 
                            title="{{ __('Sign Out') }}" 
                            class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/60 rounded-xl transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
