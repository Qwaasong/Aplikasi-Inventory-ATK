@php
use App\Models\Product;
// Mengambil array kategori dari model Product
$kategoriOptions = Product::$kategoriOptions;
@endphp
@extends('layouts.main')
@section('title', 'KopsisApp - Vendor')
@section('content')

    <div class="px-8 py-6">
        <!-- Header Konten -->
        <div class="flex flex-col space-y-4">
            <div class="flex items-center text-sm text-gray-500">
                <span>Manajemen Stok</span>
                <svg class="h-4 w-4 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>Stok Terkini</span>
            </div>

            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-900 m-0">Stok Terkini</h2>
            </div>
        </div>

        <hr class="my-6 border-gray-200">
        {{-- PANGGEL COMPONENT --}}
        <x-table :data-table="[
                                    'Nama Produk' => 'nama',
                                    'Kategori' => 'kategori',
                                    'Satuan' => 'satuan',
                                    'Stok' => 'stok_tersedia',
                                    ]" data-url="{{ route('api.stok_terkini.index') }}" :showAction="false">
            {{-- Debug info --}}
            <x-slot:debug>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Debug Info: </strong>
                    <span class="block sm:inline">Check browser console for API response details</span>
                </div>
            </x-slot:debug>
            {{-- Slot untuk filter --}}
            <x-slot:filter>
                <div class="flex items-center space-x-4">
                    <button id="filter-button"
                        class="p-3 sm:p-2 text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </button>

                    <!-- Dropdown Filter -->
                    <div id="filter-dropdown"
                        class="hidden absolute mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-20 top-full">
                        <form id="filter-form" class="p-6 space-y-4">
                        
                            <!-- Kategori -->
                           
                            <div>
                                <label for="filter_kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="filter[kategori]" id="filter_kategori"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    
                                    {{-- Opsi default untuk membatalkan filter --}}
                                    <option value="">Semua Kategori</option>
                                    
                                    {{-- Loop untuk menampilkan semua kategori dari model Product --}}
                                    @foreach ($kategoriOptions as $key => $label)
                                        <option value="{{ $key }}" 
                                            {{-- Cek apakah opsi ini adalah kategori yang sedang difilter --}}
                                            @if(request()->get('filter') && request()->get('filter')['kategori'] == $key)
                                                selected
                                            @endif
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="filter_stock" class="block text-sm font-medium text-gray-700">Stock</label>
                                <input type="number" name="filter[stock]" id="filter_stock" min="0"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- Tombol aksi -->
                            <div class="flex justify-end space-x-2 pt-4">
                                <button type="button" id="reset-filter-btn"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                    Reset
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                                    Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-slot:filter>
        </x-table>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/fab.js') }}"></script>
@endsection