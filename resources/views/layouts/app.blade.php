<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AttendX — Attendance Management System')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen w-full flex flex-col bg-slate-50 text-slate-800 antialiased">

    <!-- Top Full-Width Navbar -->
    @include('components.header')

    <!-- Full-Width Fluid App Shell -->
    <div class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Sidebar (Collapsible on mobile, 3-column span on lg screens) -->
            <aside id="sidebarMenu" class="hidden lg:block lg:col-span-3 xl:col-span-3 transition-all duration-200">
                @include('components.sidebar')
            </aside>

            <!-- Main Content Area (12-column on mobile, 9-column span on lg screens) -->
            <main class="col-span-1 lg:col-span-9 xl:col-span-9 w-full min-w-0 space-y-6">
                <!-- Alerts / Flash Notifications -->
                @include('components.alerts')

                <!-- Dynamic Page Content -->
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Modals -->
    @include('components.modals.clock-modal')
    @include('components.modals.leave-modal')
    @include('components.modals.employee-modal')

    <!-- Footer -->
    @include('components.footer')

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

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarMenu');
            if (sidebar) {
                sidebar.classList.toggle('hidden');
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
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            // Geolocation auto-detection for punch clock
            if (id === 'clockModal' && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const latInput = document.getElementById('geoLat');
                    const lngInput = document.getElementById('geoLng');
                    if (latInput && lngInput) {
                        latInput.value = pos.coords.latitude;
                        lngInput.value = pos.coords.longitude;
                    }
                }, () => {});
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
    </script>
    @stack('scripts')
</body>
</html>
