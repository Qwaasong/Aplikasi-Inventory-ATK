<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inter-font.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        // Check for dark mode preference
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <title>@yield('title')</title>
</head>

<body class="flex flex-col h-screen overflow-hidden bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">

    <!-- HEADER -->
    <header class="h-16 w-full bg-[#f0f0f0] dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 transition-colors duration-300">
        <div class="flex items-center gap-4">
            <button id="toggleBtn" class="p-1 rounded-md hover:bg-gray-200 focus:outline-none transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256" class="text-black font-bold">
                    <path d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128ZM40,72H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16ZM216,184H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z"></path>
                </svg>
            </button>
        </div>
        <div class="flex items-center">
            <button id="darkModeToggle" type="button" class="relative inline-flex items-center h-7 rounded-full w-12 border border-gray-400 dark:border-gray-500 bg-gray-200 dark:bg-gray-700 focus:outline-none transition-colors duration-300">
                <span id="darkModeKnob" class="translate-x-1 dark:translate-x-6 inline-block w-5 h-5 transform bg-white dark:bg-gray-300 rounded-full transition-transform duration-300 items-center justify-center flex shadow-md">
                    <!-- Sun Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256" class="text-orange-500 block dark:hidden">
                        <path d="M120,40V16a8,8,0,0,1,16,0V40a8,8,0,0,1-16,0Zm72,88a64,64,0,1,1-64-64A64.07,64.07,0,0,1,192,128Zm-16,0a48,48,0,1,0-48,48A48.05,48.05,0,0,0,176,128ZM58.34,69.66A8,8,0,0,0,69.66,58.34l-16-16A8,8,0,0,0,42.34,53.66Zm0,116.68-16,16a8,8,0,0,0,11.32,11.32l16-16a8,8,0,0,0-11.32-11.32Zm139.32-116.68a8,8,0,0,0,11.32,11.32l16-16a8,8,0,0,0-11.32-11.32Zm0,116.68l16,16a8,8,0,0,0,11.32-11.32l-16-16a8,8,0,0,0-11.32,11.32ZM192,72a8,8,0,0,0,5.66-2.34l16-16a8,8,0,0,0-11.32-11.32l-16,16A8,8,0,0,0,192,72Zm-128,0a8,8,0,0,0,5.66-13.66l-16-16a8,8,0,0,0-11.32,11.32l16,16A8,8,0,0,0,64,72ZM128,216a8,8,0,0,0-8,8v24a8,8,0,0,0,16,0V224A8,8,0,0,0,128,216ZM216,120h24a8,8,0,0,0,0-16H216a8,8,0,0,0,0,16ZM16,120H40a8,8,0,0,0,0-16H16a8,8,0,0,0,0,16Z"></path>
                    </svg>
                    <!-- Moon Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256" class="text-blue-500 hidden dark:block">
                        <path d="M233.54,142.23a8,8,0,0,0-8-2,88.08,88.08,0,0,1-109.8-109.8,8,8,0,0,0-10-10,104.11,104.11,0,1,0,129.75,129.75A8,8,0,0,0,233.54,142.23ZM128,216a88.13,88.13,0,0,1-73.49-136.66,104.05,104.05,0,0,0,128.74,128.74A87.59,87.59,0,0,1,128,216Z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <!-- WRAPPER -->
    <div class="flex flex-1 overflow-hidden relative">

