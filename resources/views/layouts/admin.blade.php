<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inter-font.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-layout.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <title>@yield('title')</title>

    <style>
        :root {
            /* Light Mode Variables */
            --bg-main: #f1f5f9;
            --bg-sidebar: #f8fafc;
            --bg-header: #ffffff;
            --bg-card: #ffffff;
            --bg-input: #ffffff;
            --bg-modal: #ffffff;
            --border-primary: #e2e8f0;
            --border-input: #d1d5db;
            --border-focus: #3b82f6;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --text-heading: #0f172a;
            --accent-primary: #3b82f6;
            --accent-danger: #ef4444;
            --accent-success: #22c55e;
            --accent-info: #3b82f6;
            --nav-active-bg: #eff6ff;
            --nav-active-text: #2563eb;
            --nav-hover-bg: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .dark {
            /* Dark Mode Variables */
            --bg-main: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-header: #1e293b;
            --bg-card: #1e293b;
            --bg-input: #334155;
            --bg-modal: #1e293b;
            --border-primary: #334155;
            --border-input: #475569;
            --border-focus: #60a5fa;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --text-heading: #f8fafc;
            --accent-primary: #60a5fa;
            --accent-danger: #f87171;
            --accent-success: #4ade80;
            --accent-info: #60a5fa;
            --nav-active-bg: #334155;
            --nav-active-text: #60a5fa;
            --nav-hover-bg: #334155;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
        }

        #sidebar {
            background-color: var(--bg-sidebar);
            border-color: var(--border-primary);
        }

        #header {
            background-color: var(--bg-header);
            border-color: var(--border-primary);
        }

        h1,
        h2,
        h3,
        h4 {
            color: var(--text-heading);
        }

        label {
            color: var(--text-muted);
        }

        .nav-item.active {
            background-color: var(--nav-active-bg) !important;
            color: var(--nav-active-text) !important;
        }

        .nav-item:hover {
            background-color: var(--nav-hover-bg) !important;
        }

        .input-field {
            background-color: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-main);
        }

        .input-field:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* Sidebar Scrollbar */
        #sidebar-nav-container::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar-nav-container::-webkit-scrollbar-thumb {
            background: var(--border-primary);
            border-radius: 10px;
        }

        #sidebar-nav-container::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">

    <!-- HEADER -->
    <header id="header" class="fixed top-0 left-0 right-0 h-16 bg-[#f0f0f0] dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 flex items-center justify-between px-4 sm:px-6 z-40 transition-all duration-300">
        <div class="flex items-center gap-4">
            <button id="toggleBtn" class="p-1 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"
                    class="dark:text-white font-bold">
                    <path
                        d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128ZM40,72H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16ZM216,184H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z">
                    </path>
                </svg>
            </button>
        </div>
        <div class="flex items-center">
            <button id="darkModeToggle" type="button"
                class="relative inline-flex items-center h-7 rounded-full w-12 border border-gray-400 dark:border-gray-500 bg-gray-200 dark:bg-gray-700 focus:outline-none transition-colors duration-300">
                <span id="darkModeKnob"
                    class="translate-x-1 dark:translate-x-6 inline-block w-5 h-5 transform bg-white dark:bg-gray-300 rounded-full transition-transform duration-300 items-center justify-center flex shadow-md">
                    <!-- Sun Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        viewBox="0 0 256 256" class="text-orange-500 block dark:hidden">
                        <path
                            d="M120,40V16a8,8,0,0,1,16,0V40a8,8,0,0,1-16,0Zm72,88a64,64,0,1,1-64-64A64.07,64.07,0,0,1,192,128Zm-16,0a48,48,0,1,0-48,48A48.05,48.05,0,0,0,176,128ZM58.34,69.66A8,8,0,0,0,69.66,58.34l-16-16A8,8,0,0,0,42.34,53.66Zm0,116.68-16,16a8,8,0,0,0,11.32,11.32l16-16a8,8,0,0,0-11.32-11.32Zm139.32-116.68a8,8,0,0,0,11.32,11.32l16-16a8,8,0,0,0-11.32-11.32Zm0,116.68l16,16a8,8,0,0,0,11.32-11.32l-16-16a8,8,0,0,0-11.32,11.32ZM192,72a8,8,0,0,0,5.66-2.34l16-16a8,8,0,0,0-11.32-11.32l-16,16A8,8,0,0,0,192,72Zm-128,0a8,8,0,0,0,5.66-13.66l-16-16a8,8,0,0,0-11.32,11.32l16,16A8,8,0,0,0,64,72ZM128,216a8,8,0,0,0-8,8v24a8,8,0,0,0,16,0V224A8,8,0,0,0,128,216ZM216,120h24a8,8,0,0,0,0-16H216a8,8,0,0,0,0,16ZM16,120H40a8,8,0,0,0,0-16H16a8,8,0,0,0,0,16Z">
                        </path>
                    </svg>
                    <!-- Moon Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        viewBox="0 0 256 256" class="text-blue-500 hidden dark:block">
                        <path
                            d="M233.54,142.23a8,8,0,0,0-8-2,88.08,88.08,0,0,1-109.8-109.8,8,8,0,0,0-10-10,104.11,104.11,0,1,0,129.75,129.75A8,8,0,0,0,233.54,142.23ZM128,216a88.13,88.13,0,0,1-73.49-136.66,104.05,104.05,0,0,0,128.74,128.74A87.59,87.59,0,0,1,128,216Z">
                        </path>
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed top-16 left-0 bottom-0 w-64 bg-[#f0f0f0] dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 transition-all duration-300 ease-in-out z-30 flex flex-col -translate-x-full sm:translate-x-0">

        <!-- NAV CONTAINER (Scrollable) -->
        <nav id="sidebar-nav-container" class="flex-grow pt-4 px-3 space-y-2 overflow-y-auto overflow-x-hidden">

            <!-- Nav Item -->
            @can('admin-only')
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="currentColor" class="shrink-0">
                        <path
                            d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                    </svg>
                    <span
                        class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Dashboard</span>
                </a>
            @endcan

            @can('akses-laporan')
                <a href="{{ route('supervisor.dashboard') }}"
                    class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('supervisor.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="currentColor" class="shrink-0">
                        <path
                            d="M280-280h80v-200h-80v200Zm320 0h80v-400h-80v400Zm-160 0h80v-120h-80v120Zm0-200h80v-80h-80v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                    </svg>
                    <span
                        class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Dashboard</span>
                </a>
            @endcan

            @can('akses-barang')
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.barang') : route('staff.barang')}}"
                    class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.barang') || Route::is('staff.barang') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="currentColor" class="shrink-0">
                        <path
                            d="m400-570 80-40 80 40v-190H400v190ZM280-280v-80h200v80H280Zm-80 160q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-640v560-560Zm0 560h560v-560H640v320l-160-80-160 80v-320H200v560Z" />
                    </svg>
                    <span
                        class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Barang</span>
                </a>
            @endcan

            @can('admin-only')
                <a href="{{ route('admin.user') }}"
                    class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.user') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="currentColor" class="shrink-0">
                        <path
                            d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                    </svg>
                    <span
                        class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">User</span>
                </a>
            @endcan
        </nav>

        <!-- Logout (Fixed at Bottom) -->
        <div class="px-3 pb-4 pt-4 border-t border-gray-300 dark:border-gray-700 bg-[#f0f0f0] dark:bg-gray-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="currentColor" class="shrink-0">
                        <path
                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                    </svg>
                    <span
                        class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main id="main-content" class="pt-16 sm:pl-64 transition-all duration-300 ease-in-out min-h-screen">
        <div class="p-4 sm:p-6">
            @yield('content')
        </div>
    </main>
    <script src="{{ asset('assets/js/admin-layout.js') }}"></script>
    <script src="{{ asset('assets/js/fab.js') }}"></script>
    @yield('script')
</body>

</html>