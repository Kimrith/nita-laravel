<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('429 Too Many Requests — Security Shield | AttendX') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Kantumruy Pro (Khmer) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Kantumruy Pro"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            500: '#3b82f6',
                            600: '#2563eb',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'radar': 'radar 3s linear infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        radar: {
                            '0%': { transform: 'scale(0.8)', opacity: '0.9' },
                            '100%': { transform: 'scale(2.2)', opacity: '0' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-6px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Kantumruy Pro', sans-serif;
            background: radial-gradient(circle at 50% 20%, #1e1b4b 0%, #0b0f19 50%, #030712 100%);
            min-height: 100vh;
        }
        .countdown-circle {
            stroke-dasharray: 283;
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 1s linear;
        }
        .equalizer-bar {
            animation: bounce 0.6s ease-in-out infinite alternate;
        }
        @keyframes bounce {
            0% { height: 4px; }
            100% { height: 18px; }
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between text-slate-100 antialiased selection:bg-rose-500 selection:text-white relative overflow-x-hidden">

    <!-- Ambient Grid & Glow Effects -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293715_1px,transparent_1px),linear-gradient(to_bottom,#1f293715_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-rose-600/15 rounded-full blur-[130px]"></div>
        <div class="absolute top-1/3 left-1/3 w-[350px] h-[350px] bg-amber-500/10 rounded-full blur-[110px]"></div>
    </div>

    <!-- Hidden Audio Player with Loop -->
    <audio id="rateLimitAudio" preload="auto" loop>
        <source src="{{ asset('sound/rate-limite -sound.mp3') }}" type="audio/mpeg">
    </audio>

    <!-- Header Navigation -->
    <header class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-600 to-amber-600 text-white flex items-center justify-center shadow-lg shadow-rose-500/20 group-hover:scale-105 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <div>
                <span class="text-base font-bold text-white tracking-tight">Attend<span class="text-rose-400">X</span></span>
                <span class="block text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Security Shield</span>
            </div>
        </a>

        <!-- Locale Switcher -->
        <div class="flex items-center gap-2 bg-slate-900/80 backdrop-blur border border-slate-800 rounded-xl p-1 text-xs">
            <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 rounded-lg font-medium transition {{ app()->getLocale() === 'en' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'text-slate-400 hover:text-white' }}">
                English
            </a>
            <a href="{{ route('locale.switch', 'km') }}" class="px-2.5 py-1 rounded-lg font-medium transition {{ app()->getLocale() === 'km' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'text-slate-400 hover:text-white' }}">
                ភាសាខ្មែរ
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 py-8">
        <div class="w-full max-w-xl">
            
            <!-- Security Glass Card -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-rose-500/30 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-rose-950/50 text-center relative overflow-hidden">
                
                <!-- Top Warning Header Banner -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold uppercase tracking-wider mb-6 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    <span>{{ __('HTTP 429 • Rate Limit Activated') }}</span>
                </div>

                <!-- Animated Radar Shield Icon -->
                <div class="relative w-28 h-28 mx-auto mb-6 flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full border border-rose-500/40 animate-radar"></div>
                    <div class="absolute inset-2 rounded-full border border-amber-500/30 animate-radar" style="animation-delay: 1s;"></div>
                    <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-tr from-rose-900/80 to-amber-900/60 border border-rose-500/40 flex items-center justify-center shadow-xl shadow-rose-500/20 animate-float">
                        <svg class="w-10 h-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Title & Explanation -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-2.5">
                    {{ __('Too Many Requests') }}
                </h1>
                <p class="text-sm text-slate-300 max-w-md mx-auto leading-relaxed mb-6">
                    {{ __('You have exceeded the maximum threshold of 3 attempts per minute. For security against brute-force attacks and abuse, your access is temporarily throttled.') }}
                </p>

                <!-- Circular Countdown Timer -->
                <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800/80 mb-6 flex flex-col sm:flex-row items-center justify-center gap-6">
                    
                    <!-- Progress Ring -->
                    <div class="relative w-24 h-24 flex items-center justify-center">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                            <!-- Background Track -->
                            <circle cx="50" cy="50" r="45" class="text-slate-800" stroke-width="8" stroke="currentColor" fill="none" />
                            <!-- Active Countdown Path -->
                            <circle id="countdownRing" cx="50" cy="50" r="45" 
                                    class="text-rose-500 countdown-circle" 
                                    stroke-width="8" 
                                    stroke-linecap="round" 
                                    stroke="currentColor" 
                                    fill="none" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span id="countdownNumber" class="font-mono text-2xl font-extrabold text-white">
                                {{ $seconds ?? 60 }}
                            </span>
                            <span class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold">
                                {{ __('sec') }}
                            </span>
                        </div>
                    </div>

                    <!-- Countdown Info Text -->
                    <div class="text-center sm:text-left">
                        <div class="text-xs font-semibold text-rose-400 uppercase tracking-wider mb-1 flex items-center justify-center sm:justify-start gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                            <span id="cooldownStatus">{{ __('Cooldown In Progress') }}</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-normal max-w-xs">
                            {{ __('The security lock will automatically lift once the timer reaches zero. Please wait patiently.') }}
                        </p>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    @php
                        $returnTarget = $returnUrl ?? url()->previous() ?? route('login');
                        if (str_contains($returnTarget, 'rate-limited')) {
                            $returnTarget = route('login');
                        }
                    @endphp
                    <a id="retryBtn" 
                       href="{{ $returnTarget }}" 
                       class="w-full sm:w-auto px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 inline-flex items-center justify-center gap-2 bg-slate-800 text-slate-400 cursor-not-allowed opacity-60 pointer-events-none">
                        <svg class="w-4 h-4 animate-spin hidden" id="retrySpinner" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <svg class="w-4 h-4" id="retryIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span id="retryBtnText">{{ __('Please Wait...') }}</span>
                    </a>

                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto px-6 py-3 rounded-xl font-semibold text-sm transition-colors border border-slate-700/80 hover:bg-slate-800 text-slate-300 hover:text-white inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span>{{ __('Return to Sign In') }}</span>
                    </a>
                </div>

            </div>

            <!-- Footer Help Notice -->
            <p class="text-center text-xs text-slate-500 mt-6">
                {{ __('Need immediate administrative access? Contact your workspace system administrator.') }}
            </p>

        </div>
    </main>

    <!-- Bottom Audio Control Bar & Notification -->
    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>AttendX Defense System • {{ config('app.name', 'AttendX') }}</span>
        </div>

        <!-- Audio Control Widget -->
        <div class="flex items-center gap-3 bg-slate-900/90 border border-slate-800 px-3.5 py-1.5 rounded-full shadow-lg">
            <!-- Audio Equalizer Animation -->
            <div id="audioVisualizer" class="flex items-center gap-0.5 h-4 opacity-50">
                <span class="equalizer-bar w-1 bg-rose-400 rounded-full" style="animation-delay: 0.1s;"></span>
                <span class="equalizer-bar w-1 bg-rose-500 rounded-full" style="animation-delay: 0.3s;"></span>
                <span class="equalizer-bar w-1 bg-amber-400 rounded-full" style="animation-delay: 0.2s;"></span>
                <span class="equalizer-bar w-1 bg-rose-400 rounded-full" style="animation-delay: 0.4s;"></span>
            </div>

            <span id="audioStatusText" class="text-[11px] font-medium text-slate-300">
                {{ __('Background Sound') }}
            </span>

            <button id="toggleAudioBtn" 
                    type="button" 
                    class="p-1 rounded-full hover:bg-slate-800 text-rose-400 hover:text-rose-300 transition focus:outline-none" 
                    title="Toggle sound playback">
                <!-- Sound On Icon -->
                <svg id="soundOnIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                </svg>
                <!-- Sound Off Icon -->
                <svg id="soundOffIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Script for Live Countdown and Background Audio Handling -->
    <script>
        (function() {
            let totalSeconds = parseInt("{{ $seconds ?? 60 }}", 10);
            if (isNaN(totalSeconds) || totalSeconds <= 0) totalSeconds = 60;
            
            const initialSeconds = totalSeconds;
            let currentSeconds = totalSeconds;

            const countdownNumEl = document.getElementById('countdownNumber');
            const countdownRingEl = document.getElementById('countdownRing');
            const cooldownStatusEl = document.getElementById('cooldownStatus');
            const retryBtn = document.getElementById('retryBtn');
            const retryBtnText = document.getElementById('retryBtnText');
            const retryIcon = document.getElementById('retryIcon');

            // Circumference of 2 * PI * 45 ≈ 282.74
            const circumference = 2 * Math.PI * 45;
            countdownRingEl.style.strokeDasharray = circumference;

            function updateRing(left, total) {
                const fraction = left / total;
                const offset = circumference * (1 - fraction);
                countdownRingEl.style.strokeDashoffset = offset;
            }

            updateRing(currentSeconds, initialSeconds);

            const timerInterval = setInterval(function() {
                currentSeconds--;

                if (currentSeconds > 0) {
                    countdownNumEl.textContent = currentSeconds;
                    updateRing(currentSeconds, initialSeconds);
                } else {
                    clearInterval(timerInterval);
                    countdownNumEl.textContent = "0";
                    updateRing(0, initialSeconds);

                    // Shield Unlocked State
                    countdownRingEl.classList.remove('text-rose-500');
                    countdownRingEl.classList.add('text-emerald-500');
                    
                    cooldownStatusEl.textContent = "{{ __('Shield Unlocked') }}";
                    cooldownStatusEl.classList.remove('text-rose-400');
                    cooldownStatusEl.classList.add('text-emerald-400');

                    // Activate Retry Button
                    retryBtn.classList.remove('bg-slate-800', 'text-slate-400', 'cursor-not-allowed', 'opacity-60', 'pointer-events-none');
                    retryBtn.classList.add('bg-gradient-to-r', 'from-emerald-600', 'to-teal-600', 'hover:from-emerald-500', 'hover:to-teal-500', 'text-white', 'shadow-lg', 'shadow-emerald-500/25', 'cursor-pointer');
                    retryBtnText.textContent = "{{ __('Try Again Now ↗') }}";
                }
            }, 1000);

            // ==========================================
            // Audio Playback with Autoplay Policy Handlers
            // ==========================================
            const audio = document.getElementById('rateLimitAudio');
            const toggleAudioBtn = document.getElementById('toggleAudioBtn');
            const soundOnIcon = document.getElementById('soundOnIcon');
            const soundOffIcon = document.getElementById('soundOffIcon');
            const visualizer = document.getElementById('audioVisualizer');
            const audioStatusText = document.getElementById('audioStatusText');

            function setAudioPlayingUI(isPlaying) {
                if (isPlaying) {
                    soundOnIcon.classList.remove('hidden');
                    soundOffIcon.classList.add('hidden');
                    visualizer.classList.remove('opacity-50');
                    visualizer.classList.add('opacity-100');
                    audioStatusText.textContent = "{{ __('Audio Playing') }}";
                } else {
                    soundOnIcon.classList.add('hidden');
                    soundOffIcon.classList.remove('hidden');
                    visualizer.classList.remove('opacity-100');
                    visualizer.classList.add('opacity-50');
                    audioStatusText.textContent = "{{ __('Tap To Play Sound') }}";
                }
            }

            function tryPlayAudio() {
                audio.volume = 0.75;
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.then(function() {
                        setAudioPlayingUI(true);
                    }).catch(function(error) {
                        // Browser autoplay policy blocked unmuted sound
                        setAudioPlayingUI(false);
                    });
                }
            }

            // Attempt autoplay on load
            window.addEventListener('load', tryPlayAudio);
            document.addEventListener('DOMContentLoaded', tryPlayAudio);

            // First user interaction (click or keypress anywhere on screen) unlocks audio if blocked
            function onFirstUserGesture() {
                if (audio.paused) {
                    tryPlayAudio();
                }
                window.removeEventListener('click', onFirstUserGesture);
                window.removeEventListener('keydown', onFirstUserGesture);
                window.removeEventListener('touchstart', onFirstUserGesture);
            }

            window.addEventListener('click', onFirstUserGesture);
            window.addEventListener('keydown', onFirstUserGesture);
            window.addEventListener('touchstart', onFirstUserGesture);

            // Manual toggle button
            toggleAudioBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (audio.paused) {
                    audio.play().then(function() {
                        setAudioPlayingUI(true);
                    }).catch(function(err) {
                        console.warn(err);
                    });
                } else {
                    audio.pause();
                    setAudioPlayingUI(false);
                }
            });

        })();
    </script>
</body>
</html>
