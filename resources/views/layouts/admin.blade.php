<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>@yield('title')</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .nav-item.active {
            background-color: rgba(209, 213, 219, 0.6) !important;
            color: #111827 !important;
        }

        @media(min-width: 765px){
            .fab {
                display: none !important;
            }
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden bg-gray-100 text-gray-800">

    <!-- HEADER -->
    <header class="h-16 w-full bg-[#f0f0f0] border-b border-gray-300 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20">
        <div class="flex items-center gap-4">
            <button id="toggleBtn" class="p-1 rounded-md hover:bg-gray-200 focus:outline-none transition-colors">
                <i class="ph ph-list text-3xl font-bold text-black"></i>
            </button>
            <div class="text-3xl font-medium tracking-tight text-black select-none">V</div>
        </div>
        <div class="flex items-center">
            <button class="relative inline-flex items-center h-7 rounded-full w-12 border border-black bg-transparent focus:outline-none">
                <span class="translate-x-6 inline-block w-5 h-5 transform bg-transparent rounded-full transition-transform items-center justify-center flex">
                    <i class="ph ph-moon text-lg text-black"></i>
                </span>
            </button>
        </div>
    </header>

    <!-- WRAPPER -->
    <div class="flex flex-1 overflow-hidden relative">

        <!-- SIDEBAR -->
        <aside id="sidebar" class="hidden sm:flex flex-col justify-between h-full bg-[#f0f0f0] w-64 transition-[width] duration-300 ease-in-out border-r border-gray-300 overflow-y-auto overflow-x-hidden">

            <!-- 
                NAV CONTAINER 
            -->
            <nav class="pt-4 px-3 space-y-2">

                <!-- Nav Item -->
                <a href="{{ route('admin.dashboard') }}" class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 hover:bg-gray-200/80 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="ph ph-house text-2xl shrink-0"></i>
                    <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Dashboard</span>
                </a>

                <a href="{{ route('admin.barang') }}" class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 hover:bg-gray-200/80 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.barang') ? 'active' : '' }}">
                    <i class="ph ph-package text-2xl shrink-0"></i>
                    <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Barang</span>
                </a>

                <a href="{{ route('admin.user') }}" class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 hover:bg-gray-200/80 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.user') ? 'active' : '' }}">
                    <i class="ph ph-user text-2xl shrink-0"></i>
                    <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">User</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="p-3 mb-2">
                <form action="" method="POST">
                    @csrf
                    <button type="submit" class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 hover:bg-gray-200/80 rounded-lg transition-all duration-200 w-full">
                        <i class="ph ph-sign-out text-2xl shrink-0 rotate-180"></i>
                        <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            @yield('content')
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const navItems = document.querySelectorAll('.nav-item');

        // Kita tidak perlu lagi memilih navContainer untuk diubah paddingnya,
        // karena padding container (px-3) dibiarkan statis.

        let isExpanded = true;
        let isAnimatingSidebar = false;

        toggleBtn.addEventListener('click', () => {
            if (isAnimatingSidebar) return;

            // Handle Mobile Toggle (< 640px)
            if (window.innerWidth < 640) {
                if (sidebar.classList.contains('hidden')) {
                    sidebar.classList.remove('hidden');
                    sidebar.classList.add('flex', 'absolute', 'inset-y-0', 'left-0', 'z-50', 'w-64');
                    sidebarTexts.forEach(text => {
                        text.classList.remove('hidden', 'opacity-0');
                    });
                    navItems.forEach(item => {
                        item.classList.remove('pl-4', 'pr-0');
                        item.classList.add('pl-3');
                    });
                } else {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('flex', 'absolute', 'inset-y-0', 'left-0', 'z-50', 'w-64');
                }
                return;
            }

            isAnimatingSidebar = true;
            isExpanded = !isExpanded;

            if (!isExpanded) {
                // === MENUTUP ===
                sidebarTexts.forEach(text => {
                    text.classList.add('opacity-0');
                    setTimeout(() => text.classList.add('hidden'), 200);
                });

                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');

                setTimeout(() => {
                    navItems.forEach(item => {
                        item.classList.remove('pl-3');
                        item.classList.add('pl-4', 'pr-0');
                    });
                    isAnimatingSidebar = false;
                }, 300);

            } else {
                // === MEMBUKA ===
                navItems.forEach(item => {
                    item.classList.remove('pl-4', 'pr-0');
                    item.classList.add('pl-3');
                });

                setTimeout(() => {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');

                    setTimeout(() => {
                        sidebarTexts.forEach(text => {
                            text.classList.remove('hidden');
                            requestAnimationFrame(() => {
                                text.classList.remove('opacity-0');
                            });
                        });
                        isAnimatingSidebar = false;
                    }, 150);
                }, 200);
            }
        });

        // Responsif Init & Resize
        function checkScreenSize() {
            const width = window.innerWidth;
            if (width < 640) {
                // Mobile
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex', 'absolute', 'inset-y-0', 'left-0', 'z-50', 'w-64', 'w-20');
                isExpanded = false;
            } else if (width < 768) {
                // Tablet (Collapsed)
                sidebar.classList.remove('hidden', 'absolute', 'inset-y-0', 'left-0', 'z-50');
                sidebar.classList.add('flex', 'w-20');
                sidebar.classList.remove('w-64');
                isExpanded = false;

                navItems.forEach(item => {
                    item.classList.remove('pl-3');
                    item.classList.add('pl-4', 'pr-0');
                });
                sidebarTexts.forEach(text => {
                    text.classList.add('opacity-0', 'hidden');
                });
            } else {
                // Desktop (Expanded)
                sidebar.classList.remove('hidden', 'absolute', 'inset-y-0', 'left-0', 'z-50', 'w-20');
                sidebar.classList.add('flex', 'w-64');
                isExpanded = true;

                navItems.forEach(item => {
                    item.classList.remove('pl-4', 'pr-0');
                    item.classList.add('pl-3');
                });
                sidebarTexts.forEach(text => {
                    text.classList.remove('hidden', 'opacity-0');
                });
            }
        }

        window.addEventListener('resize', checkScreenSize);
        checkScreenSize();
    </script>
    @yield('script')
</body>

</html>