<aside id="sidebar" class="hidden sm:flex flex-col justify-between h-full bg-[#f0f0f0] dark:bg-gray-800 w-64 transition-all duration-300 ease-in-out border-r border-gray-300 dark:border-gray-700 overflow-y-auto overflow-x-hidden">

    <div class="p-2 flex flex-col gap-1">
    @auth
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-item group flex items-center justify-start px-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.dashboard') ? 'active bg-gray-200/50 dark:bg-gray-700/50' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                    <path d="M219.31,108.68l-80-73.34a16,16,0,0,0-22.62,0l-80,73.34A15.82,15.82,0,0,0,32,120.42V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V160h32v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V120.42A15.82,15.82,0,0,0,219.31,108.68ZM208,208H160V160a16,16,0,0,0-16-16H112a16,16,0,0,0-16,16v48H48V120.42l80-73.34,80,73.34Z"></path>
                </svg>
                <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Dashboard</span>
            </a>

            <a href="{{ route('admin.barang') }}" class="nav-item group flex items-center justify-start px-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.barang') ? 'active bg-gray-200/50 dark:bg-gray-700/50' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                    <path d="M223.68,66.15,135.68,18a15.88,15.88,0,0,0-15.36,0l-88,48.15a16,16,0,0,0-8.32,14V173.9a16,16,0,0,0,8.32,14l88,48.15a15.88,15.88,0,0,0,15.36,0l88-48.15a16,16,0,0,0,8.32-14V80.18A16,16,0,0,0,223.68,66.15ZM128,33.51l80,43.78-31.57,17.27L96.43,51.09ZM40,80.18l80-43.78V124.6L40,80.89Zm80,142.31L40,178.71V99.11L120,142.8Zm16-142.31V142.8l80-43.69v79.6Z"></path>
                </svg>
                <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Barang</span>
            </a>

            <a href="{{ route('admin.user') }}" class="nav-item group flex items-center justify-start px-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('admin.user') ? 'active bg-gray-200/50 dark:bg-gray-700/50' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                    <path d="M231.39,212.46A104,104,0,0,0,36.07,176.84a8,8,0,1,0,12.83,9.54,88,88,0,0,1,164.44,30.3,8,8,0,0,0,15.79-2.61ZM128,152a64,64,0,1,0-64-64A64.07,64.07,0,0,0,128,152Zm0-112a48,48,0,1,1-48,48A48.05,48.05,0,0,1,128,40Z"></path>
                </svg>
                <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">User</span>
            </a>

        @elseif(auth()->user()->role === 'staff')
            <a href="{{ route('staff.barang') }}" class="nav-item group flex items-center justify-start px-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('staff.barang') ? 'active bg-gray-200/50 dark:bg-gray-700/50' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                    <path d="M223.68,66.15,135.68,18a15.88,15.88,0,0,0-15.36,0l-88,48.15a16,16,0,0,0-8.32,14V173.9a16,16,0,0,0,8.32,14l88,48.15a15.88,15.88,0,0,0,15.36,0l88-48.15a16,16,0,0,0,8.32-14V80.18A16,16,0,0,0,223.68,66.15ZM128,33.51l80,43.78-31.57,17.27L96.43,51.09ZM40,80.18l80-43.78V124.6L40,80.89Zm80,142.31L40,178.71V99.11L120,142.8Zm16-142.31V142.8l80-43.69v79.6Z"></path>
                </svg>
                <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Barang</span>
            </a>

        @elseif(auth()->user()->role === 'supervisor')
            <a href="{{ route('supervisor.dashboard') }}" class="nav-item group flex items-center justify-start px-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full {{ Route::is('supervisor.dashboard') ? 'active bg-gray-200/50 dark:bg-gray-700/50' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                    <path d="M219.31,108.68l-80-73.34a16,16,0,0,0-22.62,0l-80,73.34A15.82,15.82,0,0,0,32,120.42V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V160h32v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V120.42A15.82,15.82,0,0,0,219.31,108.68ZM208,208H160V160a16,16,0,0,0-16-16H112a16,16,0,0,0-16,16v48H48V120.42l80-73.34,80,73.34Z"></path>
                </svg>
                <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Dashboard</span>
            </a>
        @endif

    @else
        <a href="{{ route('login') }}" class="nav-item group flex items-center justify-start px-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-gray-200/80 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                <path d="M224,128a8,8,0,0,1-8,8H112a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128Zm-101.66,42.34a8,8,0,0,0,11.32-11.32L108.69,128l25.17-25.17a8,8,0,0,0-11.32-11.32l-32,32a8,8,0,0,0,0,11.32ZM136,208a8,8,0,0,1-8,8H48a16,16,0,0,1-16-16V48A16,16,0,0,1,48,32h80a8,8,0,0,1,0,16H48V208h80A8,8,0,0,1,136,208Z"></path>
            </svg>
            <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Login</span>
        </a>
    @endauth
    </div>
          <!-- Logout -->
                   
           <form method="POST" action="{{ route('logout') }}" class="w-full">
               @csrf
               <button type="submit" class="nav-item group flex items-center justify-start pl-3 pr-3 py-3 text-gray-700 dark:text-gray-200 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition-all duration-200 w-full">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" class="shrink-0">
                       <path d="M120,216a8,8,0,0,1-8,8H48a16,16,0,0,1-16-16V48A16,16,0,0,1,48,32h64a8,8,0,0,1,0,16H48V208h64A8,8,0,0,1,120,216Zm109.66-93.66-40-40a8,8,0,0,0-11.32,11.32L204.69,120H112a8,8,0,0,0,0,16h92.69l-26.35,26.34a8,8,0,0,0,11.32,11.32l40-40A8,8,0,0,0,229.66,122.34Z"></path>
                   </svg>
                   <span class="ml-3 whitespace-nowrap font-medium sidebar-text transition-opacity duration-200">Logout</span>
               </button>
           </form>
        </aside>
        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('assets/js/admin-layout.js') }}"></script>
    <script src="{{ asset('assets/js/fab.js') }}"></script>
    @yield('script')
</body>

</html>