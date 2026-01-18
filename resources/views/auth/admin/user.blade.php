@extends('layouts.admin')

@section('title', 'Admin | User')

@section('content')

<!-- Font Awesome untuk Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom Style untuk penyesuaian spesifik agar mirip gambar dan modal -->
<style>
    body {
        background-color: #EEEFF1;
    }

    .modal-bg {
        background-color: #F5F3F4;
    }

    .input-field {
        background-color: transparent;
        border: 1px solid #333;
        border-radius: 4px;
        padding: 8px 12px;
        width: 100%;
        outline: none;
        transition: border-color 0.2s;
    }

    .input-field:focus {
        border-color: #2563EB;
    }

    .btn-modal-action {
        border: 1px solid #6B7280;
        color: #6B7280;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-modal-action:hover {
        background-color: #E5E7EB;
        color: #111;
    }
</style>

<div class="px-8 py-6">
    <!-- Header Konten -->
    <div class="flex flex-col space-y-4">
        <div class="flex items-center text-sm text-gray-500">
            <span>Admin</span>
            <svg class="h-4 w-4 mx-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
            </svg>
            <span>Daftar User</span>
        </div>

        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-gray-900 m-0">Daftar User</h2>
            <div class="hidden md:flex gap-2">
                <button onclick="openModal('modalTambahUser')" class="bg-[#1e5bb5] hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-semibold transition shadow-sm">
                    Tambah User
                </button>
            </div>
        </div>
    </div>

    <!-- FAB Container (Mobile) -->
    <div class="md:hidden fab fixed bottom-6 right-6 flex flex-col items-end gap-3 z-40">
        <button onclick="openModal('modalTambahUser')" class="w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all bg-[#1e5bb5] text-white hover:bg-blue-800">
            <i class="fa-solid fa-plus text-xl"></i>
        </button>
    </div>

    <hr class="my-6 border-gray-200">

    <!-- Tabel Data Menggunakan Component -->
    <x-table :data-table="[
                'Nama User' => 'nama_user',
                'Username' => 'username',
                'Role' => 'role'
                ]"
        data-url="{{ route('api.user.index') }}">

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

                <div id="filter-dropdown"
                    class="hidden absolute mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-20 top-full">
                    <form id="filter-form" class="p-6 space-y-4">

                        <div>
                            <label for="filter_tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                            <input type="date" name="filter[tanggal_awal]" id="filter_tanggal_awal"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="filter_tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" name="filter[tanggal_akhir]" id="filter_tanggal_akhir"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

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

<!-- ================= MODAL: TAMBAH USER ================= -->
<div id="modalTambahUser" class="fixed inset-0 bg-black bg-opacity-30 hidden flex justify-center items-center z-50 p-4 transition-opacity duration-300">
    <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
        <div class="flex justify-between items-start p-6 pb-2">
            <h2 class="text-xl font-normal text-black hidden">Tambah User</h2>
            <button onclick="closeModal('modalTambahUser')" class="absolute top-4 right-4 text-gray-800 hover:text-red-600 text-3xl font-light">
                &times;
            </button>
        </div>
        <div class="p-8 pt-4">
            <h3 class="text-lg mb-6 text-black">Tambah User Baru</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama User -->
                <div>
                    <input type="text" placeholder="Nama User" class="input-field placeholder-gray-500">
                </div>
                <!-- Role -->
                <div>
                    <input type="text" placeholder="Role" class="input-field placeholder-gray-500">
                </div>
                <!-- Username -->
                <div>
                    <input type="text" placeholder="Username" class="input-field placeholder-gray-500">
                </div>
                <!-- Password -->
                <div>
                    <input type="password" placeholder="Password" class="input-field placeholder-gray-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-10">
                <button class="btn-modal-action text-blue-500 border-blue-400 hover:bg-blue-50">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/fab.js') }}"></script>
<script>
    // Fungsi Membuka Modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Fungsi Menutup Modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Menutup modal jika user klik di area gelap (backdrop)
    window.onclick = function(event) {
        const modalTambahUser = document.getElementById('modalTambahUser');

        if (event.target == modalTambahUser) {
            closeModal('modalTambahUser');
        }
    }
</script>
@endsection