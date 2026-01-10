@extends('layouts.main')
@section('title', 'KopsisApp - Pembukuan Transaksi')
@section('content')
    <div class="px-8 py-6">
        <div class="flex flex-col space-y-4">
            <div class="flex items-center text-sm text-gray-500">
                <span>Keuangan</span>
                <svg class="h-4 w-4 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>Pembukuan</span>
            </div>

            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-900 m-0">Pembukuan</h2>
                <div class="flex gap-4">
                    <button
                        class="hidden md:flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        onclick="openModal('export-pdf-modal')">
                        Export PDF
                    </button>
                    <button
                        class="hidden md:flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        onclick="window.location.href='{{ route('pembukuan_transaksi.create') }}'">
                        Tambah Transaksi
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 my-6">
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

            {{-- <div class="bg-white p-5 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500">Keuntungan</p>
                <p id="stat-keuntungan" class="text-2xl md:text-3xl font-bold text-gray-800">Loading...</p>
            </div> --}}
        </div>

        <!-- FAB Container -->
        <div class="md:hidden fab fixed bottom-6 right-6 flex flex-col items-end gap-3">
            <div class="fab-items flex flex-col-reverse items-end gap-3">

                <div class="fab-item-wrapper hidden-space" data-idx="0">
                    <span class="fab-label" onclick="window.location.href='{{ route('produk.create') }}'">Tambah
                        Transaksi</span>
                    <button
                        class="fab-item w-14 h-14 rounded-full flex items-center justify-center text-white font-semibold shadow-lg bg-gradient-to-br from-purple-500 to-pink-500"
                        onclick="window.location.href='{{ route('produk.create') }}'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 640 640" class="w-6 h-6">
                            <path
                                d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z" />
                        </svg>
                    </button>
                </div>

                <div class="fab-item-wrapper hidden-space" data-idx="1">
                    <span class="fab-label" onclick="openModal('export-pdf-modal')">Export PDF</span>
                    <button
                        class="fab-item w-14 h-14 rounded-full flex items-center justify-center text-white font-semibold shadow-lg bg-gradient-to-br from-blue-500 to-cyan-500"
                        onclick="openModal('export-pdf-modal')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor" class="w-6 h-6">
                            <path
                                d="M96 0C60.7 0 32 28.7 32 64l0 384c0 35.3 28.7 64 64 64l80 0 0-112c0-35.3 28.7-64 64-64l176 0 0-165.5c0-17-6.7-33.3-18.7-45.3L290.7 18.7C278.7 6.7 262.5 0 245.5 0L96 0zM357.5 176L264 176c-13.3 0-24-10.7-24-24L240 58.5 357.5 176zM240 380c-11 0-20 9-20 20l0 128c0 11 9 20 20 20s20-9 20-20l0-28 12 0c33.1 0 60-26.9 60-60s-26.9-60-60-60l-32 0zm32 80l-12 0 0-40 12 0c11 0 20 9 20 20s-9 20-20 20zm96-80c-11 0-20 9-20 20l0 128c0 11 9 20 20 20l32 0c28.7 0 52-23.3 52-52l0-64c0-28.7-23.3-52-52-52l-32 0zm20 128l0-88 12 0c6.6 0 12 5.4 12 12l0 64c0 6.6-5.4 12-12 12l-12 0zm88-108l0 128c0 11 9 20 20 20s20-9 20-20l0-44 28 0c11 0 20-9 20-20s-9-20-20-20l-28 0 0-24 28 0c11 0 20-9 20-20s-9-20-20-20l-48 0c-11 0-20 9-20 20z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button id="fabMain"
                class="fab-main w-14 h-14 rounded-full btn-lg shadow-lg flex items-center justify-center transition-all bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white"
                aria-expanded="false" aria-label="Open FAB" title="Open FAB">
                <span id="iconX" class="fab-icon text-xl visible" role="img" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 640 640" class="w-6 h-6">
                        <path
                            d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z" />
                    </svg>
                </span>
                {{-- Jika fab.js Anda butuh ikon 'close' terpisah, tambahkan di sini --}}
                {{-- <span id="iconClose" class="fab-icon text-xl hidden" ...> ... SVG X ... </span> --}}
            </button>
        </div>

        <hr class="my-6 border-gray-200">

       <!-- Tabel Pembukuan Transaksi-->
        <x-table :data-table="[
                    'Tanggal' => 'tanggal', 
                    'Keterangan' => 'keterangan', 
                    'Pemasukan' => 'pemasukan', 
                    'Pengeluaran' => 'pengeluaran',
                    'Saldo' => 'saldo'   
                    ]" data-url="{{ route('api.pembukuan_transaksi.index') }}" :showAction="false">
        </x-table>
    </div>

    <div id="export-pdf-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-start justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl w-md max-w-sm">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Export Laporan PDF</h3>
                <button onclick="closeModal('export-pdf-modal')"
                    class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            </div>

          {{-- Filter pada export --}}
            <p class="text-sm text-gray-600 mb-2">Silakan pilih rentang tanggal laporan yang ingin Anda export.</p>

            <form id="export-pdf-form" action="{{ route('pembukuan_transaksi.export_pdf') }}" method="GET"
                target="_blank">

                <div id="myDatePickerContainer" class="border rounded-md relative z-50"></div>

                <input type="hidden" name="start_date" id="start_date_input">
                <input type="hidden" name="end_date" id="end_date_input">

                <div class="flex justify-end mt-6 space-x-2">
                    <button type="button" onclick="closeModal('export-pdf-modal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                        Export ke PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/fab.js') }}"></script>
    <script src="{{ asset('assets/js/DatePicker.js') }}"></script>
    {{-- Hapus pembukuan_transaksi.js karena kodenya sudah digabung di bawah --}}

    <script>
        // --- Bagian 1: Fungsi Statistik (dari pembukuan_transaksi.js) ---
        function formatRupiah(angka) {
            if (typeof angka === 'string') angka = parseFloat(angka) || 0;
            if (typeof angka !== 'number') angka = 0;
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function tampilkanStatistik(statistik) {
            document.getElementById('stat-pemasukan').textContent = formatRupiah(statistik.pemasukan);
            document.getElementById('stat-pengeluaran').textContent = formatRupiah(statistik.pengeluaran);
            document.getElementById('stat-saldo').textContent = formatRupiah(statistik.saldo);
            document.getElementById('stat-total-produk').textContent = statistik.total_produk || 0;
            if (document.getElementById('stat-keuntungan')) {
                document.getElementById('stat-keuntungan').textContent = formatRupiah(statistik.keuntungan);
            }
        }

        function fetchStatistikData() {
            $.ajax({
                url: '{{ route('api.beranda.index') }}', // Sesuaikan nama rute jika perlu
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data.statistik) {
                        tampilkanStatistik(response.data.statistik);
                    } else {
                        console.error('Error memuat statistik:', response.message);
                        setDefaultValues();
                    }
                },
                error: function(error) {
                    console.error('Ajax error statistik:', error);
                    setDefaultValues();
                }
            });
        }

        function setDefaultValues() {
            document.getElementById('stat-pemasukan').textContent = formatRupiah(0);
            document.getElementById('stat-pengeluaran').textContent = formatRupiah(0);
            document.getElementById('stat-saldo').textContent = formatRupiah(0);
            document.getElementById('stat-total-produk').textContent = 0;
            if (document.getElementById('stat-keuntungan')) {
                document.getElementById('stat-keuntungan').textContent = formatRupiah(0);
            }
        }

        // --- Bagian 2: Fungsi Modal (diperbarui dan AKTIF) ---
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        }

        // --- Bagian 3: Main DOMContentLoaded ---
        document.addEventListener('DOMContentLoaded', () => {

            // 1. Panggil Statistik
            fetchStatistikData();

            // 2. Definisikan helper DatePicker
            const normalizeDate = (d) => {
                if (!d) return '';
                const date = new Date(d);
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${date.getFullYear()}-${m}-${day}`;
            };

            // 3. Inisialisasi DatePicker MODAL
            const modalContainer = document.getElementById('myDatePickerContainer');
            if (modalContainer) {
                const startDateInput = document.getElementById('start_date_input');
                const endDateInput = document.getElementById('end_date_input');
                new DatePicker(modalContainer, {
                    type: 'dateRange',
                    direction: 'top',
                    data: {
                        startDate: null,
                        endDate: null
                    },
                    options: {
                        onApply: function(dates) {
                            startDateInput.value = normalizeDate(dates.startDate);
                            endDateInput.value = normalizeDate(dates.endDate);
                        }
                    }
                });
            }

            // 4. Inisialisasi DatePicker FILTER
            const filterDateContainer = document.getElementById('filterDatePickerContainer');
            if (filterDateContainer) {
                const filterDateDisplay = document.getElementById('filter_date_range_display');
                const filterStartDate = document.getElementById('filter_start_date');
                const filterEndDate = document.getElementById('filter_end_date');
                const filterDatePicker = new DatePicker(filterDateContainer, {
                    type: 'dateRange',
                    options: {
                        onApply: function(dates) {
                            const startDate = normalizeDate(dates.startDate);
                            const endDate = normalizeDate(dates.endDate);
                            filterStartDate.value = startDate;
                            filterEndDate.value = endDate;
                            filterDateDisplay.value = `${startDate} s/d ${endDate}`;
                            filterDatePicker.toggle(false);
                        },
                        onClear: function() {
                            filterStartDate.value = '';
                            filterEndDate.value = '';
                            filterDateDisplay.value = '';
                        }
                    }
                });
                filterDateDisplay.addEventListener('click', () => {
                    filterDatePicker.toggle();
                });
            }

            // 5. Validasi Form PDF
            const exportForm = document.getElementById('export-pdf-form');
            if (exportForm) {
                exportForm.addEventListener('submit', function(e) {
                    const start = document.getElementById('start_date_input').value;
                    const end = document.getElementById('end_date_input').value;
                    if (!start || !end) {
                        e.preventDefault(); // Hentikan pengiriman form
                        alert('Silakan pilih rentang tanggal terlebih dahulu.');
                    }
                });
            }

        }); // Akhir DOMContentLoaded
    </script>
@endsection
