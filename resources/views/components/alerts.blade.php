@if(session('success'))
    <div class="mb-5 flex items-center justify-between p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm shadow-sm transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold p-1">
            ✕
        </button>
    </div>
@endif

@if(session('error'))
    <div class="mb-5 flex items-center justify-between p-4 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-sm transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"></line>
                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"></line>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold p-1">
            ✕
        </button>
    </div>
@endif

@if(session('warning'))
    <div class="mb-5 flex items-center justify-between p-4 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-sm shadow-sm transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span class="font-medium">{{ session('warning') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700 font-bold p-1">
            ✕
        </button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="mb-5 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm shadow-sm">
        <div class="flex items-center gap-2 mb-2 font-semibold">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"></line>
                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"></line>
            </svg>
            <span>{{ __('Please resolve the following errors:') }}</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
