<div id="leaveTypeModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 leading-snug">Create Leave Category</h3>
                    <p class="text-[11px] text-slate-400">Add a new absence category or policy</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('leaveTypeModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none transition">
                ✕
            </button>
        </div>

        <form action="{{ route('leave-types.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Leave Category Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Category Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="type_name" required placeholder="e.g. Annual Vacation, Bereavement, Study Leave" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Paid / Unpaid Status Toggle -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <input type="checkbox" name="is_paid" id="isPaidLeave" value="1" checked class="w-4 h-4 mt-0.5 rounded text-teal-600 border-slate-300 focus:ring-teal-500 cursor-pointer">
                <label for="isPaidLeave" class="text-xs text-slate-700 select-none cursor-pointer">
                    <span class="font-semibold block text-slate-800">Paid Leave Category</span>
                    <span class="text-slate-500 text-[11px]">Employees maintain compensation when taking approved absence under this category.</span>
                </label>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModal('leaveTypeModal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs sm:text-sm transition shadow-sm hover:shadow-teal-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Save Leave Category</span>
                </button>
            </div>
        </form>
    </div>
</div>
