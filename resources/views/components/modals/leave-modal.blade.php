<div id="leaveModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-md overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    📅
                </div>
                <h3 class="text-base font-bold text-slate-800">Apply for Leave</h3>
            </div>
            <button type="button" onclick="closeModal('leaveModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none">
                ✕
            </button>
        </div>

        <form action="{{ route('leave.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Applicant</label>
                <select name="employee_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @if(isset($employees))
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->full_name }} ({{ $emp->email }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Leave Category</label>
                <select name="leave_type_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    @if(isset($leaveTypes))
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->leave_type_id }}">{{ $type->type_name }} ({{ $type->is_paid ? 'Paid' : 'Unpaid' }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">End Date</label>
                    <input type="date" name="end_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm hover:shadow-emerald-500/25">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
