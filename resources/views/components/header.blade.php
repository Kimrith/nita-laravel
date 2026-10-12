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
                    <p class="text-[11px] text-slate-400 hidden sm:block mt-0.5">Workforce & Attendance System</p>
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

            <!-- Punch Action Trigger -->
            <button onclick="openModal('clockModal')" type="button" class="inline-flex items-center gap-1.5 sm:gap-2 bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 rounded-xl transition shadow-sm hover:shadow-brand-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Punch Clock</span>
            </button>
        </div>
    </div>
</header>