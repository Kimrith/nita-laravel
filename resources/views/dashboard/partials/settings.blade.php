@extends('layouts.app')

@section('title', 'Settings & Preferences — AttendX')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Banner -->
    <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-brand-500/20 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-100">{{ __('Settings & Preferences') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Customize appearance themes, application display, and system preferences') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span id="themeSaveStatus" class="hidden text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1.5 rounded-xl border border-emerald-200 dark:border-emerald-800 animate-fade-in flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ __('Saved') }}</span>
            </span>
        </div>
    </div>

    <!-- Theme Selection Section -->
    <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs transition-colors">
        <div class="mb-5">
            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __('Appearance & Theme Mode') }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Select your preferred visual style. Changes apply immediately across all dashboard views.') }}</p>
        </div>

        <!-- 3 Interactive Theme Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- 1. Light Mode Card -->
            <div onclick="selectThemeMode('light')" 
                 data-theme-card="light"
                 class="group relative border-2 rounded-2xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md bg-white border-slate-200 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="w-full h-32 rounded-xl bg-slate-100 p-2.5 flex flex-col justify-between overflow-hidden border border-slate-200 shadow-inner">
                    <div class="w-full h-3 bg-white rounded-md shadow-2xs flex items-center px-1.5 gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                    </div>
                    <div class="flex gap-2 flex-1 mt-2">
                        <div class="w-1/3 bg-white rounded-lg p-1.5 border border-slate-200 space-y-1">
                            <div class="w-full h-1.5 bg-blue-100 rounded"></div>
                            <div class="w-3/4 h-1.5 bg-slate-200 rounded"></div>
                            <div class="w-4/5 h-1.5 bg-slate-200 rounded"></div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg p-2 border border-slate-200 flex flex-col justify-between">
                            <div class="flex justify-between items-center">
                                <div class="w-1/2 h-2 bg-slate-800 rounded"></div>
                                <div class="w-4 h-2 bg-emerald-100 rounded-full"></div>
                            </div>
                            <div class="space-y-1">
                                <div class="w-full h-1 bg-slate-200 rounded"></div>
                                <div class="w-5/6 h-1 bg-slate-200 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ __('Light Mode') }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Crisp daylight clarity') }}</div>
                        </div>
                    </div>
                    <div class="theme-check w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all">
                        <div class="theme-check-dot w-2.5 h-2.5 rounded-full bg-brand-600 hidden"></div>
                    </div>
                </div>
            </div>

            <!-- 2. Dark Mode Card -->
            <div onclick="selectThemeMode('dark')" 
                 data-theme-card="dark"
                 class="group relative border-2 rounded-2xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md bg-white border-slate-200 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="w-full h-32 rounded-xl bg-slate-950 p-2.5 flex flex-col justify-between overflow-hidden border border-slate-800 shadow-inner">
                    <div class="w-full h-3 bg-slate-900 rounded-md flex items-center px-1.5 gap-1 border border-slate-800">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    </div>
                    <div class="flex gap-2 flex-1 mt-2">
                        <div class="w-1/3 bg-slate-900 rounded-lg p-1.5 border border-slate-800 space-y-1">
                            <div class="w-full h-1.5 bg-brand-500/40 rounded"></div>
                            <div class="w-3/4 h-1.5 bg-slate-800 rounded"></div>
                            <div class="w-4/5 h-1.5 bg-slate-800 rounded"></div>
                        </div>
                        <div class="flex-1 bg-slate-900 rounded-lg p-2 border border-slate-800 flex flex-col justify-between">
                            <div class="flex justify-between items-center">
                                <div class="w-1/2 h-2 bg-slate-200 rounded"></div>
                                <div class="w-4 h-2 bg-emerald-950 border border-emerald-700 rounded-full"></div>
                            </div>
                            <div class="space-y-1">
                                <div class="w-full h-1 bg-slate-800 rounded"></div>
                                <div class="w-5/6 h-1 bg-slate-800 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-indigo-950 text-indigo-400 border border-indigo-800 flex items-center justify-center text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ __('Dark Mode') }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Deep slate contrast') }}</div>
                        </div>
                    </div>
                    <div class="theme-check w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all">
                        <div class="theme-check-dot w-2.5 h-2.5 rounded-full bg-brand-600 hidden"></div>
                    </div>
                </div>
            </div>

            <!-- 3. System Sync Card -->
            <div onclick="selectThemeMode('system')" 
                 data-theme-card="system"
                 class="group relative border-2 rounded-2xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md bg-white border-slate-200 dark:border-slate-800 dark:bg-slate-950/40">
                <div class="w-full h-32 rounded-xl p-2.5 flex flex-col justify-between overflow-hidden border border-slate-300 dark:border-slate-700 shadow-inner relative" style="background: linear-gradient(135deg, #f1f5f9 50%, #0b1120 50%);">
                    <div class="w-full h-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xs rounded-md flex items-center px-1.5 gap-1 border border-slate-300/40">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                    </div>
                    <div class="flex items-center justify-center flex-1">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-white/90 dark:bg-slate-900/90 text-slate-800 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-700">
                            {{ __('Auto OS Sync') }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ __('System Default') }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Match OS preferences') }}</div>
                        </div>
                    </div>
                    <div class="theme-check w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all">
                        <div class="theme-check-dot w-2.5 h-2.5 rounded-full bg-brand-600 hidden"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Interface & Display Preferences Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Language & Regional Settings -->
        <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs transition-colors flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __('Language & Localization') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Select interface language for menus, tables, and notifications.') }}</p>

                <div class="grid grid-cols-2 gap-3 mt-4">
                    <a href="{{ route('locale.switch', 'en') }}" class="flex items-center justify-between p-3.5 rounded-xl border {{ app()->getLocale() === 'en' ? 'border-brand-500 bg-brand-50/50 dark:bg-brand-950/20 ring-2 ring-brand-500/20 font-bold text-brand-700 dark:text-brand-300' : 'border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300' }} transition">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">🇬🇧</span>
                            <div>
                                <div class="text-xs font-semibold">English</div>
                                <div class="text-[10px] text-slate-400">Default</div>
                            </div>
                        </div>
                        @if(app()->getLocale() === 'en')
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @endif
                    </a>

                    <a href="{{ route('locale.switch', 'km') }}" class="flex items-center justify-between p-3.5 rounded-xl border {{ app()->getLocale() === 'km' ? 'border-brand-500 bg-brand-50/50 dark:bg-brand-950/20 ring-2 ring-brand-500/20 font-bold text-brand-700 dark:text-brand-300' : 'border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300' }} transition">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">🇰🇭</span>
                            <div>
                                <div class="text-xs font-semibold">ភាសាខ្មែរ</div>
                                <div class="text-[10px] text-slate-400">Khmer</div>
                            </div>
                        </div>
                        @if(app()->getLocale() === 'km')
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @endif
                    </a>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                <span>{{ __('Current Locale') }}: <strong class="text-slate-700 dark:text-slate-300 uppercase">{{ app()->getLocale() }}</strong></span>
                <span>UTF-8 Unicode Supported</span>
            </div>
        </div>

        <!-- Display & Screen Options -->
        <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs transition-colors flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __('Display & Workspace') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Control kiosk and workspace viewport preferences.') }}</p>

                <div class="space-y-3 mt-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 flex items-center justify-center text-xs">
                                🖥️
                            </span>
                            <div>
                                <div class="text-xs font-semibold text-slate-900 dark:text-slate-200">{{ __('Fullscreen Mode') }}</div>
                                <div class="text-[10px] text-slate-400">{{ __('Edge-to-edge distraction-free view') }}</div>
                            </div>
                        </div>
                        <button type="button" onclick="toggleFullScreen()" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 transition shadow-2xs">
                            {{ __('Toggle') }}
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xs">
                                ⏱️
                            </span>
                            <div>
                                <div class="text-xs font-semibold text-slate-900 dark:text-slate-200">{{ __('Live Digital Clock') }}</div>
                                <div class="text-[10px] text-slate-400">{{ __('Synchronized header punch clock') }}</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                            {{ __('Active') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/80 text-xs text-slate-400 flex items-center justify-between">
                <span>{{ __('Layout') }}: <strong>Fixed Viewport (h-screen)</strong></span>
                <span class="font-mono text-[11px]">AttendX Lite</span>
            </div>
        </div>

    </div>

    <!-- System Status & Environment Card -->
    <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs transition-colors">
        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-3">{{ __('System & Environment Information') }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                <div class="text-slate-400 text-[10px] uppercase font-semibold">{{ __('Framework') }}</div>
                <div class="font-bold text-slate-900 dark:text-slate-100 mt-1">Laravel {{ app()->version() }}</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                <div class="text-slate-400 text-[10px] uppercase font-semibold">{{ __('PHP Version') }}</div>
                <div class="font-bold text-slate-900 dark:text-slate-100 mt-1">PHP {{ phpversion() }}</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                <div class="text-slate-400 text-[10px] uppercase font-semibold">{{ __('Database') }}</div>
                <div class="font-bold text-slate-900 dark:text-slate-100 mt-1">SQLite 3 (Relational)</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                <div class="text-slate-400 text-[10px] uppercase font-semibold">{{ __('Geolocation') }}</div>
                <div class="font-bold text-emerald-600 dark:text-emerald-400 mt-1">OpenStreetMap Active</div>
            </div>
        </div>
    <!-- Account Profile & Password Settings Card -->
    <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs transition-colors">
        <div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ __('Account Profile & Security') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ __('Manage your personal workforce account details and password.') }}
                </p>
            </div>
            @if(isset($currentUser))
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $currentUser->isAdmin() ? 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300' : 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' }}">
                        <span>{{ __('Role:') }}</span>
                        <span class="uppercase tracking-wider">{{ $currentUser->role }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-teal-100 dark:bg-teal-950 text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-teal-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                        <span>{{ ucfirst($currentUser->status) }}</span>
                    </span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 1. Profile Information Form -->
            <form method="POST" action="{{ route('settings.profile') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="font-semibold text-xs text-slate-800 dark:text-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ __('Personal Information') }}</span>
                    </div>
                    @if($currentUser && $currentUser->role === 'employee')
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ __('Employee Profile') }}
                        </span>
                    @endif
                </div>

                <!-- Profile Avatar Upload Section (Form Files Only) -->
                <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700 flex items-center gap-4">
                    <div class="relative flex-shrink-0 group">
                        <img id="profileAvatarPreview" 
                             src="{{ $currentEmployee?->profile_image ?? $currentUser->profile_image ?? \App\Models\Employee::DEFAULT_AVATAR }}" 
                             alt="Profile Avatar" 
                             class="w-16 h-16 rounded-2xl object-cover border-2 border-brand-500/30 shadow-xs bg-slate-100 dark:bg-slate-700">
                        <div class="absolute -bottom-1 -right-1 bg-brand-600 text-white p-1 rounded-full shadow-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="profile_image" class="block text-xs font-semibold text-slate-800 dark:text-slate-200 mb-1">
                            {{ __('Profile Picture') }}
                        </label>
                        <input type="file" 
                               id="profile_image" 
                               name="profile_image" 
                               accept="image/png,image/jpeg,image/jpg,image/webp,image/gif" 
                               onchange="previewProfileImage(event)"
                               class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-950 dark:file:text-brand-300 transition cursor-pointer">
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('Only form file uploads allowed (JPG, PNG, WEBP, GIF, max 5MB).') }}</span>
                        </p>
                        @error('profile_image')
                            <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Full Name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $currentUser->name ?? '') }}" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Email Address') }} ({{ __('Read-Only') }})</label>
                    <input type="email" value="{{ $currentUser->email ?? '' }}" disabled class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 px-3.5 py-2 text-xs text-slate-500 dark:text-slate-400 cursor-not-allowed">
                    <p class="text-[10px] text-slate-400 mt-1">{{ __('Email and roles cannot be modified directly.') }}</p>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow-xs">
                    {{ __('Save Profile') }}
                </button>
            </form>

            <!-- 2. Change Password Form -->
            <form method="POST" action="{{ route('settings.password') }}" class="space-y-4">
                @csrf
                <div class="font-semibold text-xs text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>{{ __('Change Password') }}</span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Current Password') }}</label>
                    <input type="password" name="current_password" required placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('New Password') }}</label>
                        <input type="password" name="password" required placeholder="Min 6 chars" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Confirm Password') }}</label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat password" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow-xs">
                    {{ __('Update Password') }}
                </button>
            </form>
        </div>
    </div>

    @if(auth()->user() && auth()->user()->isAdmin())
        <!-- Telegram Bot Push Notifications Card (Admin Only) -->
        <div class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xs transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#229ED9] text-white flex items-center justify-center shadow-md shadow-[#229ED9]/25 flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.196 1.006.128.832.941z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <span>{{ __('Telegram Bot Login Push Alerts') }}</span>
                            @if(!empty($telegramConfig['chat_id']))
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    {{ __('Connected') }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    {{ __('Awaiting Chat ID') }}
                                </span>
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ __('Receive instant real-time security alerts on Telegram whenever an employee or administrator logs in.') }}
                        </p>
                    </div>
                </div>
                <a href="https://t.me/{{ $telegramConfig['bot'] ?? 'AttendanceLogSystemBot' }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#229ED9]/10 hover:bg-[#229ED9]/20 text-[#229ED9] transition border border-[#229ED9]/30 self-start sm:self-auto">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.196 1.006.128.832.941z"/></svg>
                    <span>@<span>{{ $telegramConfig['bot'] ?? 'AttendanceLogSystemBot' }}</span></span>
                </a>
            </div>

            <!-- Configuration Form -->
            <form method="POST" action="{{ route('settings.telegram') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="telegram_chat_id" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('Telegram Chat ID (Where alerts are delivered)') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <input type="text" 
                               id="telegram_chat_id" 
                               name="telegram_chat_id" 
                               value="{{ old('telegram_chat_id', $telegramConfig['chat_id'] ?? '') }}" 
                               required 
                               placeholder="e.g. 123456789 or -1001234567890" 
                               class="flex-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-xs font-mono text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#229ED9]/30 focus:border-[#229ED9]">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-[#229ED9] hover:bg-[#1E88E5] text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ __('Save & Test Alert') }}</span>
                        </button>
                    </div>
                    @error('telegram_chat_id')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guidance Box -->
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400 space-y-1.5">
                    <div class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                        <span>💡</span>
                        <span>{{ __('How to get your Telegram Chat ID in 10 seconds:') }}</span>
                    </div>
                    <ol class="list-decimal list-inside space-y-1 pl-1 text-[11px] leading-relaxed">
                        <li>{{ __('Open Telegram and search for:') }} <strong class="text-slate-800 dark:text-slate-200">@<span>{{ $telegramConfig['bot'] ?? 'AttendanceLogSystemBot' }}</span></strong> {{ __('and tap') }} <strong>START</strong> ({{ __('or type "hi"') }}).</li>
                        <li>{{ __('To find your numeric ID, message') }} <a href="https://t.me/userinfobot" target="_blank" class="text-[#229ED9] hover:underline font-bold">@userinfobot</a> {{ __('which immediately replies with your ID.') }}</li>
                        <li>{{ __('Paste the ID above and click "Save & Test Alert"!') }}</li>
                    </ol>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function selectThemeMode(mode) {
        if (typeof applyTheme === 'function') {
            applyTheme(mode);
        } else {
            localStorage.setItem('theme', mode);
            if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        updateSettingsCards(mode);

        // Flash saved badge
        const badge = document.getElementById('themeSaveStatus');
        if (badge) {
            badge.classList.remove('hidden');
            setTimeout(() => {
                badge.classList.add('hidden');
            }, 2500);
        }
    }

    function updateSettingsCards(mode) {
        document.querySelectorAll('[data-theme-card]').forEach(card => {
            const cardMode = card.getAttribute('data-theme-card');
            const isSelected = cardMode === mode;
            const dot = card.querySelector('.theme-check-dot');
            const checkContainer = card.querySelector('.theme-check');

            if (isSelected) {
                card.classList.add('border-brand-500', 'ring-2', 'ring-brand-500/20');
                card.classList.remove('border-slate-200', 'dark:border-slate-800');
                checkContainer?.classList.add('border-brand-600');
                checkContainer?.classList.remove('border-slate-300', 'dark:border-slate-600');
                dot?.classList.remove('hidden');
            } else {
                card.classList.remove('border-brand-500', 'ring-2', 'ring-brand-500/20');
                card.classList.add('border-slate-200', 'dark:border-slate-800');
                checkContainer?.classList.remove('border-brand-600');
                checkContainer?.classList.add('border-slate-300', 'dark:border-slate-600');
                dot?.classList.add('hidden');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const current = localStorage.getItem('theme') || '{{ session('theme', 'light') }}';
        updateSettingsCards(current);
    });

    function previewProfileImage(event) {
        const input = event.target;
        const file = input.files && input.files[0];
        if (!file) return;

        // Verify file is an image
        if (!file.type.match(/^image\//)) {
            alert('Please select a valid image form file (JPG, PNG, WEBP, GIF).');
            input.value = '';
            return;
        }

        // Verify file size does not exceed 5MB
        if (file.size > 5 * 1024 * 1024) {
            alert('File size exceeds the 5MB limit. Please upload a smaller image.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profileAvatarPreview');
            if (preview) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
