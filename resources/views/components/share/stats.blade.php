<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <!-- Stat 1: Total Employees -->
    <div class="w-full bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
        <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2">
            <span>{{ __('Employees') }}</span>
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">👥</span>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['total_employees'] }}</div>
        <p class="text-xs text-slate-400 mt-1">{{ $stats['active_employees'] }} {{ __('actively enrolled') }}</p>
    </div>

    <!-- Stat 2: Clocked In Today -->
    <div class="w-full bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
        <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2">
            <span>{{ __('Clocked In Today') }}</span>
            <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">⏱️</span>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['today_clocked_in'] }}</div>
        <p class="text-xs text-emerald-600 font-medium mt-1">
            {{ $stats['total_employees'] > 0 ? round(($stats['today_clocked_in'] / $stats['total_employees']) * 100) : 0 }}% {{ __('workforce present') }}
        </p>
    </div>

    <!-- Stat 3: Pending Leaves -->
    <div class="w-full bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
        <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2">
            <span>{{ __('Pending Leaves') }}</span>
            <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">📅</span>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['pending_leaves'] }}</div>
        <p class="text-xs text-slate-400 mt-1">{{ __('Awaiting approval') }}</p>
    </div>

    <!-- Stat 4: Departments & Shifts -->
    <div class="w-full bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
        <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2">
            <span>{{ __('Org Units') }}</span>
            <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">🏢</span>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['total_departments'] }}</div>
        <p class="text-xs text-slate-400 mt-1">{{ $stats['total_shifts'] }} {{ __('shift patterns defined') }}</p>
    </div>
</div>
