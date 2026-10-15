<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AttendX — Attendance Management System')</title>

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

    <!-- Theme Initialization (Runs before render to prevent flash) -->
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

        /* Dark Mode Global Design Overrides */
        html.dark {
            color-scheme: dark;
        }
        html.dark, html.dark body {
            background-color: #0b1120 !important;
            color: #f1f5f9 !important;
        }
        html.dark header {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        html.dark aside,
        html.dark aside > div {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        html.dark main {
            background-color: #0b1120 !important;
        }
        html.dark footer {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #94a3b8 !important;
        }
        html.dark .bg-white {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        html.dark .bg-slate-50 {
            background-color: #0b1120 !important;
        }
        html.dark .bg-slate-100,
        html.dark .bg-slate-100\/90 {
            background-color: #1f2937 !important;
            color: #e2e8f0 !important;
        }
        html.dark .border-slate-100,
        html.dark .border-slate-200,
        html.dark .border-slate-200\/80,
        html.dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html.dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #1f2937 !important;
        }
        html.dark .text-slate-900,
        html.dark .text-slate-800 {
            color: #f8fafc !important;
        }
        html.dark .text-slate-700 {
            color: #e2e8f0 !important;
        }
        html.dark .text-slate-600,
        html.dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        html.dark .text-slate-400 {
            color: #64748b !important;
        }
        html.dark thead.bg-slate-50,
        html.dark thead {
            background-color: #182234 !important;
            color: #94a3b8 !important;
        }
        html.dark tr.hover\:bg-slate-50\/70:hover,
        html.dark tr.hover\:bg-slate-50\/60:hover,
        html.dark tr:hover {
            background-color: #182234 !important;
        }
        html.dark a.hover\:bg-slate-100:hover,
        html.dark button.hover\:bg-slate-100:hover {
            background-color: #1f2937 !important;
        }
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #0f172a !important;
            border-color: #374151 !important;
            color: #f8fafc !important;
        }
        html.dark .modal-content,
        html.dark .modal-container > div:not(.modal-backdrop) {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }

        /* Glassmorphism Modal & Sidebar Backdrops */
        .modal-backdrop,
        html.dark .modal-backdrop {
            background-color: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
        }
        #sidebarBackdrop,
        html.dark #sidebarBackdrop {
            background-color: rgba(15, 23, 42, 0.6) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
        }
    
        /* Global Table Readability Enhancement */
        table {
            font-size: 0.875rem !important; /* 14px (text-sm) */
        }
        table thead th {
            font-size: 0.75rem !important; /* 12px (text-xs) */
            font-weight: 600 !important;
            letter-spacing: 0.025em !important;
        }
        table tbody td {
            font-size: 0.875rem !important; /* 14px (text-sm) */
        }
    </style>
    @stack('styles')
