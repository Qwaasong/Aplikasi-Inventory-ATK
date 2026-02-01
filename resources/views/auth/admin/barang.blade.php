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

            <button id="fabMain" class="fab-main rounded-full shadow-xl bg-[var(--accent-primary)] hover:opacity-90 text-white z-50"
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
                                <label for="filter_tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal
                                    Awal</label>
                                <input type="date" name="filter[tanggal_awal]" id="filter_tanggal_awal"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="filter_tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal
                                    Akhir</label>
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
                                        class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-500 hover:bg-blue-600 transition shadow-sm">
                                        <img src="{{ asset('assets/icon/tambah.svg') }}" class="w-4 h-4">
                                    </button>

                                    <button type="button" onclick="hapusKategori('masuk_id_kategori_display')"
                                        class="w-9 h-9 flex items-center justify-center rounded-md bg-red-500 hover:bg-red-600 transition shadow-sm">
                                        <img src="{{ asset('assets/icon/kurang.svg') }}" class="w-4 h-4">
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
                    <button onclick="closeModal('modalKeluar')"
                    {{-- text-gray-800 dark:text-gray-300 text-gray-800 hover:text-red-600 --}}
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
                        class="absolute top-4 right-4 text-gray-800 hover:text-red-600 text-3xl font-light">&times;</button>
                </div>
                <form id="formBarangEdit" onsubmit="submitBarangEdit(event)">
                    <input type="hidden" name="id_barang" id="edit_id_barang">

                    <div class="p-8 pt-4">
                        <h3 class="text-lg mb-6 text-black">Edit Barang</h3>
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
                                        class="w-9 h-9 flex items-center justify-center rounded-md bg-blue-500 hover:bg-blue-600 transition">
                                        <img src="{{ asset('assets/icon/tambah.svg') }}" class="w-4 h-4">
                                    </button>

                                    <button type="button" onclick="hapusKategori('edit_id_kategori_display')"
                                        class="w-9 h-9 flex items-center justify-center rounded-md bg-red-500 hover:bg-red-600 transition">
                                        <img src="{{ asset('assets/icon/kurang.svg') }}" class="w-4 h-4">
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

        // Jalankan kode ini setelah DOM selesai dimuat
        document.addEventListener('change', function (e) {
            if (e.target.id === 'masuk_id_kategori_display') {
                document.getElementById('masuk_id_kategori').value = e.target.value;
            }

            if (e.target.id === 'edit_id_kategori_display') {
                document.getElementById('edit_id_kategori').value = e.target.value;
            }
        });

        document.addEventListener('change', function (e) {
            // Jika dropdown kategori diubah
            if (e.target && e.target.id === 'edit_id_kategori_display') {
                const selectedValue = e.target.value;
                // Masukkan nilainya ke hidden input agar terbaca oleh FormData
                document.getElementById('edit_id_kategori').value = selectedValue;
                console.log("ID Kategori terpilih:", selectedValue);
            }
        });

        // Fungsi tambah kategori
        async function tambahKategori() {
            const namaBaru = prompt('Masukkan Nama Kategori Baru:');

            if (namaBaru) {
                try {
                    const response = await fetch("{{ route('api.kategori.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ nama_kategori: namaBaru }) // Kirim nama_kategori ke controller
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert('Kategori "' + namaBaru + '" Berhasil Ditambahkan');
                        // Refresh dropdown kategori agar data baru muncul
                        await loadKategori('masuk_id_kategori_display');
                        await loadKategori('edit_id_kategori_display');

                        // window.location.reload();

                    } else {
                        alert('Gagal' + (result.message || 'Terjadi kesalahan'));
                    }
                } catch (error) {
                    console.error('Gagal menambah kategori:', error);
                    alert('Sistem Sedang Bermasalah Coba Lagi Nanti');
                }
            }
        }

        // Fungsi hapus kategori
        // Ganti fungsi hapusKategori yang lama dengan ini
        async function hapusKategori(targetId) {
            const select = document.getElementById(targetId); // Sekarang dinamis berdasarkan targetId
            const idTerpilih = select.value;

            if (!idTerpilih) {
                alert('Pilih Kategori Yang Ingin Dihapus Terlebih Dahulu');
                return;
            }

            const teksTerpilih = select.options[select.selectedIndex].text;

            if (confirm(`Peringatan..!! Apakah Anda Yakin Menghapus ${teksTerpilih}?`)) {
                try {
                    const response = await fetch(`{{ url('api/kategori') }}/${idTerpilih}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        accept: 'application/json'
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Kategori Berhasil Dihapus');

                        // PERBAIKAN: Jangan akses result.data.id_kategori
                        // Cukup refresh dropdown dan arahkan ke pilihan kosong
                        await loadKategori('masuk_id_kategori_display');
                        await loadKategori('edit_id_kategori_display');

                        // Reset nilai select ke default agar tidak error
                        document.getElementById(targetId).value = "";

                        // Jika kamu ingin otomatis refresh halaman sesuai keinginanmu sebelumnya:
                        // window.location.reload();
                    }
                } catch (error) {
                    console.error('Error saat menghapus:', error);
                }
            }
        }

        // Fungsi Membuka Modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Jika membuka modal masuk, load kategori
            if (modalId === 'modalMasuk') {
                loadKategori('masuk_id_kategori_display');
            }
        }

        // Fungsi Menutup Modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Menutup modal jika user klik di area gelap (backdrop)
        ['modalMasuk', 'modalKeluar', 'modalEdit'].forEach(id => {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal(id);
                }
            });
        });


        // Load Kategori (Reusable)
        async function loadKategori(targetId) {
            const selectKategori = document.getElementById(targetId);
            // if (!selectKategori) return; // Mencegah error 'null'

            try {
                const response = await fetch("{{ route('api.kategori.index') }}");
                const result = await response.json();

                if (result.success) {
                    // Kosongkan dulu pilihan yang ada kecuali baris pertama (Pilih Kategori)
                    selectKategori.innerHTML = '<option value="">Pilih Kategori</option>';

                    result.data.forEach(kat => {
                        const option = document.createElement('option');
                        option.value = kat.id_kategori; // Sesuaikan dengan primary key di tabel kategori
                        option.textContent = kat.nama_kategori;
                        selectKategori.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Gagal memuat kategori:', error);
            }
        }

        // Submit Form
        async function submitBarangMasuk(e) {
            e.preventDefault();
            const form = e.target;

            // Paksa sinkronisasi sekali lagi sebelum kirim
            document.getElementById('masuk_id_kategori').value = document.getElementById('masuk_id_kategori_display').value;

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch("{{ route('api.barang.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // Wajib agar dpt respon JSON jika error 422
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    alert('Barang Berhasil Ditambahkan');
                    location.reload();
                } else {
                    alert('Gagal: ' + (result.message || 'Cek kembali inputan Anda'));
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // --- LOGIKA BARANG KELUAR (AUTOCOMPLETE) ---

        let debounceTimer;
        const searchInput = document.getElementById('search_barang_keluar');
        const resultsContainer = document.getElementById('search_results_container');
        const idBarangInput = document.getElementById('id_barang_keluar');

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = this.value;

            if (query.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetchBarang(query);
            }, 300); // Tunggu 300ms sebelum request
        });

        async function fetchBarang(query) {
            try {
                // Gunakan endpoint index yang sudah ada dengan param search
                const response = await fetch(`{{ route('api.barang.index') }}?search=${query}`);
                const result = await response.json();

                resultsContainer.innerHTML = '';

                if (result.success && result.data.data.length > 0) {
                    resultsContainer.classList.remove('hidden');
                    result.data.data.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('search-item');
                        div.textContent = `${item.nama_barang} (Stok: ${item.jumlah_pcs})`;

                        div.onclick = function () {
                            searchInput.value = item.nama_barang;
                            idBarangInput.value = item.id_barang; // Simpan ID
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

        // Sembunyikan dropdown jika klik di luar
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });

        // Submit Barang Keluar
        async function submitBarangKeluar(e) {
            e.preventDefault();

            // Ambil elemen input
            const idBarangInput = document.getElementById('id_barang_keluar');
            const jumlahInput = document.getElementById('jumlah_pcs_keluar');

            // Validasi sederhana
            const id_barang = idBarangInput.value;
            const jumlah_keluar = parseInt(jumlahInput.value);

            if (!id_barang) {
                alert('ID Barang kosong. Silakan cari barang ulang.');
                return;
            }

            if (!jumlah_keluar || jumlah_keluar <= 0) {
                alert('Masukkan jumlah barang yang valid (minimal 1).');
                return;
            }

            // Siapkan data
            const payload = {
                id_barang: id_barang,
                jumlah_pcs_keluar: jumlah_keluar
            };

            console.log('Mengirim data:', payload); // Cek di Console

            try {
                const response = await fetch("{{ route('api.barang.keluar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                // Cek status HTTP (200 OK, 404 Not Found, 500 Server Error)
                if (!response.ok) {
                    // Jika error dari server (misal stok kurang), ambil pesan errornya
                    const errorResult = await response.json();
                    throw new Error(errorResult.message || 'Terjadi kesalahan di server (Status ' + response.status + ')');
                }

                const result = await response.json();

                if (result.success) {
                    alert('Sukses! Stok berhasil dikurangi.');
                    closeModal('modalKeluar');
                    document.getElementById('formBarangKeluar').reset();
                    window.location.reload();
                } else {
                    alert('Gagal: ' + result.message);
                }

            } catch (error) {
                console.error('Error Detail:', error);
                alert('Gagal: ' + error.message);
            }
        }

        // --- LISTENER EDIT DATA (Dari Component Table) ---
        window.addEventListener('edit-data', function (e) {
            // 1. Ambil raw data dari event
            const rawData = e.detail.row || e.detail;

            // 2. DETEKSI STRUKTUR DATA (Perbaikan Utama)
            // Jika ada properti .data di dalamnya, kita masuk ke situ.
            // Jika tidak, kita pakai rawData itu sendiri.
            const data = (rawData.data) ? rawData.data : rawData;

            console.log('Data Final untuk Edit:', data); // Cek ini di console, harusnya langsung objek barang

            // 3. Buka Modal
            openModal('modalEdit');

            // 4. Isi Form Input Teks
            document.getElementById('edit_id_barang').value = data.id_barang || '';

            // Debugging visual: jika nama barang masih kosong, kita beri pesan error di inputnya
            document.getElementById('edit_nama_barang').value = data.nama_barang || '(Nama tidak terbaca)';

            document.getElementById('edit_jumlah_pack').value = data.jumlah_pack || 0;
            document.getElementById('edit_nama_pack').value = data.nama_pack || 0;
            document.getElementById('edit_jumlah_pcs').value = data.jumlah_pcs || 0;

            // 5. Isi Kategori
            // Dari log Anda, id_kategori ada langsung di root (id_kategori: 3), tidak perlu masuk object kategori
            const idKat = data.id_kategori;

            loadKategori('edit_id_kategori_display').then(() => {
                const selectDisplay = document.getElementById('edit_id_kategori_display');
                const inputHidden = document.getElementById('edit_id_kategori');

                if (idKat) {
                    selectDisplay.value = idKat;
                    inputHidden.value = idKat;
                }
                // } else {
                //     // Jika kategori kosong/null
                //     selectDisplay.value = "";
                // }
            });
        });

        // Submit Edit
        async function submitBarangEdit(e) {
            e.preventDefault();

            const form = document.getElementById('formBarangEdit');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Ambil ID dari hidden input
            const id = document.getElementById('edit_id_barang').value;

            if (!id) {
                alert('ID Barang tidak ditemukan!');
                return;
            }

            try {
                // Gunakan URL update yang benar. Perhatikan method PUT/PATCH
                // Laravel Resource Route biasanya menggunakan PUT/PATCH untuk update
                const response = await fetch(`{{ url('api/barang') }}/${id}`, {
                    method: 'PUT', // Fetch support PUT directly
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Data berhasil diperbarui!');
                    closeModal('modalEdit');
                    window.location.reload();
                } else {
                    alert('Gagal update: ' + (result.message || 'Unknown error'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem saat update');
            }
        }
    </script>
@endsection