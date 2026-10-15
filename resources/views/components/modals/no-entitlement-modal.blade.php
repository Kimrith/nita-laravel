@php
    $modalUser = auth()->user();
    $modalCurrentEmp = $modalUser ? $modalUser->getAssociatedEmployee() : null;
@endphp

<div id="noEntitlementModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('noEntitlementModal')"></div>

    <!-- Modal Card -->
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-md overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150 transition-colors">
        
        <!-- Header Strip -->
        <div class="px-6 pt-6 pb-2 text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 flex items-center justify-center shadow-lg shadow-rose-500/10 mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-700 mb-2">
                <span>{{ __('Total Entitled: 0 Days') }}</span>
            </div>

            <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                {{ __('Leave Request Not Allowed') }}
            </h3>
            
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                {{ __('You cannot submit a leave request because you do not have any statutory leave entitlement (Total Entitled is 0). Under company policy and Cambodia Labor Law, statutory leave accrues with continuous service or requires administrative grant. Please contact your administrator.') }}
            </p>
        </div>

        <!-- Employee Summary Card -->
        @if($modalCurrentEmp)
            <div class="mx-6 my-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 space-y-2.5 text-xs">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60 dark:border-slate-700/60">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Employee') }}:</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100">{{ $modalCurrentEmp->full_name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Hire Date') }}:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $modalCurrentEmp->hire_date ? $modalCurrentEmp->hire_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Base Statutory') }}:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $modalCurrentEmp->base_annual_leave_days }}d</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Seniority (+1d/3y)') }}:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">+{{ $modalCurrentEmp->seniority_bonus_days }}d</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Granted Bonus') }}:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">+{{ $modalCurrentEmp->additional_leave_days ?? 0 }}d</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-200/60 dark:border-slate-700/60 font-semibold">
                    <span class="text-slate-800 dark:text-slate-200">{{ __('Total Entitled') }}:</span>
                    <span class="text-rose-600 dark:text-rose-400 font-black text-sm">{{ $modalCurrentEmp->total_annual_leave_entitlement }} {{ __('Days') }}</span>
                </div>
            </div>
        @endif

        <!-- Action Button -->
        <div class="p-6 pt-2">
            <button type="button" onclick="closeModal('noEntitlementModal')" class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm">
                <span>{{ __('Understood') }}</span>
            </button>
        </div>

    </div>
</div>