</head>
<body class="h-screen w-full flex flex-col bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased overflow-hidden">

    <!-- Top Full-Width Navbar -->
    @include('components.header')

    <!-- Full-Screen App Shell -->
    <div class="flex-1 w-full flex overflow-hidden min-h-0 relative">
        
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden hidden backdrop-blur-xs transition-opacity"></div>

        <!-- Sidebar (Full height docked on desktop, slide-in off-canvas on mobile) -->
        <aside id="sidebarMenu" class="fixed inset-y-0 left-0 z-50 w-64 xl:w-72 bg-white border-r border-slate-200 flex flex-col h-full overflow-y-auto transform -translate-x-full lg:translate-x-0 lg:static lg:flex shrink-0 transition-transform duration-200 ease-in-out shadow-lg lg:shadow-none">
            @include('components.sidebar')
        </aside>

        <!-- Main Viewport Area (Scrollable content with fixed pinned footer) -->
        <div class="flex-1 min-w-0 h-full flex flex-col bg-slate-50 dark:bg-slate-950 overflow-hidden">
            <main class="flex-1 min-w-0 overflow-y-auto flex flex-col">
                <div class="p-4 sm:p-6 lg:p-8 flex-1 space-y-6 w-full">
                    <!-- Alerts / Flash Notifications -->
                    @include('components.alerts')

                    <!-- Dynamic Page Content -->
                    @yield('content')
                </div>
            </main>

            <!-- Fixed / Pinned Footer (Permanently visible at bottom, never scrolls away) -->
            @include('components.footer')
        </div>

    </div>

    <!-- Modals -->
    @include('components.modals.clock-modal')
    @include('components.modals.leave-modal')
    @include('components.modals.edit-leave-modal')
    @include('components.modals.employee-modal')
    @include('components.modals.department-modal')
    @include('components.modals.role-modal')
    @include('components.modals.shift-pattern-modal')
    @include('components.modals.leave-type-modal')
    @include('components.modals.delete-confirm-modal')
    @include('components.modals.no-entitlement-modal')

    <!-- Global App Scripts -->
    <script>
        // Live HH:MM:SS Digital Clock
        function updateLiveClock() {
            const clockEl = document.getElementById('liveClock');
            if (clockEl) {
                const now = new Date();
                clockEl.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Fullscreen Toggle
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().catch(() => {});
                }
            }
        }
        document.addEventListener('fullscreenchange', () => {
            const isFull = !!document.fullscreenElement;
            const exp = document.getElementById('fullscreenExpandIcon');
            const comp = document.getElementById('fullscreenCompressIcon');
            if (exp && comp) {
                exp.classList.toggle('hidden', isFull);
                comp.classList.toggle('hidden', !isFull);
            }
        });

        // Mobile Sidebar Drawer Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarMenu');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) {
                const isClosed = sidebar.classList.contains('-translate-x-full');
                if (isClosed) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop?.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop?.classList.add('hidden');
                }
            }
        }

        // Tab Navigation Switcher
        function switchTab(tabId) {
            // Update active state on buttons across header/sidebar
            document.querySelectorAll('[data-tab-target]').forEach(el => {
                if (el.getAttribute('data-tab-target') === tabId) {
                    el.classList.add('bg-brand-50', 'text-brand-600', 'font-semibold');
                    el.classList.remove('text-slate-600', 'hover:bg-slate-100');
                } else {
                    el.classList.remove('bg-brand-50', 'text-brand-600', 'font-semibold');
                    el.classList.add('text-slate-600', 'hover:bg-slate-100');
                }
            });

            // Show active tab panel
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            const targetPanel = document.getElementById(tabId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }

            // Close mobile sidebar after tab selection on mobile screens
            if (window.innerWidth < 1024) {
                const sidebar = document.getElementById('sidebarMenu');
                if (sidebar && !sidebar.classList.contains('hidden')) {
                    sidebar.classList.add('hidden');
                }
            }
        }

        document.querySelectorAll('[data-tab-target]').forEach(btn => {
            btn.addEventListener('click', () => {
                switchTab(btn.getAttribute('data-tab-target'));
            });
        });

        // Modals Control
        function openModal(id) {
            // Guard: Check if employee has 0 Total Entitled before allowing leave modal to open
            if (id === 'leaveModal') {
                @php
                    $layoutUser = auth()->user();
                    $layoutEmp = $layoutUser ? $layoutUser->getAssociatedEmployee() : null;
                    $layoutIsAdmin = $layoutUser && $layoutUser->isAdmin();
                    $layoutEntitled = $layoutEmp ? (float)$layoutEmp->total_annual_leave_entitlement : 0;
                @endphp
                const isUserAdmin = {{ $layoutIsAdmin ? 'true' : 'false' }};
                const userEntitled = {{ $layoutEntitled }};

                if (!isUserAdmin && userEntitled <= 0) {
                    const noEntModal = document.getElementById('noEntitlementModal');
                    if (noEntModal) {
                        noEntModal.classList.remove('hidden');
                        noEntModal.classList.add('flex');
                    } else {
                        alert("{{ __('You cannot submit a leave request because you do not have any statutory leave entitlement (Total Entitled is 0).') }}");
                    }
                    return;
                }
            }

            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            // Geolocation auto-detection for punch clock and leave requests
            if (id === 'clockModal' || id === 'leaveModal') {
                const isClock = id === 'clockModal';
                const badge = document.getElementById(isClock ? 'clockGeoBadge' : 'leaveGeoBadge');
                const text = document.getElementById(isClock ? 'clockGeoText' : 'leaveGeoText');
                const latInput = document.getElementById(isClock ? 'geoLat' : 'leaveGeoLat');
                const lngInput = document.getElementById(isClock ? 'geoLng' : 'leaveGeoLng');

                if (badge && text) {
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                    text.textContent = 'Acquiring location...';
                }

                const applyCoords = (lat, lng, label) => {
                    if (latInput && lngInput) {
                        latInput.value = lat;
                        lngInput.value = lng;
                    }
                    if (text) {
                        text.textContent = `${label} (${Number(lat).toFixed(3)}, ${Number(lng).toFixed(3)})`;
                    }
                };

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        pos => applyCoords(pos.coords.latitude, pos.coords.longitude, '📍 GPS Captured'),
                        err => {
                            // On desktop without hardware GPS or if permission blocked:
                            // Fall back to default workplace coordinates (SETEC Institute, Phnom Penh)
                            applyCoords(11.5454, 104.8893, '📍 Workplace Location');
                        },
                        { timeout: 5000, enableHighAccuracy: false, maximumAge: 300000 }
                    );
                } else {
                    applyCoords(11.5454, 104.8893, '📍 Workplace Location');
                }
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop')) {
                e.target.closest('.modal-container')?.classList.add('hidden');
                e.target.closest('.modal-container')?.classList.remove('flex');
            }
        });

        // Client-side Table Search Filter
        function filterTable(input, tableId) {
            const query = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            if (!table) return;
            const rows = table.getElementsByTagName('tbody')[0]?.getElementsByTagName('tr');
            if (!rows) return;

            for (let i = 0; i < rows.length; i++) {
                const text = rows[i].textContent || rows[i].innerText;
                rows[i].style.display = text.toLowerCase().includes(query) ? '' : 'none';
            }
        }

        // Theme Controller (Light, Dark, System)
        function applyTheme(theme) {
            localStorage.setItem('theme', theme);
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Update Quick Toggle Icons in Header
            const sun = document.getElementById('headerSunIcon');
            const moon = document.getElementById('headerMoonIcon');
            if (sun && moon) {
                if (isDark) {
                    sun.classList.remove('hidden');
                    moon.classList.add('hidden');
                } else {
                    sun.classList.add('hidden');
                    moon.classList.remove('hidden');
                }
            }

            // Sync with settings cards if on settings page
            if (typeof updateSettingsCards === 'function') {
                updateSettingsCards(theme);
            }

            // Sync to server-side session via async fetch
            fetch("{{ route('settings.theme') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ theme: theme })
            }).catch(() => {});
        }

        function toggleThemeQuick() {
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(isDark ? 'light' : 'dark');
        }

        // Auto-detect OS scheme changes when set to system
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            const current = localStorage.getItem('theme') || '{{ session('theme', 'light') }}';
            if (current === 'system') {
                applyTheme('system');
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = localStorage.getItem('theme') || '{{ session('theme', 'light') }}';
            applyTheme(currentTheme);
        });
    </script>
    @stack('scripts')
</body>
</html>
