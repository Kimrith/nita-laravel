@php
    $modalUser = auth()->user();
    $modalIsAdmin = $modalUser && $modalUser->isAdmin();
@endphp

<div id="editLeaveModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('editLeaveModal')"></div>

    <!-- Modal Content -->
    <div class="relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl w-full max-w-md overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150 transition-colors">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-sm">
                    ✏️
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">{{ __('Edit Leave Request') }}</h3>
                    <p id="editLeaveSubtitle" class="text-[11px] text-slate-400 dark:text-slate-400">Request #</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('editLeaveModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 text-lg leading-none cursor-pointer">
                ✕
            </button>
        </div>

        <form id="editLeaveForm" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <!-- Employee Info Banner (Shows employee that has edit / who is being edited) -->
            <div class="p-3 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700/80 rounded-xl">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500 dark:text-slate-400">{{ __('Employee') }}:</span>
                    <strong id="editLeaveEmpName" class="text-slate-900 dark:text-slate-100 font-bold">—</strong>
                </div>
                <div class="flex items-center justify-between text-[11px] mt-0.5 text-slate-400">
                    <span>{{ __('Email') }}:</span>
                    <span id="editLeaveEmpEmail" class="font-mono">—</span>
                </div>
                <!-- Previously edited by info (shown if edited) -->
                <div id="editLeaveHistoryBox" class="hidden mt-2 pt-2 border-t border-slate-200 dark:border-slate-700 text-[11px] text-teal-700 dark:text-teal-300 flex items-center gap-1.5 font-medium">
                    <svg class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('Last edited by') }}: <strong id="editLeaveEditorName">—</strong> <span id="editLeaveEditorTime" class="opacity-75"></span></span>
                </div>
            </div>

            <!-- Leave Type -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Leave Type') }} <span class="text-rose-500">*</span>
                </label>
                <select name="leave_type_id" id="editLeaveTypeId" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @php
                        $types = isset($leaveTypes) && count($leaveTypes) > 0 ? $leaveTypes : \App\Models\LeaveType::orderBy('type_name')->get();
                    @endphp
                    @foreach($types as $type)
                        <option value="{{ $type->leave_type_id }}">{{ $type->type_name }} ({{ $type->is_paid ? __('Paid') : __('Unpaid') }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Dates Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Start Date') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="editLeaveStartDate" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('End Date') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="editLeaveEndDate" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Admin Status Override (Admin Only) -->
            @if($modalIsAdmin)
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Application Status') }}</label>
                    <select name="status" id="editLeaveStatus" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2 text-xs bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            @endif

            <!-- Edit Reason / Note -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('Edit Reason / Notes (Optional)') }}
                </label>
                <input type="text" name="edit_reason" id="editLeaveReason" placeholder="{{ __('e.g. Adjusted date due to appointment reschedule') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2 text-xs bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Submit Button -->
            <div class="pt-2 flex items-center gap-2">
                <button type="button" onclick="closeModal('editLeaveModal')" class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs transition">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-4 rounded-xl text-xs transition shadow-sm hover:shadow-brand-500/25">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ __('Save Changes') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditLeaveModal(data) {
        const form = document.getElementById('editLeaveForm');
        form.action = '/dashboard/leaves/' + data.id + '/edit';

        document.getElementById('editLeaveSubtitle').innerText = 'Request #' + data.id;
        document.getElementById('editLeaveEmpName').innerText = data.employee_name || 'N/A';
        document.getElementById('editLeaveEmpEmail').innerText = data.employee_email || '';

        document.getElementById('editLeaveTypeId').value = data.leave_type_id || '';
        document.getElementById('editLeaveStartDate').value = data.start_date || '';
        document.getElementById('editLeaveEndDate').value = data.end_date || '';
        document.getElementById('editLeaveReason').value = data.edit_reason || '';

        const statusSelect = document.getElementById('editLeaveStatus');
        if (statusSelect && data.status) {
            statusSelect.value = data.status;
        }

        // Show editor history if available
        const historyBox = document.getElementById('editLeaveHistoryBox');
        if (historyBox) {
            if (data.last_edited_by) {
                historyBox.classList.remove('hidden');
                document.getElementById('editLeaveEditorName').innerText = data.last_edited_by;
                document.getElementById('editLeaveEditorTime').innerText = data.last_edited_at ? '(' + data.last_edited_at + ')' : '';
            } else {
                historyBox.classList.add('hidden');
            }
        }

        openModal('editLeaveModal');
    }
</script>
