<!-- Delete Confirmation & Mandatory Backup Modal -->
<div id="deleteConfirmModal" class="modal-container fixed inset-0 z-50 hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm transition-opacity" onclick="closeModal('deleteConfirmModal')"></div>

    <!-- Modal Content -->
    <div class="modal-content relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 z-10 transition-all transform animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Close Button -->
        <button type="button" onclick="closeModal('deleteConfirmModal')" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Header with Warning Icon -->
        <div class="flex items-center gap-3.5 mb-5">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/80 dark:border-rose-800 flex items-center justify-center flex-shrink-0 shadow-xs">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-100 leading-snug">
                    {{ __('Confirm Deletion & Backup Notice') }}
                </h3>
                <p class="text-xs text-rose-600 dark:text-rose-400 font-semibold tracking-wide uppercase">
                    {{ __('Permanent Data Removal Warning') }}
                </p>
            </div>
        </div>

        <!-- Prominent Backup Notice Box (Yellow / Amber Alert) -->
        <div class="mb-5 p-4 bg-amber-50 dark:bg-amber-950/40 border-2 border-amber-300 dark:border-amber-700/60 rounded-2xl shadow-xs">
            <div class="flex items-start gap-3">
                <div class="p-1.5 bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 rounded-lg flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-amber-950 dark:text-amber-200 uppercase tracking-wider mb-1">
                        {{ __('Admin Notice: Need To Backup First!') }}
                    </h4>
                    <p class="text-xs text-amber-900 dark:text-amber-300 font-medium leading-relaxed">
                        {{ __('Please make sure you backup your records as Excel or PDF before deleting! Once deleted, this record cannot be recovered.') }}
                    </p>
                    <p class="text-[11px] text-amber-800/80 dark:text-amber-400/90 mt-1">
                        (សូមបម្រុងទុកទិន្នន័យជាឯកសារ Excel ឬ PDF ជាមុនសិន មុនពេលលុបកំណត់ត្រានេះ ដើម្បីកុំឱ្យបាត់បង់ទិន្នន័យ)
                    </p>
                </div>
            </div>
        </div>

        <!-- Target Record Preview Details -->
        <div class="mb-5 p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-800">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                {{ __('Target Record To Be Deleted:') }}
            </div>
            <div class="flex items-center justify-between gap-2">
                <div id="deleteModalItemName" class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">
                    —
                </div>
                <span id="deleteModalItemType" class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex-shrink-0">
                    Record
                </span>
            </div>
        </div>

        <!-- Step 1: Backup Actions (Excel & PDF) -->
        <div class="mb-6">
            <div class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <span class="w-5 h-5 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 text-[11px] flex items-center justify-center font-bold">1</span>
                <span>{{ __('Backup Data First (Recommended):') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <!-- Backup as Excel (.CSV) -->
                <a id="deleteModalExcelLink" 
                   href="#" 
                   target="_blank" 
                   class="flex items-center justify-center gap-2 p-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>{{ __('Backup as Excel') }}</span>
                </a>

                <!-- Backup as PDF / Print -->
                <a id="deleteModalPdfLink" 
                   href="#" 
                   target="_blank" 
                   class="flex items-center justify-center gap-2 p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ __('Backup as PDF') }}</span>
                </a>
            </div>
        </div>

        <!-- Step 2: Final Confirmation & Delete Action -->
        <div>
            <div class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <span class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] flex items-center justify-center font-bold">2</span>
                <span>{{ __('Confirm Permanent Deletion:') }}</span>
            </div>

            <form id="deleteConfirmForm" method="POST" action="" class="flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" 
                        onclick="closeModal('deleteConfirmModal')" 
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" 
                        id="confirmDeleteSubmitBtn"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 hover:shadow-rose-600/30 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>{{ __('Delete Permanently') }}</span>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function confirmDeleteWithBackup(config) {
        const modal = document.getElementById('deleteConfirmModal');
        if (!modal) return;

        // Set target action
        const form = document.getElementById('deleteConfirmForm');
        if (form && config.url) {
            form.action = config.url;
        }

        // Set item details
        const nameEl = document.getElementById('deleteModalItemName');
        const typeEl = document.getElementById('deleteModalItemType');
        if (nameEl) nameEl.textContent = config.title || 'Selected Record';
        if (typeEl) typeEl.textContent = config.type || 'Item';

        // Set backup URLs
        const exportType = config.exportType || 'employees';
        const excelBtn = document.getElementById('deleteModalExcelLink');
        const pdfBtn = document.getElementById('deleteModalPdfLink');

        if (excelBtn) {
            excelBtn.href = "{{ route('export.excel') }}?type=" + encodeURIComponent(exportType);
        }
        if (pdfBtn) {
            pdfBtn.href = "{{ route('export.pdf') }}?type=" + encodeURIComponent(exportType) + "&print=1";
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
