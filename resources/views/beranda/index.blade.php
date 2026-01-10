@extends('layouts.main')
@section('title', 'Beranda - KopsisApp')
@section('content')
    <!-- Konten yang bisa di-scroll -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-6">
        <h1 class="text-3xl md:text-2xl font-bold text-gray-800 mb-6">Beranda</h1>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500">Saldo</p>
                <p id="stat-saldo" class="text-2xl md:text-3xl font-bold text-gray-800">Loading...</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500">Pemasukan</p>
                <p id="stat-pemasukan" class="text-2xl md:text-3xl font-bold text-gray-800">Loading...</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500">Pengeluaran</p>
                <p id="stat-pengeluaran" class="text-2xl md:text-3xl font-bold text-gray-800">Loading...</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500">Total Produk</p>
                <p id="stat-total-produk" class="text-2xl md:text-3xl font-bold text-gray-800">Loading...</p>
            </div>
        </div>

        <!-- Chart Cards -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-4 md:gap-6">
            <!-- Grafik Pemasukan & Pengeluaran -->
            <div class="lg:col-span-3 bg-white p-5 md:p-6 rounded-xl border border-gray-200">
                <h3 class="font-bold text-gray-800 text-lg">Pemasukan Dan Pengeluaran Minggu Ini</h3>
                <p id="persentase-pemasukan-pengeluaran-value" class="text-3xl font-bold text-gray-800 mt-1">Loading...</p>
                <p id="persentase-pemasukan-pengeluaran-period" class="text-sm font-semibold">Loading...</p>
                <div class="mt-4 h-64">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            <!-- Distribusi Produk -->
            <div class="lg:col-span-2 bg-white p-5 md:p-6 rounded-xl border border-gray-200">
                <h3 class="font-bold text-gray-800 text-lg">Distribusi Produk (Berdasarkan Kategori)</h3>
                <p id="persentase-distribusi-produk-value" class="text-3xl font-bold text-gray-800 mt-1">Loading...</p>
                <p id="persentase-distribusi-produk-period" class="text-sm font-semibold">Loading...</p>
                <div class="mt-6 h-48">
                    <canvas id="productDistributionChart"></canvas>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection

@section('script')
    <script src="{{ asset('assets/js/beranda.js') }}"></script>
@endsection