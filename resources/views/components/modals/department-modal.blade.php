<div id="departmentModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 leading-snug">{{ __('Create Department') }}</h3>
                    <p class="text-[11px] text-slate-400">{{ __('Add a new operational division or unit') }}</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('departmentModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none transition">
                ✕
            </button>
        </div>

        <form action="{{ route('departments.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Department Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    {{ __('Department Name') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="department_name" required placeholder="{{ __('e.g. Finance & Accounting, Product Operations') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                <p class="text-[11px] text-slate-400 mt-1">{{ __('Must be unique across the organization.') }}</p>
            </div>

            <!-- Department Manager -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    {{ __('Department Manager') }} <span class="text-slate-400 font-normal">({{ __('Optional') }})</span>
                </label>
                <select name="manager_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="">-- {{ __('No Manager Assigned (Unassigned)') }} --</option>
                    @php
                        $managerCandidates = isset($employees) ? $employees : (isset($allEmployees) ? $allEmployees : []);
                    @endphp
                    @foreach($managerCandidates as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->full_name }} ({{ $emp->email }})</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">{{ __('Assign an active staff member to lead this department.') }}</p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModal('departmentModal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs sm:text-sm transition shadow-sm hover:shadow-brand-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('Save Department') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
