@if(isset($paginator) && $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->total() > 0)
    <div class="px-4 sm:px-5 py-3 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs bg-white dark:bg-slate-900 transition-colors">
        <!-- Result Summary -->
        <div class="text-slate-500 dark:text-slate-400">
            {{ __('Showing') }} 
            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginator->firstItem() ?? 0 }}</span> 
            {{ __('to') }} 
            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginator->lastItem() ?? 0 }}</span> 
            {{ __('of') }} 
            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginator->total() }}</span> 
            {{ __('results') }}
        </div>

        @if($paginator->hasPages())
            <!-- Pagination Controls -->
            <nav class="inline-flex items-center gap-1" aria-label="Pagination">
                {{-- Previous Page Button --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 dark:text-slate-600 bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                       title="{{ __('Previous') }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif

                {{-- Page Number Links --}}
                @php
                    $start = max(1, $paginator->currentPage() - 2);
                    $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition">1</a>
                    @if($start > 2)
                        <span class="inline-flex items-center justify-center w-5 h-8 text-slate-400">…</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-600 text-white font-bold shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                @if($end < $paginator->lastPage())
                    @if($end < $paginator->lastPage() - 1)
                        <span class="inline-flex items-center justify-center w-5 h-8 text-slate-400">…</span>
                    @endif
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium transition">
                        {{ $paginator->lastPage() }}
                    </a>
                @endif

                {{-- Next Page Button --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                       title="{{ __('Next') }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 dark:text-slate-600 bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </nav>
        @endif
    </div>
@endif
