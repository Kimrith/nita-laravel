@php
    $modalUser = auth()->user();
    $modalIsAdmin = $modalUser && $modalUser->isAdmin();
    $modalCurrentEmp = $modalUser ? $modalUser->getAssociatedEmployee() : null;
@endphp

<div id="leaveModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl w-full max-w-md overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150 transition-colors">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                    📅
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">{{ __('Request Leave') }}</h3>
            </div>
            <button type="button" onclick="closeModal('leaveModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 text-lg leading-none">
                ✕
            </button>
        </div>

        <form action="{{ route('leave.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="latitude" id="leaveGeoLat">
            <input type="hidden" name="longitude" id="leaveGeoLng">

            <!-- Applicant Selection / Display -->
            @if(!$modalIsAdmin && $modalCurrentEmp)
                <input type="hidden" name="employee_id" value="{{ $modalCurrentEmp->employee_id }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Applicant') }}</label>
                    <div class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 text-slate-800 dark:text-slate-200 font-medium flex items-center justify-between">
                        <div>
                            <span class="font-bold">{{ $modalCurrentEmp->full_name }}</span>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $modalCurrentEmp->email }}</div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">{{ __('Total Entitled') }}</span>
                            <span class="text-xs font-black {{ $modalCurrentEmp->total_annual_leave_entitlement > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $modalCurrentEmp->total_annual_leave_entitlement }} {{ __('Days') }}</span>
                        </div>
                    </div>
                </div>

                @if($modalCurrentEmp->total_annual_leave_entitlement <= 0)
                    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs flex items-start gap-2.5">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <strong class="font-bold block">{{ __('Request Not Allowed:') }}</strong>
                            <span>{{ __('You do not have any leave entitlement (Total Entitled is 0). You cannot submit leave requests at this time. Please contact your administrator.') }}</span>
                        </div>
                    </div>
                @endif
            @else
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Applicant') }} <span class="text-rose-500">*</span></label>
                        @if($modalIsAdmin)
                            <button type="button" onclick="closeModal('leaveModal'); openModal('employeeModal');" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-800 transition">+ {{ __('New Employee') }}</button>
                        @endif
                    </div>
                    <select name="employee_id" id="adminLeaveApplicantSelect" onchange="checkAdminSelectedApplicantEntitlement(this)" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        @php
                            $applicantList = isset($employees) && count($employees) > 0 ? $employees : (isset($allEmployees) ? $allEmployees : \App\Models\Employee::where('is_active', true)->orderBy('full_name')->get());
                        @endphp
                        @forelse($applicantList as $emp)
                            <option value="{{ $emp->employee_id }}" data-entitled="{{ $emp->total_annual_leave_entitlement }}">{{ $emp->full_name }} ({{ $emp->email }}) — {{ __('Entitled') }}: {{ $emp->total_annual_leave_entitlement }}d</option>
                        @empty
                            <option value="" disabled selected>-- {{ __('No employees registered yet') }} --</option>
                        @endforelse
                    </select>
                    <div id="adminApplicantEntitlementWarning" class="hidden mt-2 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>{{ __('Notice: Selected employee has 0 Total Entitled leave days.') }}</span>
                    </div>
                </div>
            @endif

            <!-- Leave Type -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Leave Type') }} <span class="text-rose-500">*</span></label>
                    @if($modalIsAdmin)
                        <button type="button" onclick="closeModal('leaveModal'); openModal('leaveTypeModal');" class="text-[11px] font-semibold text-teal-600 dark:text-teal-400 hover:text-teal-800 transition">+ {{ __('Add Category') }}</button>
                    @endif
                </div>
                <select name="leave_type_id" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @php
                        $types = isset($leaveTypes) && count($leaveTypes) > 0 ? $leaveTypes : (isset($allLeaveTypes) ? $allLeaveTypes : \App\Models\LeaveType::orderBy('type_name')->get());
                    @endphp
                    @forelse($types as $type)
                        <option value="{{ $type->leave_type_id }}">{{ $type->type_name }} ({{ $type->is_paid ? __('Paid') : __('Unpaid') }})</option>
                    @empty
                        <option value="" disabled selected>-- {{ __('No categories found') }} --</option>
                    @endforelse
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('End Date') }}</label>
                    <input type="date" name="end_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <div id="leaveGeoBadge" class="hidden items-center gap-1.5 text-xs text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/80 dark:border-emerald-800 rounded-xl px-3 py-2 font-medium">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <span id="leaveGeoText" class="truncate">{{ __('Detecting GPS location...') }}</span>
            </div>

            <div class="pt-2">
                @if(!$modalIsAdmin && $modalCurrentEmp && $modalCurrentEmp->total_annual_leave_entitlement <= 0)
                    <button type="button" disabled class="w-full inline-flex items-center justify-center gap-2 bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-semibold py-2.5 px-4 rounded-xl text-sm cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        <span>{{ __('Leave Request Disabled (0 Entitled)') }}</span>
                    </button>
                @else
                    <button type="submit" id="submitLeaveBtn" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm hover:shadow-emerald-500/25">
                        {{ __('Submit Request') }}
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
function checkAdminSelectedApplicantEntitlement(selectEl) {
    const selectedOption = selectEl.options[selectEl.selectedIndex];
    const entitled = parseFloat(selectedOption?.getAttribute('data-entitled') || '0');
    const warningEl = document.getElementById('adminApplicantEntitlementWarning');
    if (warningEl) {
        if (entitled <= 0) {
            warningEl.classList.remove('hidden');
            warningEl.classList.add('flex');
        } else {
            warningEl.classList.add('hidden');
            warningEl.classList.remove('flex');
        }
    }
}
</script>
