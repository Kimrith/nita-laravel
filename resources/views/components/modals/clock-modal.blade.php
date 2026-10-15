<div id="clockModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-md overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                    ⏱️
                </div>
                <h3 class="text-base font-bold text-slate-800">{{ __('Record Attendance') }}</h3>
            </div>
            <button type="button" onclick="closeModal('clockModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none">
                ✕
            </button>
        </div>

        <form action="{{ route('attendance.record') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Warning Banner if Selected Employee is on Leave -->
            <div id="leaveRestrictedBanner" class="hidden p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>{{ __('Punch Recording Restricted') }}</span>
                </div>
                <p class="text-[11px] text-amber-700 mt-1">{{ __('This employee is currently on Approved Leave and cannot punch attendance.') }}</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('Select Employee') }}</label>
                <select name="employee_id" id="clockEmployeeSelect" required onchange="checkEmployeeLeaveStatus(this)" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @php
                        $empList = isset($employees) && count($employees) > 0 ? $employees : (isset($allEmployees) ? $allEmployees : \App\Models\Employee::where('is_active', true)->orderBy('full_name')->get());
                        $leaveEmpIds = isset($activeLeaveEmployeeIds) ? $activeLeaveEmployeeIds : \App\Models\LeaveRequest::where('status', 'Approved')->whereDate('start_date', '<=', now()->toDateString())->whereDate('end_date', '>=', now()->toDateString())->pluck('employee_id')->toArray();
                    @endphp
                    @forelse($empList as $emp)
                        @php
                            $onLeave = in_array($emp->employee_id, $leaveEmpIds);
                        @endphp
                        <option value="{{ $emp->employee_id }}" data-on-leave="{{ $onLeave ? '1' : '0' }}" {{ $onLeave ? 'disabled class=text-slate-400' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->email }}) {{ $onLeave ? '— 🚫 [On Approved Leave]' : '' }}
                        </option>
                    @empty
                        <option value="" disabled selected>-- {{ __('No employees registered') }} --</option>
                    @endforelse
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">{{ __('Event Type') }}</label>
                <select name="event_type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="Clock-In">{{ __('Clock-In (Shift Start)') }}</option>
                    <option value="Clock-Out">{{ __('Clock-Out (Shift End)') }}</option>
                    <option value="Break-Start">{{ __('Break-Start') }}</option>
                    <option value="Break-End">{{ __('Break-End') }}</option>
                </select>
            </div>

            <input type="hidden" name="source_device" value="Web">

            <input type="hidden" name="latitude" id="geoLat">
            <input type="hidden" name="longitude" id="geoLng">

            <div id="clockGeoBadge" class="hidden items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200/80 rounded-xl px-3 py-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <span id="clockGeoText" class="truncate">{{ __('Detecting GPS location...') }}</span>
            </div>

            <div class="pt-2">
                <button type="submit" id="clockSubmitBtn" class="w-full inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm hover:shadow-brand-500/25">
                    {{ __('Submit Punch') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function checkEmployeeLeaveStatus(selectEl) {
        if (!selectEl) return;
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        const isOnLeave = selectedOpt && selectedOpt.getAttribute('data-on-leave') === '1';
        const banner = document.getElementById('leaveRestrictedBanner');
        const submitBtn = document.getElementById('clockSubmitBtn');

        if (isOnLeave) {
            if (banner) banner.classList.remove('hidden');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.classList.remove('hover:bg-brand-700');
            }
        } else {
            if (banner) banner.classList.add('hidden');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.classList.add('hover:bg-brand-700');
            }
        }
    }
</script>
