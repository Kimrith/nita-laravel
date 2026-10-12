<div id="employeeModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                    👤
                </div>
                <h3 class="text-base font-bold text-slate-800">Add New Employee</h3>
            </div>
            <button type="button" onclick="closeModal('employeeModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none">
                ✕
            </button>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="full_name" required placeholder="e.g. John Doe" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Work Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="e.g. john.doe@company.com" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Department & Role Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Department</label>
                    <select name="department_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="">-- None / General --</option>
                        @if(isset($departments))
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role</label>
                    <select name="role_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="">-- None / Staff --</option>
                        @if(isset($roles))
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <!-- Shift Pattern & Hire Date Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Shift Pattern</label>
                    <select name="shift_pattern_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="">-- Default Shift --</option>
                        @if(isset($shiftPatterns))
                            @foreach($shiftPatterns as $shift)
                                <option value="{{ $shift->shift_pattern_id }}">{{ $shift->shift_name }} ({{ substr($shift->start_time, 0, 5) }}-{{ substr($shift->end_time, 0, 5) }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hire Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="hire_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Active Status Checkbox -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" id="empIsActive" value="1" checked class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                <label for="empIsActive" class="text-xs font-medium text-slate-700 select-none">Active Employee (authorized to punch in/out)</label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm hover:shadow-brand-500/25">
                    Save Employee
                </button>
            </div>
        </form>
    </div>
</div>
