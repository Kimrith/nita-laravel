<header class="w-full bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        
        <!-- Left: Mobile Menu Toggle & Brand Logo -->
        <div class="flex items-center gap-3">
            <!-- Mobile Sidebar Hamburger Toggle (visible on screens < lg) -->
            <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition" aria-label="Toggle Navigation">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Brand Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-brand-500/20 flex-shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-1.5 leading-none">
                        <span class="font-bold text-slate-900 tracking-tight text-base sm:text-lg">AttendX</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-600 px-1.5 py-0.5 rounded border border-brand-200">Lite</span>
                    </div>
                    <p class="text-[11px] text-slate-400 hidden sm:block mt-0.5">{{ __('Workforce & Attendance System') }}</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Clock & Punch Action -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Digital Clock Badge -->
            <div class="hidden md:flex items-center gap-2 bg-slate-100/90 border border-slate-200 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-700 tracking-wide font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="liveClock">--:--:--</span>
            </div>

            <!-- Language Switcher (EN / KM) -->
            <div class="flex items-center bg-slate-100 p-0.5 rounded-xl border border-slate-200/80 text-xs font-semibold shadow-2xs">
                <a href="{{ route('locale.switch', 'en') }}" 
                   title="English"
                   class="px-2.5 py-1 rounded-lg transition-all flex items-center gap-1 {{ app()->getLocale() === 'en' ? 'bg-white text-brand-600 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <span>EN</span>
                </a>
                <a href="{{ route('locale.switch', 'km') }}" 
                   title="ភាសាខ្មែរ"
                   class="px-2.5 py-1 rounded-lg transition-all flex items-center gap-1 {{ app()->getLocale() === 'km' ? 'bg-white text-brand-600 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <span>ខ្មែរ</span>
                </a>
            </div>

            <!-- Theme Switcher (Quick Dark / Light Toggle) -->
            <button type="button" 
                    onclick="toggleThemeQuick()" 
                    title="{{ __('Toggle Dark / Light Mode') }}" 
                    class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition border border-slate-200/80 flex items-center justify-center cursor-pointer" 
                    aria-label="Toggle Theme">
                <!-- Sun Icon (Shown in Dark Mode) -->
                <svg id="headerSunIcon" class="w-4 h-4 hidden text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <!-- Moon Icon (Shown in Light Mode) -->
                <svg id="headerMoonIcon" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>

            <!-- Fullscreen Toggle Button -->
            <button type="button" onclick="toggleFullScreen()" title="{{ __('Toggle Full Screen') }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition border border-slate-200/80 flex items-center justify-center cursor-pointer" aria-label="Toggle Fullscreen">
                <svg id="fullscreenExpandIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
                <svg id="fullscreenCompressIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h6m0 0v6m0-6L3 21m17-7h-6m0 0v6m0-6l7 7M10 10V4m0 6H4m6 0L3 3m10 7h6m-6 0V4m0 6l7-7"></path>
                </svg>
            </button>

            <!-- User Info & Logout Button -->
            @if(auth()->check())
                <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-700">
                    <img src="{{ auth()->user()->profile_image }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-7 h-7 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-2xs flex-shrink-0 bg-slate-100" 
                         onerror="this.src='{{ \App\Models\Employee::DEFAULT_AVATAR }}'">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[120px]">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded {{ auth()->user()->isAdmin() ? 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300' : 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' }}">
                        {{ auth()->user()->role }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" title="{{ __('Sign Out') }}" class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/60 rounded-lg transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Punch Action Trigger -->
            <button onclick="openModal('clockModal')" type="button" class="inline-flex items-center gap-1.5 sm:gap-2 bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 rounded-xl transition shadow-sm hover:shadow-brand-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ __('Punch Clock') }}</span>
            </button>
        </div>
    </div>
</header>