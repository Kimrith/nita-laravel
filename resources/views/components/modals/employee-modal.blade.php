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
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Work Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="e.g. john.doe@company.com" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Login Credentials Notice -->
            <div class="p-3 bg-blue-50/80 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-xl text-xs text-blue-800 dark:text-blue-300 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('Automatic Account & Login Credentials') }}</span>
                </div>
                <p class="text-[11px] leading-relaxed text-blue-700 dark:text-blue-300">
                    {{ __('An employee account will be created automatically with role') }} <strong>employee</strong>, {{ __('status') }} <strong>approved</strong>, {{ __('and default password') }} <code class="bg-blue-100 dark:bg-blue-900 px-1 py-0.5 rounded font-mono font-bold">12345678</code>. {{ __('The employee can log in immediately without extra approval.') }}
                </p>
            </div>

            <!-- Department & Role Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-slate-700">Department</label>
                        <button type="button" onclick="closeModal('employeeModal'); openModal('departmentModal');" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 transition">+ New</button>
                    </div>
                    <select name="department_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="">-- None / General --</option>
                        @php
                            $deptList = isset($departments) ? $departments : (isset($allDepartments) ? $allDepartments : []);
                        @endphp
                        @foreach($deptList as $dept)
                            <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-slate-700">Role</label>
                        <button type="button" onclick="closeModal('employeeModal'); openModal('roleModal');" class="text-[11px] font-semibold text-amber-600 hover:text-amber-800 transition">+ New</button>
                    </div>
                    <select name="role_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="">-- None / Staff --</option>
                        @php
                            $roleList = isset($roles) ? $roles : (isset($allRoles) ? $allRoles : []);
                        @endphp
                        @foreach($roleList as $role)
                            <option value="{{ $role->role_id }}">{{ $role->role_name }} (L{{ $role->access_level }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Shift Pattern & Hire Date Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-slate-700">Shift Pattern</label>
                        <button type="button" onclick="closeModal('employeeModal'); openModal('shiftPatternModal');" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-800 transition">+ New</button>
                    </div>
                    <select name="shift_pattern_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        <option value="">-- Default Shift --</option>
                        @php
                            $shiftList = isset($shiftPatterns) ? $shiftPatterns : (isset($allShiftPatterns) ? $allShiftPatterns : []);
                        @endphp
                        @foreach($shiftList as $shift)
                            <option value="{{ $shift->shift_pattern_id }}">{{ $shift->shift_name }} ({{ substr($shift->start_time, 0, 5) }}-{{ substr($shift->end_time, 0, 5) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hire Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="hire_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Cambodia Labor Law Permission Notice & Bonus -->
            <div class="p-3 bg-teal-50/70 border border-teal-100 rounded-xl space-y-2">
                <div class="flex items-center gap-1.5 text-teal-800 text-xs font-bold">
                    <span>🇰🇭</span>
                    <span>{{ __('Cambodia Labor Law Statutory Permission') }}</span>
                </div>
                <p class="text-[11px] text-teal-700 leading-snug">
                    {{ __('Entitled to 18 days/yr base annual leave (1.5 days/month after 1 year), increasing by 1 day for every 3 years continuous service, plus 7 days special leave & 90 days maternity (50%).') }}
                </p>
                <div class="pt-1 flex items-center gap-2">
                    <label class="text-[11px] font-semibold text-slate-700 whitespace-nowrap">{{ __('Initial Bonus Days (Optional)') }}:</label>
                    <input type="number" name="additional_leave_days" min="0" max="60" value="0" class="w-20 rounded-lg border border-teal-200 bg-white px-2 py-1 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-teal-500">
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
