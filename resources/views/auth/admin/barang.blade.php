@extends('layouts.admin')

@section('title', 'Admin | Barang')

@section('content')
    <main class="px-8 py-6">
        <!-- Font Awesome untuk Icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Custom Style untuk penyesuaian spesifik agar mirip gambar dan modal -->
        <style>
            body {
                background-color: var(--bg-main);
            }

            .modal-bg {
                background-color: var(--bg-modal);
                border-radius: 12px;
                border: 1px solid var(--border-primary);
                box-shadow: var(--shadow-lg);
            }

            .input-field {
                background-color: var(--bg-input);
                border: 1px solid var(--border-input);
                border-radius: 8px;
                padding: 10px 12px;
                width: 100%;
                font-size: 0.9rem;
                color: var(--text-main);
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .input-field:focus {
                border-color: #2563eb;
                outline: none;
                box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
            }

            .btn-modal-action {
                border: 1px solid transparent;
                background-color: #2563eb;
                color: white;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 0.85rem;
                font-weight: 500;
                transition: background-color 0.2s, transform 0.1s;
            }

            .btn-modal-action:hover {
                background-color: #1e40af;
                transform: translateY(-1px);
            }


            .btn-modal-action:hover {
                background-color: #E5E7EB;
                color: #111;
            }

            /* Styling untuk Hasil Pencarian (Autocomplete) */
            .search-results {
                position: absolute;
                background: var(--bg-card);
                border: 1px solid var(--border-primary);
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
                z-index: 1000;
                border-radius: 4px;
                box-shadow: var(--shadow-md);
            }

            .search-item {
                padding: 10px;
                cursor: pointer;
                border-bottom: 1px solid #eee;
            }

            .search-item:hover {
                background-color: var(--nav-hover-bg);
            }

            /* 1. WRAPPER UTAMA FAB */
            .fab {
                z-index: 9999;
                /* KUNCI: Buat container selebar tombol utama (56px) & ratakan tengah */
                width: 56px !important;
                flex-direction: column !important;
                align-items: center !important;
                /* Semua tombol dipaksa ke tengah as */
                right: 24px !important;
                /* Jarak dari kanan layar (right-6) */
            }

            /* 2. ITEM LIST (ANAK-ANAK) */
            .fab-items {
                width: 100%;
                display: flex;
                flex-direction: column-reverse;
                align-items: center;
                /* Pastikan anak-anak juga rata tengah */
                gap: 12px;
                padding-bottom: 12px;
                /* Jarak antara tombol kecil & tombol utama */
            }

            .fab-item-wrapper {
                /* Wrapper memenuhi lebar 56px, tombol ditaruh di tengah */
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                /* Tombol kecil dipaksa ke tengah */
                position: relative;
                /* Jangkar untuk label absolute */

                /* Animasi */
                transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                opacity: 1;
                transform: translateY(0) scale(1);
                visibility: visible;
                height: 48px;
                /* Tinggi fix sesuai tombol kecil */
            }

            /* Kondisi Tersembunyi */
            .fab-item-wrapper.hidden-space {
                opacity: 0;
                transform: translateY(20px) scale(0.8);
                visibility: hidden;
                height: 0;
            }

            /* Tombol Bulat Anak (48px) */
            .fab-item {
                width: 48px;
                height: 48px;
                border: none;
                outline: none;
                cursor: pointer;
                border-radius: 9999px;
                /* Tidak perlu margin aneh-aneh lagi */
            }

            /* 3. LABEL TEKS (POSISI ABSOLUTE) */
            /* Label menempel melayang di sebelah kiri tombol */
            .fab-label {
                position: absolute;
                right: 56px;
                /* Geser ke kiri sejauh lebar container */
                top: 50%;
                transform: translateY(-50%);
                /* Trik CSS biar lurus vertikal tengah */

                font-family: 'Inter', sans-serif;
                background-color: #1f2937;
                color: white;
                padding: 6px 14px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                margin-right: 12px;
                /* Jarak label ke tombol */
                white-space: nowrap;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            /* 4. TOMBOL UTAMA (TRIGGER) */
            #fabMain {
                width: 56px;
                height: 56px;
                position: relative;
                border: none;
                outline: none;
                cursor: pointer;
                transform: none !important;
                transition: background-color 0.2s;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                /* Otomatis rata tengah karena parent align-items: center */
            }

            /* ICON X */
            #iconX {
                position: static !important;
                transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                display: flex !important;
                align-items: center;
                justify-content: center;
            }

            #fabMain[aria-expanded="true"] #iconX {
                transform: rotate(135deg) !important;
            }
        </style>


        <!-- Header Konten -->
        <div class="flex flex-col space-y-4">
            <div class="dark:text-white flex items-center text-sm text-gray-500">
                <span class="text-gray-500 dark:text-gray-500">Admin</span>
                <svg class="h-4 w-4 mx-1 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="text-gray-500 dark:text-white-500 font-medium">Daftar Barang</span>
            </div>

            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-900 m-0 dark:text-white">Daftar Barang</h2>
                <div class="hidden md:flex gap-2">

                    {{-- Barang Masuk --}}
                    <button onclick="openModal('modalMasuk')"
                        class="bg-[var(--accent-primary)] hover:opacity-90 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-md">
                        Barang Masuk <i class="fa-regular fa-square-plus"></i>
                    </button>

                    {{-- Barang Keluar --}}
                    <button onclick="openModal('modalKeluar')"
                        class="bg-[var(--accent-danger)] hover:opacity-90 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-md">
                        Barang Keluar <i class="fa-regular fa-square-minus"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- FAB Container (Mobile) -->
        <div class="md:hidden fab fixed bottom-6 right-6 flex flex-col z-50">
            <div class="fab-items">

                {{-- Barang Keluar --}}
                <div class="fab-item-wrapper hidden-space">
                    <span class="fab-label" onclick="openModal('modalKeluar')">
                        Barang Keluar
                    </span>
                    <button onclick="openModal('modalKeluar')"
                        class="fab-item shadow-lg flex items-center justify-center text-white bg-[var(--accent-danger)] hover:opacity-90">
                        <i class="fa-regular fa-square-minus"></i>
                    </button>
                </div>

                {{-- Barang Masuk --}}
                <div class="fab-item-wrapper hidden-space">
                    <span class="fab-label" onclick="openModal('modalMasuk')">
                        Barang Masuk
                    </span>
                    <button onclick="openModal('modalMasuk')"
                        class="fab-item shadow-lg flex items-center justify-center text-white bg-[var(--accent-info)] hover:opacity-90">
                        <i class="fa-regular fa-square-plus"></i>
                    </button>
                </div>
            </div>

            <button id="fabMain"
                class="fab-main rounded-full shadow-xl bg-[var(--accent-primary)] hover:opacity-90 text-white z-50"
                aria-expanded="false" aria-label="Open FAB">

                <span id="iconX" class="fab-icon visible text-xl">
                    <i class="fa-solid fa-plus"></i>
                </span>
            </button>

        </div>


        <hr class="my-6 border-gray-200">

        <!-- Tabel Data Menggunakan Component -->
        <x-table :data-table="[
                        'Nama Barang' => 'nama_barang',
                        'Kategori' => 'kategori.nama_kategori',
                        'Nama Pack' => 'nama_pack',
                        'Jumlah Pack' => 'jumlah_pack',
                        'Pcs per pack' => 'jumlah_pcs',
                        'Total Pcs' => 'total_pcs',
                    ]" data-url="{{ route('api.barang.index') }}" primary-key="id_barang">

            {{-- ================= FILTER ================= --}}
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
                                <label for="filter_kategori"
                                    class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select id="filter_kategori" name="filter[kategori]"    
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Semua</option>
                                </select>
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


        <!-- ================= MODAL 1: TAMBAH BARANG MASUK ================= -->
        <div id="modalMasuk"
            class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50 p-4 transition-opacity duration-300">
            <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
                <div class="flex justify-between items-start p-6 pb-2">
                    <h2 class="text-xl font-normal text-black hidden">Pop Up Tambah Barang Masuk</h2>
                    <button onclick="closeModal('modalMasuk')"
                        class="absolute top-4 right-4 text-gray-800 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 text-3xl font-light transition-colors">
                        &times;
                    </button>
                </div>
                <form id="formBarangMasuk" onsubmit="submitBarangMasuk(event)">
                    <div class="p-8 pt-6">
                        <h3 class="text-lg font-medium mb-6 dark:text-white text-gray-900">Masukkan Barang Masuk</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nama Barang Masuk --}}
                            <div>
                                <label class="block text-xs mb-1">Nama Barang</label>
                                <input type="text" name="nama_barang" id="nama_barang" placeholder="Nama Barang..."
                                    class="input-field placeholder-gray-500" required>
                            </div>

                            {{-- Jumlah Pack Masuk --}}
                            <div>
                                <label class="block text-xs mb-1">Jumlah Pack</label>
                                <input type="number" name="jumlah_pack" id="jumlah_pack" placeholder="Jumlah Pack...."
                                    class="input-field placeholder-gray-500" required>
                            </div>

                            {{-- Kategori Masuk --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Kategori</label>

                                <div class="flex items-center gap-2">
                                    <select id="masuk_id_kategori_display" class="input-field bg-gray-100 flex-1">
                                        <option value="">Pilih Kategori</option>
                                    </select>

                                    <button type="button" onclick="tambahKategori()"
                                        class="w-9 h-9 flex items-center justify-center rounded-md text-white bg-blue-500 hover:bg-blue-600 transition shadow-sm">
                                        <i class="fa-solid fa-circle-plus text-lg leading-none"></i>
                                    </button>

                                    <button type="button" onclick="hapusKategori('masuk_id_kategori_display')"
                                        class="w-9 h-9 flex items-center justify-center rounded-md text-white bg-red-500 hover:bg-red-600 transition shadow-sm">
                                        <i class="fa-solid fa-circle-minus text-lg leading-none"></i>
                                    </button>
                                </div>

                                <input type="hidden" name="id_kategori" id="masuk_id_kategori">
                            </div>

                            {{-- Nama Pack Masuk --}}
                            <div>
                                <label class="block text-xs mb-1">Nama Pack</label>
                                <input type="text" name="nama_pack" id="nama_pack"
                                    placeholder="Satuan Pack (Contoh: Dus, Box, Karton)"
                                    class="input-field placeholder-gray-500">
                            </div>

                            {{-- Jumlah Pcs Masuk --}}
                            <div>
                                <label class="block text-xs mb-1">Jumlah Pcs</label>
                                <input type="number" name="jumlah_pcs" id="jumlah_pcs" placeholder="Jumlah Pcs"
                                    class="input-field placeholder-gray-500" required>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-10">
                            <button type="submit" class="btn-modal-action text-blue-500 border-blue-400 hover:bg-blue-50">
                                Simpan Data
                            </button>
                            <!-- Opsi 'Simpan Dan Buat Lagi' bisa ditambahkan nanti jika diperlukan logika khusus -->
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL 2: BARANG KELUAR ================= -->
        <div id="modalKeluar"
            class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50 p-4 transition-opacity duration-300">
            <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
                <div class="flex justify-between items-start p-6 pb-2">
                    <button onclick="closeModal('modalKeluar')" {{-- text-gray-800 dark:text-gray-300 text-gray-800
                        hover:text-red-600 --}}
                        class="absolute top-4 right-4 text-gray-800 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 text-3xl font-light transition-colors">
                        &times;
                    </button>
                </div>
                <form id="formBarangKeluar" onsubmit="submitBarangKeluar(event)">
                    <div class="p-8 pt-4">
                        <h3 class="text-lg mb-6 text-black dark:text-white">Keluarkan Barang</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nama Barang --}}
                            <div class="relative">
                                <!-- Input Pencarian (Autocomplete) -->
                                <label class="block text-xs mb-1">Nama Barang</label>
                                <input type="text" id="search_barang_keluar" placeholder="Ketik Nama Barang..."
                                    class="input-field placeholder-gray-500" autocomplete="off">
                                <input type="hidden" name="id_barang" id="id_barang_keluar" required>
                                <div id="search_results_container" class="search-results hidden"></div>
                            </div>

                            {{-- Jumlah Pcs --}}
                            <div>
                                <label class="block text-xs mb-1">Jumlah Pcs</label>
                                <input type="number" name="jumlah_pcs" id="jumlah_pcs_keluar" placeholder="Jumlah Pcs"
                                    class="input-field placeholder-gray-500" required>
                            </div>
                        </div>
                        <div class="mb-10"></div>

                        {{-- Tombol Simpan Perubahan --}}
                        <div class="flex justify-end gap-3 mt-4">
                            <button type="submit" class="btn-modal-action text-blue-500 border-blue-400 hover:bg-blue-50">
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
        </div>

        <!-- ================= MODAL 3: EDIT BARANG ================= -->
        <div id="modalEdit"
            class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50 p-4 transition-opacity duration-300">
            <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
                <div class="flex justify-between items-start p-6 pb-2">
                    <button onclick="closeModal('modalEdit')"
                        class="absolute top-4 right-4 text-gray-800 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 text-3xl font-light transition-colors duration-200">&times;</button>
                </div>
                <form id="formBarangEdit" onsubmit="submitBarangEdit(event)">
                    <input type="hidden" name="id_barang" id="edit_id_barang">

                    <div class="p-8 pt-4">
                        <h3 class="text-lg mb-6 dark:text-white text-black">Edit Barang</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nama Barang Edit --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Barang</label>
                                <input type="text" name="nama_barang" id="edit_nama_barang" class="input-field bg-gray-100">
                            </div>

                            {{-- Jumlah Pack Edit --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jumlah Pack</label>
                                <input type="number" name="jumlah_pack" id="edit_jumlah_pack" class="input-field" required>
                            </div>

                            {{-- Kategori Edit --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Kategori</label>

                                <div class="flex items-center gap-2">
                                    <select id="edit_id_kategori_display" class="input-field bg-gray-100 flex-1">
                                        <option value="">Pilih Kategori</option>
                                    </select>

                                    <button type="button" onclick="tambahKategori()"
                                        class="w-9 h-9 flex items-center text-white justify-center rounded-md bg-blue-500 hover:bg-blue-600 transition">
                                        <i class="fa-solid fa-circle-plus text-lg leading-none"></i>
                                    </button>

                                    <button type="button" onclick="hapusKategori('edit_id_kategori_display')"
                                        class="w-9 h-9 flex items-center text-white justify-center rounded-md bg-red-500 hover:bg-red-600 transition">
                                        <i class="fa-solid fa-circle-minus text-lg leading-none"></i>
                                    </button>
                                </div>

                                <input type="hidden" name="id_kategori" id="edit_id_kategori">
                            </div>

                            {{-- Nama Pack Edit --}}
                            <div>
                                <label class="block text-xs mb-1">Nama Pack</label>
                                <input type="text" name="nama_pack" id="edit_nama_pack"
                                    placeholder="Satuan Pack (Contoh: Dus, Box, Karton)"
                                    class="input-field placeholder-gray-500">
                            </div>

                            {{-- Jumlah Pcs Edit --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jumlah Pcs</label>
                                <input type="number" name="jumlah_pcs" id="edit_jumlah_pcs" class="input-field" required>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-10">
                            <button type="submit"
                                class="btn-modal-action text-blue-500 border-blue-400 hover:bg-blue-50">Simpan
                                Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection

@section('script')
    <script src="{{ asset('assets/js/fab.js') }}"></script>
    <script>
        /**
         * ==========================================
         * CONFIG & STATE MANAGEMENT
         * ==========================================
         * Kumpulkan semua Route Laravel di sini agar kode logic di bawah bersih
         */
        const APP_CONFIG = {
            routes: {
                kategoriIndex: "{{ route('api.kategori.index') }}",
                kategoriStore: "{{ route('api.kategori.store') }}",
                kategoriDelete: "{{ url('api/kategori') }}", // Base URL untuk delete
                barangIndex: "{{ route('api.barang.index') }}",
                barangStore: "{{ route('api.barang.store') }}",
                barangKeluar: "{{ route('api.barang.keluar') }}",
                barangUpdate: "{{ url('api/barang') }}", // Base URL untuk update
            },
            csrfToken: '{{ csrf_token() }}'
        };

        // Variabel global untuk state
        let debounceTimer;

        /**
         * ==========================================
         * INITIALIZATION (DOM READY)
         * ==========================================
         */
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi event listener global
            initEventListeners();
        });

        /**
         * ==========================================
         * CATEGORY LOGIC
         * ==========================================
         */
        async function loadKategori(targetId, defaultText = 'Pilih Kategori') {
            const selectElement = document.getElementById(targetId);
            if (!selectElement) return;

            try {
                const response = await fetch(APP_CONFIG.routes.kategoriIndex);
                const result = await response.json();

                if (result.success) {
                    selectElement.innerHTML = `<option value="">${defaultText}</option>`;
                    result.data.forEach(kat => {
                        const option = document.createElement('option');
                        option.value = kat.id_kategori;
                        option.textContent = kat.nama_kategori;
                        selectElement.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Gagal memuat kategori:', error);
            }
        }

        async function tambahKategori() {
            const namaBaru = prompt('Masukkan Nama Kategori Baru:');
            if (!namaBaru) return;

            try {
                const response = await fetch(APP_CONFIG.routes.kategoriStore, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': APP_CONFIG.csrfToken
                    },
                    body: JSON.stringify({ nama_kategori: namaBaru })
                });

                const result = await response.json();
                if (result.success) {
                    alert(`Kategori "${namaBaru}" Berhasil Ditambahkan`);
                    await refreshAllKategoriDropdowns();
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan'));
                }
            } catch (error) {
                console.error('Error tambah kategori:', error);
                alert('Sistem Sedang Bermasalah Coba Lagi Nanti');
            }
        }

        async function hapusKategori(targetSelectId) {
            const select = document.getElementById(targetSelectId);
            const idTerpilih = select.value;

            if (!idTerpilih) {
                alert('Pilih Kategori Yang Ingin Dihapus Terlebih Dahulu');
                return;
            }

            const teksTerpilih = select.options[select.selectedIndex].text;
            if (!confirm(`Peringatan..!! Apakah Anda Yakin Menghapus ${teksTerpilih}?`)) return;

            try {
                const response = await fetch(`${APP_CONFIG.routes.kategoriDelete}/${idTerpilih}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': APP_CONFIG.csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                if (result.success) {
                    alert('Kategori Berhasil Dihapus');
                    select.value = ""; // Reset pilihan
                    await refreshAllKategoriDropdowns();
                }
            } catch (error) {
                console.error('Error hapus kategori:', error);
            }
        }

        async function refreshAllKategoriDropdowns() {
            await loadKategori('masuk_id_kategori_display');
            await loadKategori('edit_id_kategori_display');
        }

        /**
         * ==========================================
         * ITEM (BARANG) LOGIC - MASUK & EDIT
         * ==========================================
         */
        async function submitBarangMasuk(e) {
            e.preventDefault();

            // Sinkronisasi manual sebelum submit
            syncSelectToHidden('masuk_id_kategori_display', 'masuk_id_kategori');

            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            await handleFormSubmit(APP_CONFIG.routes.barangStore, 'POST', data, () => {
                alert('Barang Berhasil Ditambahkan');
                location.reload();
            });
        }

        async function submitBarangEdit(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id_barang').value;
            if (!id) return alert('ID Barang tidak ditemukan!');

            const form = document.getElementById('formBarangEdit');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const url = `${APP_CONFIG.routes.barangUpdate}/${id}`;

            await handleFormSubmit(url, 'PUT', data, () => {
                alert('Data berhasil diperbarui!');
                closeModal('modalEdit');
                window.location.reload();
            });
        }

        // Fungsi Helper Generik untuk Submit Form
        async function handleFormSubmit(url, method, data, onSuccess) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': APP_CONFIG.csrfToken
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    onSuccess(result);
                } else {
                    alert('Gagal: ' + (result.message || 'Cek inputan Anda'));
                }
            } catch (error) {
                console.error('Error submit:', error);
                alert('Terjadi kesalahan sistem.');
            }
        }

        /**
         * ==========================================
         * ITEM (BARANG) LOGIC - KELUAR & SEARCH
         * ==========================================
         */
        function handleSearchInput(e) {
            clearTimeout(debounceTimer);
            const query = e.target.value;
            const resultsContainer = document.getElementById('search_results_container');

            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => fetchBarangAutocomplete(query), 300);
        }

        async function fetchBarangAutocomplete(query) {
            const resultsContainer = document.getElementById('search_results_container');
            const searchInput = document.getElementById('search_barang_keluar');
            const idBarangInput = document.getElementById('id_barang_keluar');

            try {
                const response = await fetch(`${APP_CONFIG.routes.barangIndex}?search=${query}`);
                const result = await response.json();

                resultsContainer.innerHTML = '';

                if (result.success && result.data.data.length > 0) {
                    resultsContainer.classList.remove('hidden');
                    result.data.data.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('search-item'); // Pastikan ada CSS untuk class ini
                        div.textContent = `${item.nama_barang} (Stok: ${item.jumlah_pcs})`;
                        div.style.cursor = 'pointer';
                        div.style.padding = '8px';
                        div.style.borderBottom = '1px solid #eee';

                        div.onclick = () => {
                            searchInput.value = item.nama_barang;
                            idBarangInput.value = item.id_barang;
                            resultsContainer.classList.add('hidden');
                        };
                        resultsContainer.appendChild(div);
                    });
                } else {
                    resultsContainer.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error fetching barang:', error);
            }
        }

        async function submitBarangKeluar(e) {
            e.preventDefault();
            const id_barang = document.getElementById('id_barang_keluar').value;
            const jumlah_keluar = parseInt(document.getElementById('jumlah_pcs_keluar').value);

            if (!id_barang) return alert('Silakan cari dan pilih barang terlebih dahulu.');
            if (!jumlah_keluar || jumlah_keluar <= 0) return alert('Jumlah tidak valid.');

            await handleFormSubmit(APP_CONFIG.routes.barangKeluar, 'POST', { id_barang, jumlah_pcs_keluar: jumlah_keluar }, () => {
                alert('Stok berhasil dikurangi.');
                closeModal('modalKeluar');
                document.getElementById('formBarangKeluar').reset();
                window.location.reload();
            });
        }

        /**
         * Reset filter kembali ke default
         */
        function resetFilter() {
            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                filterForm.reset();
                // Trigger click on reset button if it exists to trigger x-table logic
                const resetBtn = document.getElementById('reset-filter-btn');
                if (resetBtn) resetBtn.click();
            }
        }

        /**
         * ==========================================
         * MODAL & UTILITIES
         * ==========================================
         */
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (modalId === 'modalMasuk') loadKategori('masuk_id_kategori_display');
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Helper untuk sync select display ke hidden input
        function syncSelectToHidden(sourceId, targetId) {
            const source = document.getElementById(sourceId);
            const target = document.getElementById(targetId);
            if (source && target) target.value = source.value;
        }

        /**
         * ==========================================
         * EVENT LISTENERS
         * ==========================================
         */
        function initEventListeners() {

            // Listener Perubahan Dropdown (Delegation)
            document.addEventListener('change', function (e) {
                if (e.target.id === 'masuk_id_kategori_display') {
                    syncSelectToHidden('masuk_id_kategori_display', 'masuk_id_kategori');
                }
                if (e.target.id === 'edit_id_kategori_display') {
                    syncSelectToHidden('edit_id_kategori_display', 'edit_id_kategori');
                }
            });

            // Listener Filter Kategori (Load categories when filter is opened or on init)
            const filterButton = document.getElementById('filter-button');
            if (filterButton) {
                filterButton.addEventListener('click', function() {
                    loadKategori('filter_kategori', 'Semua');
                });
            }
            // Also load once on init just in case
            loadKategori('filter_kategori', 'Semua');

            // Listener Modal Backdrop Click
            ['modalMasuk', 'modalKeluar', 'modalEdit'].forEach(id => {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) closeModal(id);
                    });
                }
            });

            // Listener Search Barang Keluar
            const searchInput = document.getElementById('search_barang_keluar');
            if (searchInput) searchInput.addEventListener('input', handleSearchInput);

            // Listener Tutup Autocomplete saat klik luar
            document.addEventListener('click', (e) => {
                const container = document.getElementById('search_results_container');
                const input = document.getElementById('search_barang_keluar');
                if (container && input && !input.contains(e.target) && !container.contains(e.target)) {
                    container.classList.add('hidden');
                }
            });

            // Listener Edit Data (Livewire/External Trigger)
            window.addEventListener('edit-data', async function (e) {
                const rawData = e.detail.row || e.detail;
                const data = rawData.data ? rawData.data : rawData;

                openModal('modalEdit');

                // Isi Form
                document.getElementById('edit_id_barang').value = data.id_barang || '';
                document.getElementById('edit_nama_barang').value = data.nama_barang || '';
                document.getElementById('edit_jumlah_pack').value = data.jumlah_pack || 0;
                document.getElementById('edit_nama_pack').value = data.nama_pack || '';
                document.getElementById('edit_jumlah_pcs').value = data.jumlah_pcs || 0;

                // Load & Set Kategori
                await loadKategori('edit_id_kategori_display');
                if (data.id_kategori) {
                    document.getElementById('edit_id_kategori_display').value = data.id_kategori;
                    document.getElementById('edit_id_kategori').value = data.id_kategori;
                }
            });
        }

        // Expose functions to global scope (untuk onclick di HTML button)
        window.tambahKategori = tambahKategori;
        window.hapusKategori = hapusKategori;
        window.submitBarangMasuk = submitBarangMasuk;
        window.submitBarangEdit = submitBarangEdit;
        window.submitBarangKeluar = submitBarangKeluar;
        window.openModal = openModal;
        window.closeModal = closeModal;
    </script>
@endsection