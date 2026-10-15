<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AttendX — Authentication')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Kantumruy Pro (Khmer) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Class-based Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Kantumruy Pro"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Theme Initialization -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || '{{ session('theme', 'light') }}';
            if (savedTheme === 'dark' || (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', 'Kantumruy Pro', sans-serif; }
        html.dark, html.dark body {
            background-color: #0b1120 !important;
            color: #f1f5f9 !important;
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-200">

    <!-- Top Navigation Header with Brand and Locale Switcher -->
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-brand-500/20">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-1.5 leading-none">
                    <span class="font-bold text-slate-900 dark:text-white tracking-tight text-lg">AttendX</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 px-1.5 py-0.5 rounded border border-brand-200 dark:border-brand-800">Auth</span>
                </div>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Workforce & Attendance System') }}</p>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <!-- Language Switcher -->
            <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-0.5 rounded-xl border border-slate-200/80 dark:border-slate-700 text-xs font-semibold">
                <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-400 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">EN</a>
                <a href="{{ route('locale.switch', 'km') }}" class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'km' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-400 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">ខ្មែរ</a>
            </div>

            <!-- Dark / Light Theme Toggle -->
            <button type="button" onclick="toggleTheme()" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700 transition" title="Toggle Theme">
                <svg id="themeToggleIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full text-center py-4 text-xs text-slate-400 dark:text-slate-500">
        &copy; {{ date('Y') }} AttendX. {{ __('All rights reserved.') }}
    </footer>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</body>
</html>
