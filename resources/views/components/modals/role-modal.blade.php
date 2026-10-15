<div id="roleModal" class="modal-container hidden fixed inset-0 z-50 items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 leading-snug">{{ __('Create Role') }}</h3>
                    <p class="text-[11px] text-slate-400">{{ __('Establish a job position and define access hierarchy') }}</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('roleModal')" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none transition">
                ✕
            </button>
        </div>

        <form action="{{ route('roles.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Role Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    {{ __('Role Title / Position') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="role_name" required placeholder="{{ __('e.g. Senior Frontend Engineer, HR Specialist') }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>

            <!-- Access Level Tier -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    {{ __('Access Level Tier') }} <span class="text-rose-500">*</span>
                </label>
                <select name="access_level" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <option value="1">{{ __('Level 1 — Staff / General Employee') }}</option>
                    <option value="2">{{ __('Level 2 — Team Lead / Supervisor') }}</option>
                    <option value="3" selected>{{ __('Level 3 — Department Manager / Head') }}</option>
                    <option value="4">{{ __('Level 4 — Executive / Director') }}</option>
                    <option value="5">{{ __('Level 5 — System Administrator') }}</option>
                </select>
                
                <div class="mt-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <p class="text-[11px] font-semibold text-slate-600">{{ __('Access Tier Guidelines:') }}</p>
                    <ul class="text-[11px] text-slate-500 space-y-0.5 list-disc list-inside">
                        <li><span class="font-medium text-slate-700">{{ __('Level 1:') }}</span> {{ __('Standard attendance punching & personal leave application') }}</li>
                        <li><span class="font-medium text-slate-700">{{ __('Level 2-3:') }}</span> {{ __('Team attendance tracking & initial leave review') }}</li>
                        <li><span class="font-medium text-slate-700">{{ __('Level 4-5:') }}</span> {{ __('Full administrative management & organization configuration') }}</li>
                    </ul>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModal('roleModal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 px-5 rounded-xl text-xs sm:text-sm transition shadow-sm hover:shadow-brand-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('Save Role') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
