@if(session('success'))
    <div class="mb-5 flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm shadow-sm transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold p-1">
            ✕
        </button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
        <div class="flex items-center gap-2 mb-2 font-semibold">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"></line>
                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"></line>
            </svg>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
