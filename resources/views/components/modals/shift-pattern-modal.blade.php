<div id="shiftPatternModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 leading-snug">{{ __('Create Shift Pattern') }}</h3>
                    <p class="text-[11px] text-slate-400">{{ __('Configure working hours and attendance grace policies') }}</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('shiftPatternModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none transition">
                ✕
            </button>
        </div>

        <form action="{{ route('shift-patterns.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Shift Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    {{ __('Shift Name / Identifier') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="shift_name" required placeholder="{{ __('e.g. Standard Morning (08:30-17:30), Night Rotation') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Start & End Time Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        {{ __('Shift Start Time') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="start_time" required value="08:00" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        {{ __('Shift End Time') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="end_time" required value="17:00" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Grace Period -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    {{ __('Late Grace Period (Minutes)') }} <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" name="grace_period_mins" required min="0" max="240" value="15" class="w-full rounded-xl border border-slate-200 pl-3.5 pr-14 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">{{ __('mins') }}</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">{{ __('Clock-ins within this period after start time are marked on-time.') }}</p>
            </div>

            <!-- Flexibility Toggle -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <input type="checkbox" name="is_flexible" id="isShiftFlexible" value="1" class="w-4 h-4 mt-0.5 rounded text-brand-600 border-slate-300 focus:ring-brand-500 cursor-pointer">
                <label for="isShiftFlexible" class="text-xs text-slate-700 select-none cursor-pointer">
                    <span class="font-semibold block text-slate-800">{{ __('Flexible Working Hours') }}</span>
                    <span class="text-slate-500 text-[11px]">{{ __('Employees under this schedule have flexible punch timing rather than strict fixed arrival times.') }}</span>
                </label>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModal('shiftPatternModal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs sm:text-sm transition shadow-sm hover:shadow-brand-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('Save Shift Pattern') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
