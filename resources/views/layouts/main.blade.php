<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @yield('style')
    @stack('styles')
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/chartjs/dist/chart.umd.js') }}"></script>
</head>

<body>
    <div class="flex flex-col min-h-[100dvh] overflow-x-hidden">
        <!-- Header Destop -->
        <header class="hidden md:flex border-b border-gray-200">
            <div class="container py-4 px-10">
                <h1 class="text-xl font-semibold text-gray-800">KopsisApp</h1>
            </div>
        </header>

        <!-- Header Mobile -->
        <header class="md:hidden flex justify-between items-center p-4 border-b">
            <button id="menu-btn" class="text-gray-600 focus:outline-none" aria-label="Buka Sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </header>

        <!-- (Sidebar + Content) -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar untuk Desktop -->
            <aside class="hidden md:flex md:flex-col md:w-64 bg-white border-r border-gray-200">
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="{{ route('beranda.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('beranda.index') || request()->is('beranda*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                        <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" height="24"
                            width="24" viewBox="0 -960 960 960" fill="currentColor">
                            <path
                                d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                        </svg>
                        Beranda
                    </a>

                    <!-- Menu Dropdown -->
                    <details open class="group">
                        <summary
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer text-gray-600">
                            <div class="flex items-center">
                                Data Master
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="{{ route('vendor.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('vendor.index') || request()->is('vendor*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="M841-518v318q0 33-23.5 56.5T761-120H201q-33 0-56.5-23.5T121-200v-318q-23-21-35.5-54t-.5-72l42-136q8-26 28.5-43t47.5-17h556q27 0 47 16.5t29 43.5l42 136q12 39-.5 71T841-518Zm-272-42q27 0 41-18.5t11-41.5l-22-140h-78v148q0 21 14 36.5t34 15.5Zm-180 0q23 0 37.5-15.5T441-612v-148h-78l-22 140q-4 24 10.5 42t37.5 18Zm-178 0q18 0 31.5-13t16.5-33l22-154h-78l-40 134q-6 20 6.5 43t41.5 23Zm540 0q29 0 42-23t6-43l-42-134h-76l22 154q3 20 16.5 33t31.5 13ZM201-200h560v-282q-5 2-6.5 2H751q-27 0-47.5-9T663-518q-18 18-41 28t-49 10q-27 0-50.5-10T481-518q-17 18-39.5 28T393-480q-29 0-52.5-10T299-518q-21 21-41.5 29.5T211-480h-4.5q-2.5 0-5.5-2v282Zm560 0H201h560Z" />
                                </svg>
                                Vendor
                            </a>
                            <a href="{{ route('produk.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('produk.index') || request()->is('produk*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="m400-570 80-40 80 40v-190H400v190ZM280-280v-80h200v80H280Zm-80 160q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-640v560-560Zm0 560h560v-560H640v320l-160-80-160 80v-320H200v560Z" />
                                </svg>
                                Produk
                            </a>
                        </div>
                    </details>

                    <!-- Menu lainnya dengan pola yang sama -->
                    <details open class="group">
                        <summary
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                Manajemen Stok
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="{{ route('stok_terkini.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('stok_terkini.index') || request()->is('stok_terkini*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="M80-80v-160h800v160H760v-80H540v80H420v-80H200v80H80Zm160-240q-17 0-28.5-11.5T200-360v-480q0-17 11.5-28.5T240-880h480q17 0 28.5 11.5T760-840v480q0 17-11.5 28.5T720-320H240Zm40-80h400v-400H280v400Zm80-240h240v-80H360v80Zm-80 240v-400 400Z" />
                                </svg>
                                Stok Terkini
                            </a>
                            <a href="{{ route('barang_masuk.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('barang_masuk.index') || request()->is('barang_masuk*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z" />
                                </svg>
                                Barang Masuk
                            </a>
                            <a href="{{ route('barang_keluar.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('barang_keluar.index') || request()->is('barang_keluar*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="M856-390 570-104q-12 12-27 18t-30 6q-15 0-30-6t-27-18L103-457q-11-11-17-25.5T80-513v-287q0-33 23.5-56.5T160-880h287q16 0 31 6.5t26 17.5l352 353q12 12 17.5 27t5.5 30q0 15-5.5 29.5T856-390ZM513-160l286-286-353-354H160v286l353 354ZM260-640q25 0 42.5-17.5T320-700q0-25-17.5-42.5T260-760q-25 0-42.5 17.5T200-700q0 25 17.5 42.5T260-640Zm220 160Z" />
                                </svg>
                                Barang Keluar
                            </a>
                        </div>
                    </details>

                    <details open class="group">
                        <summary
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                Keuangan
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="{{ route('ledger_entries.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('ledger_entries.index') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 640 640" fill="currentColor" width="24" height="24">
                                    <path
                                        d="M31 169C21.6 159.6 21.6 144.4 31 135.1L103 63C112.4 53.6 127.6 53.6 136.9 63C146.2 72.4 146.3 87.6 136.9 96.9L105.9 127.9L173.6 127.9L173.6 127.9L511.9 127.9C547.2 127.9 575.9 156.6 575.9 191.9L575.9 370.1L570.8 365C542.7 336.9 497.1 336.9 469 365C441.8 392.2 440.9 435.6 466.2 463.9L533.9 463.9L502.9 432.9C493.5 423.5 493.5 408.3 502.9 399C512.3 389.7 527.5 389.6 536.8 399L608.8 471C618.2 480.4 618.2 495.6 608.8 504.9L536.8 576.9C527.4 586.3 512.2 586.3 502.9 576.9C493.6 567.5 493.5 552.3 502.9 543L533.9 512L127.8 512C92.5 512 63.8 483.3 63.8 448L63.8 269.8L68.9 274.9C97 303 142.6 303 170.7 274.9C197.9 247.7 198.8 204.3 173.5 176L105.8 176L136.8 207C146.2 216.4 146.2 231.6 136.8 240.9C127.4 250.2 112.2 250.3 102.9 240.9L31 169zM416 320C416 267 373 224 320 224C267 224 224 267 224 320C224 373 267 416 320 416C373 416 416 373 416 320zM504 255.5C508.4 256 512 252.4 512 248L512 200C512 195.6 508.4 192 504 192L456 192C451.6 192 447.9 195.6 448.5 200C452.1 229 475.1 251.9 504 255.5zM136 384.5C131.6 384 128 387.6 128 392L128 440C128 444.4 131.6 448 136 448L184 448C188.4 448 192.1 444.4 191.5 440C187.9 411 164.9 388.1 136 384.5z" />
                                </svg>
                                Hutang
                            </a>
                        </div>

                        <div class="pl-6 mt-1 space-y-1">
                            <a href="{{ route('pembukuan_transaksi.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('pembukuan_transaksi.index') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="M360-200v-80h480v80H360Zm0-240v-80h480v80H360Zm0-240v-80h480v80H360ZM200-160q-33 0-56.5-23.5T120-240q0-33 23.5-56.5T200-320q33 0 56.5 23.5T280-240q0 33-23.5 56.5T200-160Zm0-240q-33 0-56.5-23.5T120-480q0-33 23.5-56.5T200-560q33 0 56.5 23.5T280-480q0 33-23.5 56.5T200-400Zm0-240q-33 0-56.5-23.5T120-720q0-33 23.5-56.5T200-800q33 0 56.5 23.5T280-720q0 33-23.5 56.5T200-640Z" />
                                </svg>
                                Pembukuan
                            </a>
                        </div>
                    </details>

                    <details open class="group">
                        <summary
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                Manajemen Pengguna
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="{{ route('pengguna.index') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('pengguna.index') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                    <path
                                        d="M560-680v-80h320v80H560Zm0 160v-80h320v80H560Zm0 160v-80h320v80H560Zm-240-40q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM80-160v-76q0-21 10-40t28-30q45-27 95.5-40.5T320-360q56 0 106.5 13.5T522-306q18 11 28 30t10 40v76H80Zm86-80h308q-35-20-74-30t-80-10q-41 0-80 10t-74 30Zm154-240q17 0 28.5-11.5T360-520q0-17-11.5-28.5T320-560q-17 0-28.5 11.5T280-520q0 17 11.5 28.5T320-480Zm0-40Zm0 280Z" />
                                </svg>
                                Pengguna
                            </a>
                        </div>
                    </details>
                </nav>
                <div class="mt-auto p-4 space-y-2 border-t border-gray-200">
                    <a href="#"
                        class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 640 640" height="24" width="24">
                            <path
                                d="M528 320C528 205.1 434.9 112 320 112C205.1 112 112 205.1 112 320C112 434.9 205.1 528 320 528C434.9 528 528 434.9 528 320zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320zM320 240C302.3 240 288 254.3 288 272C288 285.3 277.3 296 264 296C250.7 296 240 285.3 240 272C240 227.8 275.8 192 320 192C364.2 192 400 227.8 400 272C400 319.2 364 339.2 344 346.5L344 350.3C344 363.6 333.3 374.3 320 374.3C306.7 374.3 296 363.6 296 350.3L296 342.2C296 321.7 310.8 307 326.1 302C332.5 299.9 339.3 296.5 344.3 291.7C348.6 287.5 352 281.7 352 272.1C352 254.4 337.7 240.1 320 240.1zM288 432C288 414.3 302.3 400 320 400C337.7 400 352 414.3 352 432C352 449.7 337.7 464 320 464C302.3 464 288 449.7 288 432z" />
                        </svg>
                        Bantuan
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 w-full text-left">
                            <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" height="24"
                                width="24" viewBox="0 0 640 640" fill="currentColor">
                                <path
                                    d="M569 337C578.4 327.6 578.4 312.4 569 303.1L425 159C418.1 152.1 407.8 150.1 398.8 153.8C389.8 157.5 384 166.3 384 176L384 256L272 256C245.5 256 224 277.5 224 304L224 336C224 362.5 245.5 384 272 384L384 384L384 464C384 473.7 389.8 482.5 398.8 486.2C407.8 489.9 418.1 487.9 425 481L569 337zM224 160C241.7 160 256 145.7 256 128C256 110.3 241.7 96 224 96L160 96C107 96 64 139 64 192L64 448C64 501 107 544 160 544L224 544C241.7 544 256 529.7 256 512C256 494.3 241.7 480 224 480L160 480C142.3 480 128 465.7 128 448L128 192C128 174.3 142.3 160 160 160L224 160z" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Sidebar untuk Mobile (Off-canvas) -->
            <div id="mobile-menu" class="fixed inset-0 flex z-50 md:hidden hidden">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black opacity-0 transition-opacity duration-500 ease-in-out "></div>

                <!-- Konten Sidebar -->
                <div
                    class="relative flex flex-col w-64 h-full bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out will-change-transform backface-hidden perspective-1000 lg:translate-x-0 lg:static lg:inset-0">
                    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200">
                        <h1 class="text-xl font-bold text-gray-800">KopsisApp</h1>
                        <button id="close-btn"
                            class="text-gray-600 p-1 rounded-md hover:bg-gray-200 focus:outline-none"
                            aria-label="Tutup Sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                            </svg>
                        </button>
                    </div>
                    <!-- Konten Navigasi Mobile (sama dengan desktop) -->
                    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
                        <a href="{{ route('beranda.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('beranda.index') || request()->is('beranda*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                            <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" height="24"
                                width="24" viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                            </svg>
                            Beranda
                        </a>

                        <!-- Menu Dropdown -->
                        <details open class="group">
                            <summary
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                                <div class="flex items-center">
                                    Data Master
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="pl-6 mt-1 space-y-1">
                                <a href="{{ route('vendor.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('vendor.index') || request()->is('vendor*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="M841-518v318q0 33-23.5 56.5T761-120H201q-33 0-56.5-23.5T121-200v-318q-23-21-35.5-54t-.5-72l42-136q8-26 28.5-43t47.5-17h556q27 0 47 16.5t29 43.5l42 136q12 39-.5 71T841-518Zm-272-42q27 0 41-18.5t11-41.5l-22-140h-78v148q0 21 14 36.5t34 15.5Zm-180 0q23 0 37.5-15.5T441-612v-148h-78l-22 140q-4 24 10.5 42t37.5 18Zm-178 0q18 0 31.5-13t16.5-33l22-154h-78l-40 134q-6 20 6.5 43t41.5 23Zm540 0q29 0 42-23t6-43l-42-134h-76l22 154q3 20 16.5 33t31.5 13ZM201-200h560v-282q-5 2-6.5 2H751q-27 0-47.5-9T663-518q-18 18-41 28t-49 10q-27 0-50.5-10T481-518q-17 18-39.5 28T393-480q-29 0-52.5-10T299-518q-21 21-41.5 29.5T211-480h-4.5q-2.5 0-5.5-2v282Zm560 0H201h560Z" />
                                    </svg>
                                    Vendor
                                </a>
                                <a href="{{ route('produk.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('produk.index') || request()->is('produk*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="m400-570 80-40 80 40v-190H400v190ZM280-280v-80h200v80H280Zm-80 160q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-640v560-560Zm0 560h560v-560H640v320l-160-80-160 80v-320H200v560Z" />
                                    </svg>
                                    Produk
                                </a>
                            </div>
                        </details>

                        <!-- Menu lainnya dengan pola yang sama -->
                        <details open class="group">
                            <summary
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                                <div class="flex items-center">
                                    Manajemen Stok
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="pl-6 mt-1 space-y-1">
                                <a href="{{ route('stok_terkini.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('stok_terkini.index') || request()->is('stok_terkini*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="M80-80v-160h800v160H760v-80H540v80H420v-80H200v80H80Zm160-240q-17 0-28.5-11.5T200-360v-480q0-17 11.5-28.5T240-880h480q17 0 28.5 11.5T760-840v480q0 17-11.5 28.5T720-320H240Zm40-80h400v-400H280v400Zm80-240h240v-80H360v80Zm-80 240v-400 400Z" />
                                    </svg>
                                    Stok Terkini
                                </a>
                                <a href="{{ route('barang_masuk.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('barang_masuk.index') || request()->is('barang_masuk*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="M240-80q-33 0-56.5-23.5T160-160v-480q0-33 23.5-56.5T240-720h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800-640v480q0 33-23.5 56.5T720-80H240Zm0-80h480v-480h-80v80q0 17-11.5 28.5T600-520q-17 0-28.5-11.5T560-560v-80H400v80q0 17-11.5 28.5T360-520q-17 0-28.5-11.5T320-560v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480-800q-33 0-56.5 23.5T400-720ZM240-160v-480 480Z" />
                                    </svg>
                                    Barang Masuk
                                </a>
                                <a href="{{ route('barang_keluar.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('barang_keluar.index') || request()->is('barang_keluar*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="M856-390 570-104q-12 12-27 18t-30 6q-15 0-30-6t-27-18L103-457q-11-11-17-25.5T80-513v-287q0-33 23.5-56.5T160-880h287q16 0 31 6.5t26 17.5l352 353q12 12 17.5 27t5.5 30q0 15-5.5 29.5T856-390ZM513-160l286-286-353-354H160v286l353 354ZM260-640q25 0 42.5-17.5T320-700q0-25-17.5-42.5T260-760q-25 0-42.5 17.5T200-700q0 25 17.5 42.5T260-640Zm220 160Z" />
                                    </svg>
                                    Barang Keluar
                                </a>
                            </div>
                        </details>

                        <details open class="group">
                            <summary
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                                <div class="flex items-center">
                                    Keuangan
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="pl-6 mt-1 space-y-1">
                                <a href="{{ route('ledger_entries.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('ledger_entries.index') || request()->is('ledger_entries*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 640" fill="currentColor" width="24" height="24">
                                        <path
                                            d="M31 169C21.6 159.6 21.6 144.4 31 135.1L103 63C112.4 53.6 127.6 53.6 136.9 63C146.2 72.4 146.3 87.6 136.9 96.9L105.9 127.9L173.6 127.9L173.6 127.9L511.9 127.9C547.2 127.9 575.9 156.6 575.9 191.9L575.9 370.1L570.8 365C542.7 336.9 497.1 336.9 469 365C441.8 392.2 440.9 435.6 466.2 463.9L533.9 463.9L502.9 432.9C493.5 423.5 493.5 408.3 502.9 399C512.3 389.7 527.5 389.6 536.8 399L608.8 471C618.2 480.4 618.2 495.6 608.8 504.9L536.8 576.9C527.4 586.3 512.2 586.3 502.9 576.9C493.6 567.5 493.5 552.3 502.9 543L533.9 512L127.8 512C92.5 512 63.8 483.3 63.8 448L63.8 269.8L68.9 274.9C97 303 142.6 303 170.7 274.9C197.9 247.7 198.8 204.3 173.5 176L105.8 176L136.8 207C146.2 216.4 146.2 231.6 136.8 240.9C127.4 250.2 112.2 250.3 102.9 240.9L31 169zM416 320C416 267 373 224 320 224C267 224 224 267 224 320C224 373 267 416 320 416C373 416 416 373 416 320zM504 255.5C508.4 256 512 252.4 512 248L512 200C512 195.6 508.4 192 504 192L456 192C451.6 192 447.9 195.6 448.5 200C452.1 229 475.1 251.9 504 255.5zM136 384.5C131.6 384 128 387.6 128 392L128 440C128 444.4 131.6 448 136 448L184 448C188.4 448 192.1 444.4 191.5 440C187.9 411 164.9 388.1 136 384.5z" />
                                    </svg>
                                    Hutang
                                </a>
                            </div>
                            <div class="pl-6 mt-1 space-y-1">
                                <a href="{{ route('pembukuan_transaksi.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('pembukuan_transaksi.index') || request()->is('pembukuan_transaksi*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="M360-200v-80h480v80H360Zm0-240v-80h480v80H360Zm0-240v-80h480v80H360ZM200-160q-33 0-56.5-23.5T120-240q0-33 23.5-56.5T200-320q33 0 56.5 23.5T280-240q0 33-23.5 56.5T200-160Zm0-240q-33 0-56.5-23.5T120-480q0-33 23.5-56.5T200-560q33 0 56.5 23.5T280-480q0 33-23.5 56.5T200-400Zm0-240q-33 0-56.5-23.5T120-720q0-33 23.5-56.5T200-800q33 0 56.5 23.5T280-720q0 33-23.5 56.5T200-640Z" />
                                    </svg>
                                    Pembukuan
                                </a>
                            </div>
                        </details>

                        <details open class="group">
                            <summary
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600 rounded-lg cursor-pointer hover:bg-gray-50">
                                <div class="flex items-center">
                                    Manajemen Pengguna
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="pl-6 mt-1 space-y-1">
                                <a href="{{ route('pengguna.index') }}"
                                    class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('pengguna.index') || request()->is('pengguna*') ? 'text-gray-700 bg-gray-100' : 'text-gray-600 text-gray-600' }} rounded-lg cursor-pointer">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        height="24" width="24" viewBox="0 -960 960 960" fill="currentColor">
                                        <path
                                            d="M560-680v-80h320v80H560Zm0 160v-80h320v80H560Zm0 160v-80h320v80H560Zm-240-40q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM80-160v-76q0-21 10-40t28-30q45-27 95.5-40.5T320-360q56 0 106.5 13.5T522-306q18 11 28 30t10 40v76H80Zm86-80h308q-35-20-74-30t-80-10q-41 0-80 10t-74 30Zm154-240q17 0 28.5-11.5T360-520q0-17-11.5-28.5T320-560q-17 0-28.5 11.5T280-520q0 17 11.5 28.5T320-480Zm0-40Zm0 280Z" />
                                    </svg>
                                    Pengguna
                                </a>
                            </div>
                        </details>
                    </nav>
                    <div class="mt-auto p-4 space-y-2 border-t border-gray-200">
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 640 640" height="24" width="24">
                                <path
                                    d="M528 320C528 205.1 434.9 112 320 112C205.1 112 112 205.1 112 320C112 434.9 205.1 528 320 528C434.9 528 528 434.9 528 320zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320zM320 240C302.3 240 288 254.3 288 272C288 285.3 277.3 296 264 296C250.7 296 240 285.3 240 272C240 227.8 275.8 192 320 192C364.2 192 400 227.8 400 272C400 319.2 364 339.2 344 346.5L344 350.3C344 363.6 333.3 374.3 320 374.3C306.7 374.3 296 363.6 296 350.3L296 342.2C296 321.7 310.8 307 326.1 302C332.5 299.9 339.3 296.5 344.3 291.7C348.6 287.5 352 281.7 352 272.1C352 254.4 337.7 240.1 320 240.1zM288 432C288 414.3 302.3 400 320 400C337.7 400 352 414.3 352 432C352 449.7 337.7 464 320 464C302.3 464 288 449.7 288 432z" />
                            </svg>
                            Bantuan
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 w-full text-left">
                                <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    height="24" width="24" viewBox="0 0 640 640" fill="currentColor">
                                    <path
                                        d="M569 337C578.4 327.6 578.4 312.4 569 303.1L425 159C418.1 152.1 407.8 150.1 398.8 153.8C389.8 157.5 384 166.3 384 176L384 256L272 256C245.5 256 224 277.5 224 304L224 336C224 362.5 245.5 384 272 384L384 384L384 464C384 473.7 389.8 482.5 398.8 486.2C407.8 489.9 418.1 487.9 425 481L569 337zM224 160C241.7 160 256 145.7 256 128C256 110.3 241.7 96 224 96L160 96C107 96 64 139 64 192L64 448C64 501 107 544 160 544L224 544C241.7 544 256 529.7 256 512C256 494.3 241.7 480 224 480L160 480C142.3 480 128 465.7 128 448L128 192C128 174.3 142.3 160 160 160L224 160z" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    @stack('scripts')
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('script')
</body>

</html>
