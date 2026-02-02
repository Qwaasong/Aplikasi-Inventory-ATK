@extends('layouts.admin')

@section('title', 'Admin | Dashboard')

@section('content')

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* --- DASHBOARD THEME SYNC --- */
        .dark .box-container {
            background-color: var(--bg-card);
            color: var(--text-main);
            border-color: var(--border-primary);
        }

        .dark .box-container span {
            color: var(--text-muted);
        }

        .dark .chart-main,
        .dark .chart-side {
            background-color: var(--bg-card);
            box-shadow: var(--shadow-lg);
            border-color: var(--border-primary);
        }

        .dark .title {
            color: var(--text-heading);
        }

        .dark .filter {
            background: var(--bg-input);
            border-color: var(--border-primary);
        }

        .dark .filter:hover {
            background: var(--nav-hover-bg);
            border-color: var(--accent-primary);
        }

        .dark .filter label,
        .dark .filter select {
            color: var(--text-main);
        }

        .dark .filter select option {
            background-color: var(--bg-main);
            color: var(--text-main);
        }

        /* --- BASE DASHBOARD STYLES --- */
        body {
            margin: 0;
            min-height: 100vh;
            background-color: var(--bg-main);
        }

        .wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .box-container {
            flex: 1;
            min-width: 250px;
            max-width: 450px;
            height: 152px;
            position: relative;
            padding: 16px;
            color: var(--text-main);
            background-color: var(--bg-card);
            border: 1px solid var(--border-primary);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .box-container span {
            position: absolute;
            top: 16px;
            left: 16px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .box-container h3 {
            position: absolute;
            right: 16px;
            bottom: 16px;
            font-weight: 600;
            font-size: 40px;
            margin: 0;
            color: var(--accent-primary);
        }

        .dashboard-charts {
            max-width: 1400px;
            width: 100%;
            margin: 20px auto;
            padding: 0 21px;
            display: grid;
            grid-template-columns: 3fr 1.3fr;
            gap: 20px;
        }

        .chart-main,
        .chart-side {
            background-color: var(--bg-card);
            border-radius: 16px;
            border: 1px solid var(--border-primary);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .header-bar {
            max-width: 1020px;
            margin: 30px;
            display: flex;
            align-items: center;
        }

        .filter {
            display: flex;
            align-items: center;
            margin: 12px;
            gap: 10px;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border-primary);
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
        }

        .filter i {
            color: var(--accent-primary);
        }

        .filter:hover {
            border-color: var(--accent-primary);
        }

        .filter:focus-within {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* --- MOBILE ADAPTATION --- */

        /* Style untuk Tombol Export */
        .export-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: auto;
        }

        .export-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .export-btn i {
            font-size: 16px;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Modal Content */
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transform: translateY(20px);
            transition: transform 0.3s;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6B7280;
            transition: color 0.2s;
        }

        .btn-close:hover {
            color: #111827;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-secondary {
            background: #F3F4F6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #E5E7EB;
        }

        .btn-primary {
            background: #10B981;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
        }

        /* Dark Mode Support */
        .dark .modal-content {
            background: #1F2937;
        }

        .dark .modal-title {
            color: #F9FAFB;
        }

        .dark .form-label {
            color: #E5E7EB;
        }

        .dark .form-control {
            background: #374151;
            border-color: #4B5563;
            color: #F9FAFB;
        }

        .dark .form-control:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .dark .btn-secondary {
            background: #4B5563;
            color: #E5E7EB;
        }

        .dark .btn-secondary:hover {
            background: #6B7280;
        }

        .dark .modal-footer {
            border-top-color: #4B5563;
        }


        /* --------------------------------------------------------- */
        /* --- BAGIAN MOBILE (MEDIA QUERY) --- */
        /* "Kalau layar kurang dari 991px (Tablet/HP), abaikan aturan atas, pakai aturan ini" */
        /* --------------------------------------------------------- */

        @media (max-width: 991px) {
            body {
                overflow-y: auto !important;
                height: auto !important;
                padding-bottom: 50px;
            }

            .wrapper {
                flex-wrap: wrap;
                padding: 20px;
            }

            .box-container {
                width: 100%;
                margin-bottom: 10px;
            }

            .header-bar {
                margin: 20px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .dashboard-charts {
                grid-template-columns: 1fr !important;
                height: auto !important;
                display: flex;
                flex-direction: column;
                padding: 0 20px;
            }

            .chart-main,
            .chart-side {
                height: 400px !important;
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>

    <body>
        <div class="wrapper">
            <div class="box-container">
                <span>Total Pack</span>
                <h3 id="totalPack"></h3>
            </div>

            <div class="box-container">
                <span>Total Pcs</span>
                <h3 id="totalPcs"></h3>
            </div>

            <div class="box-container">
                <span>Total User</span>
                <h3 id="totalUser"></h3>
            </div>
        </div>

        <div class="header-bar">
            <div class="title">
                Filter Statistik
            </div>

            <div class="filter">
                <select id="range"
                    class="appearance-none w-full rounded-md border border-gray-300 bg-white
                                                                                                        text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 
                                                                                                        dark:border-gray-600 dark:text-white">
                    <option value="minggu">Mingguan</option>
                    <option value="bulan">Bulanan</option>
                    <option value="tahun">Tahunan</option>
                </select>
            </div>

            <!-- Tombol Export Data -->
            <button class="export-btn" id="exportButton">
                <i class="fas fa-file-excel"></i> Export Data
            </button>

        </div>

        <div class="dashboard-charts">
            <div class="chart-main">
                <div id="chartMasukKeluar"></div>
            </div>
            <div class="chart-side">
                <div id="chartDistribusi"></div>
            </div>
        </div>
    </body>

    {{-- =================
    EXPORT Modal
    ================== --}}
    <div class="modal-overlay" id="exportModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Export Data Rekap</h3>
                <button class="btn-close" id="closeModal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Jenis Laporan</label>
                    <select class="form-control" id="exportType">
                        <option value="inventaris">Laporan Inventaris Barang (Master)</option>
                        <option value="mutasi">Laporan Riwayat Mutasi Stok</option>
                        <option value="kategori">Rekapitulasi Per Kategori</option>
                        <option value="user">Daftar Pengguna</option>
                    </select>
                </div>

                <div class="form-group" id="periodGroup" style="display: none;">
                    <label class="form-label">Pilih Periode</label>
                    <select class="form-control" id="exportPeriod">
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                        <option value="custom">Rentang Tanggal Custom</option>
                    </select>
                </div>

                <div class="form-group" id="monthYearGroup">
                    <div class="form-row">
                        <div>
                            <label class="form-label">Bulan</label>
                            <select class="form-control" id="exportMonth">
                                <option value="">Semua Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Tahun</label>
                            <select class="form-control" id="exportYear">
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="customDateGroup" style="display: none;">
                    <div class="form-row">
                        <div>
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama File</label>
                    <input type="text" class="form-control" id="fileName" placeholder="Contoh: rekap-barang-2024"
                        value="rekap-barang-{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelExport">Batal</button>
                <button class="btn btn-primary" id="confirmExport">
                    <i class="fas fa-download"></i> Download Excel
                </button>
            </div>
        </div>
    </div>

    {{-- ///////////// FUNGSI AMBIL DATA DARI API \\\\\\\\\\\\\\\ --}}

    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.8.0/dist/countUp.umd.js"></script>
    <script>
        // Inisialisasi variabel global agar bisa diakses fungsi load
        let chartMasukKeluar;
        let chartDistribusi;
        // Simpan instance di luar fungsi supaya bisa di-reset kalau filter diganti
        let countUpInstances = {};

        function animateValue(id, value) {
            const targetEl = document.getElementById(id);
            if (!targetEl) return;

            // Pastikan kita menggunakan konstruktor yang benar dari window.countUp
            const CountUpClass = window.countUp ? window.countUp.CountUp : null;

            if (CountUpClass) {
                // Jika sebelumnya sudah ada animasi di ID ini, kita reset
                if (countUpInstances[id]) {
                    countUpInstances[id].update(value);
                    return;
                }

                const options = {
                    duration: 2,
                    useEasing: true,
                    separator: '.',
                    startVal: 0
                };

                // Simpan instance ke dalam object global kita
                countUpInstances[id] = new CountUpClass(id, value, options);

                if (!countUpInstances[id].error) {
                    countUpInstances[id].start();
                } else {
                    console.error("Error CountUp:", countUpInstances[id].error);
                    targetEl.innerText = value;
                }
            } else {
                // Fallback jika library belum siap
                targetEl.innerText = value;
            }
        }

        // Fungsi untuk mengambil data dari API
        async function loadDashboardData(range) {
            try {
                const response = await fetch(`/api/dashboard/stats?filter=${range}`);
                const result = await response.json();

                if (result.success) {
                    // 1. Update Box Angka
                    animateValue('totalPack', result.summary.total_pack);
                    animateValue('totalPcs', result.summary.total_pcs);
                    animateValue('totalUser', result.summary.total_user);

                    // 2. Update Grafik Batang
                    const stats = result.charts.statistik_masuk_keluar;
                    chartMasukKeluar.xAxis[0].setCategories(stats.labels);

                    // Tambahkan parameter 'true' agar ada animasi saat data berubah
                    chartMasukKeluar.series[0].setData(stats.masuk, true, { duration: 1000 });
                    chartMasukKeluar.series[1].setData(stats.keluar, true, { duration: 1000 });

                    // 3. Update Grafik Pie (Distribusi)
                    const pieData = result.charts.distribusi_barang.map(item => ({
                        name: item.label,
                        y: item.jumlah
                    }));

                    // Tambahkan parameter 'true' juga di sini
                    chartDistribusi.series[0].setData(pieData, true, { duration: 1000 });

                }
            } catch (error) {
                console.error('Gagal memuat data:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.setOptions({
                lang: {
                    months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                        'November', 'Desember'],
                    shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    weekdays: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                },
                plotOptions: {
                    series: {
                        animation: {
                            duration: 1200 // Beri durasi 1.5 detik agar terlihat jelas animasinya
                        }
                    }
                }
            });


            // Inisialisasi Chart Batang
            chartMasukKeluar = Highcharts.chart('chartMasukKeluar', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                },
                title: {
                    text: 'Statistik Barang Masuk & Keluar',
                    style: { color: 'var(--text-main)' }
                },
                xAxis: {
                    categories: [],
                    labels: {
                        rotation: 0,
                        style: {
                            fontSize: '11px',
                            color: 'var(--text-main)'
                        },
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah (Pcs)',
                        style: { color: 'var(--text-main)' }
                    },
                    allowDecimals: false,
                    labels: {
                        style: { color: 'var(--text-muted)' }
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    itemStyle: {
                        color: 'var(--text-main)'
                    },
                    itemHoverStyle: {
                        color: '#1C3AFF' // Biar tetep keren pas di-hover
                    }
                },
                series: [
                    {
                        name: 'Barang Masuk',
                        color: '#1C3AFF',
                        data: []
                    },
                    {
                        name: 'Barang Keluar',
                        color: '#FF2929',
                        data: []
                    }
                ]
            });

            let debounceTimer; // Wadah untuk menahan eksekusi

            const observer = new MutationObserver(() => {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {
                    // Gunakan requestIdleCallback agar tidak mengganggu proses lain
                    (window.requestIdleCallback || window.requestAnimationFrame)(() => {
                        console.time("⏱️ TemaDiubah");

                        // Cukup panggil redraw tanpa update options yang berat
                        if (chartMasukKeluar) chartMasukKeluar.redraw(false);
                        if (chartDistribusi) chartDistribusi.redraw(false);

                        console.timeEnd("⏱️ TemaDiubah");
                    });
                }, 300); // Beri jeda lebih lama (300ms) agar transisi tema selesai total
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });


            // Inisialisasi Chart Pie
            chartDistribusi = Highcharts.chart('chartDistribusi', {
                chart: { type: 'pie', backgroundColor: 'transparent' },
                title: {
                    text: 'Distribusi Barang',
                    style: { color: 'var(--text-main)' }
                },
                legend: {
                    itemStyle: {
                        color: 'var(--text-main)'
                    },
                    itemHoverStyle: {
                        color: '#1C3AFF' // Biar tetep keren pas di-hover
                    }
                },
                credits: { enabled: false },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: { enabled: false },
                        showInLegend: true,
                    }
                },
                series: [{ name: 'Total', colorByPoint: true, data: [] }]
            });

            // Load data awal (Mingguan)
            loadDashboardData('mingguan');

            // Listener Filter Dropdown
            document.getElementById('range').addEventListener('change', function () {
                let val = this.value;
                // Sinkronisasi value dropdown dengan parameter controller
                if (val === 'minggu') val = 'mingguan';
                if (val === 'bulan') val = 'bulanan';
                if (val === 'tahun') val = 'tahunan';
                loadDashboardData(val);
            });

            // --- EXPORT LOGIC ---

            // 1. Tampilkan Modal
            document.getElementById('exportButton').addEventListener('click', function () {
                document.getElementById('exportModal').classList.add('active');
            });

            // 2. Tutup Modal
            document.getElementById('closeModal').addEventListener('click', function () {
                document.getElementById('exportModal').classList.remove('active');
            });

            document.getElementById('cancelExport').addEventListener('click', function () {
                document.getElementById('exportModal').classList.remove('active');
            });

            // 3. Listener Jenis Laporan (Show/Hide Periode)
            document.getElementById('exportType').addEventListener('change', function () {
                togglePeriodForms();
            });

            // 4. Listener Periode (Show/Hide Bulan/Tahun/Custom)
            document.getElementById('exportPeriod').addEventListener('change', function () {
                togglePeriodForms();
            });

            // 5. Initial State
            populateYears();
            togglePeriodForms();

            // 6. Tombol Download
            document.getElementById('confirmExport').addEventListener('click', function () {
                const type = document.getElementById('exportType').value;
                const period = document.getElementById('exportPeriod').value;
                const fileName = document.getElementById('fileName').value;

                // Gunakan helper route() dari Laravel agar URL dinamis dan sesuai prefix role
                // Pastikan route 'supervisor.laporan.export' juga sudah didefinisikan jika user adalah supervisor
                let baseUrl = "{{ Auth::user()->role === 'ADMIN' ? route('admin.laporan.export') : route('supervisor.laporan.export') }}";

                let url = `${baseUrl}?type=${type}&file_name=${fileName}`;

                if (type === 'mutasi') {
                    // Tambahkan parameter periode hanya jika tipe mutasi
                    url += `&period=${period}`;

                    if (period === 'custom') {
                        const start = document.getElementById('startDate').value;
                        const end = document.getElementById('endDate').value;
                        if (!start || !end) {
                            alert("Harap isi tanggal mulai dan akhir");
                            return;
                        }
                        url += `&start_date=${start}&end_date=${end}`;
                    } else {
                        const year = document.getElementById('exportYear').value;
                        const month = document.getElementById('exportMonth').value;
                        url += `&tahun=${year}&bulan=${month}`;
                    }
                }

                window.location.href = url;
            });
        });
        // Fungsi untuk mengisi tahun di dropdown
        function populateYears() {
            const yearSelect = document.getElementById('exportYear');
            const currentYear = new Date().getFullYear();

            yearSelect.innerHTML = '';

            // Tambahkan 5 tahun ke belakang dan 1 tahun ke depan
            for (let i = currentYear - 5; i <= currentYear + 1; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                if (i === currentYear) {
                    option.selected = true;
                }
                yearSelect.appendChild(option);
            }
        }

        // Fungsi untuk toggle tampilan form berdasarkan tipe & periode
        function togglePeriodForms() {
            const type = document.getElementById('exportType').value;
            const periodGroup = document.getElementById('periodGroup');
            const monthYearGroup = document.getElementById('monthYearGroup');
            const customDateGroup = document.getElementById('customDateGroup');

            // 1. Cek Tipe Laporan
            if (type === 'mutasi') {
                periodGroup.style.display = 'block';

                // 2. Cek Periode (hanya jika mutasi)
                const period = document.getElementById('exportPeriod').value;

                if (period === 'custom') {
                    monthYearGroup.style.display = 'none';
                    customDateGroup.style.display = 'block';

                    // Set tanggal default untuk custom range (bulan ini)
                    const today = new Date();
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                    const startDateInput = document.getElementById('startDate');
                    const endDateInput = document.getElementById('endDate');

                    if (!startDateInput.value) {
                        startDateInput.value = firstDay.toISOString().split('T')[0];
                    }
                    if (!endDateInput.value) {
                        endDateInput.value = lastDay.toISOString().split('T')[0];
                    }
                } else {
                    monthYearGroup.style.display = 'block';
                    customDateGroup.style.display = 'none';
                }

            } else {
                // Jika bukan mutasi, sembunyikan semua filter tanggal
                periodGroup.style.display = 'none';
                monthYearGroup.style.display = 'none';
                customDateGroup.style.display = 'none';
            }
        }

        // Fungsi untuk membuka modal
        function openModal() {
            const modal = document.getElementById('exportModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Set tanggal saat ini di nama file
            const now = new Date();
            const currentDate = now.toISOString().split('T')[0];
            document.getElementById('fileName').value = `rekap-barang-${currentDate}`;

            // Populate tahun jika belum
            if (!document.getElementById('exportYear').options.length) {
                populateYears();
            }

            // Tampilkan form sesuai periode yang dipilih
            togglePeriodForms();
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            const modal = document.getElementById('exportModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Fungsi untuk validasi form
        function validateExportForm() {
            const period = document.getElementById('exportPeriod').value;

            if (period === 'custom') {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                if (!startDate || !endDate) {
                    alert('Harap isi tanggal mulai dan tanggal akhir');
                    return false;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                    return false;
                }
            }

            const fileName = document.getElementById('fileName').value.trim();
            if (!fileName) {
                alert('Harap isi nama file');
                return false;
            }

            return true;
        }

        // Fungsi untuk export data
        async function exportData() {
            if (!validateExportForm()) {
                return;
            }

            const period = document.getElementById('exportPeriod').value;
            const fileName = document.getElementById('fileName').value.trim();
            let url = '/api/dashboard/export/rekap?';

            // Build parameter berdasarkan periode
            if (period === 'custom') {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                url += `start_date=${startDate}&end_date=${endDate}`;
            } else {
                const month = document.getElementById('exportMonth').value;
                const year = document.getElementById('exportYear').value;

                if (period === 'bulanan' && month) {
                    url += `tahun=${year}&bulan=${month}`;
                } else {
                    url += `tahun=${year}`;
                }
            }

            // Tambahkan nama file jika diperlukan (sesuaikan dengan backend)
            // url += `&file_name=${encodeURIComponent(fileName)}`;

            try {
                // Tampilkan loading jika perlu
                // setLoading(true);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Dapatkan blob dari response
                const blob = await response.blob();

                // Buat URL untuk blob
                const blobUrl = window.URL.createObjectURL(blob);

                // Buat link untuk download
                const link = document.createElement('a');
                link.href = blobUrl;

                // Dapatkan nama file dari header atau gunakan yang diinput
                const contentDisposition = response.headers.get('content-disposition');
                let finalFileName = fileName;

                if (contentDisposition) {
                    const matches = contentDisposition.match(/filename="?(.+)"?/);
                    if (matches && matches[1]) {
                        finalFileName = matches[1];
                    }
                } else {
                    // Tambahkan ekstensi jika belum ada
                    if (!finalFileName.endsWith('.xlsx')) {
                        finalFileName += '.xlsx';
                    }
                }

                link.download = finalFileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Cleanup URL
                window.URL.revokeObjectURL(blobUrl);

                // Tampilkan notifikasi sukses
                alert('Data berhasil diunduh!');

                // Tutup modal
                closeModal();

            } catch (error) {
                console.error('Export error:', error);
                alert('Gagal mengunduh data: ' + error.message);
            } finally {
                // setLoading(false);
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Event listeners untuk export functionality
            document.getElementById('exportButton').addEventListener('click', openModal);
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('cancelExport').addEventListener('click', closeModal);
            document.getElementById('exportPeriod').addEventListener('change', togglePeriodForms);
            document.getElementById('confirmExport').addEventListener('click', exportData);

            // Close modal ketika klik di luar konten modal
            document.getElementById('exportModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Close modal dengan tombol ESC
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });

            // Format nama file saat bulan/tahun berubah
            document.getElementById('exportMonth').addEventListener('change', function () {
                const month = this.value;
                const year = document.getElementById('exportYear').value;
                const period = document.getElementById('exportPeriod').value;

                if (period === 'bulanan') {
                    const monthNames = {
                        '01': 'Januari', '02': 'Februari', '03': 'Maret',
                        '04': 'April', '05': 'Mei', '06': 'Juni',
                        '07': 'Juli', '08': 'Agustus', '09': 'September',
                        '10': 'Oktober', '11': 'November', '12': 'Desember'
                    };

                    if (month) {
                        document.getElementById('fileName').value =
                            `rekap-barang-${monthNames[month]}-${year}`;
                    } else {
                        document.getElementById('fileName').value =
                            `rekap-barang-tahunan-${year}`;
                    }
                }
            });

            document.getElementById('exportYear').addEventListener('change', function () {
                const month = document.getElementById('exportMonth').value;
                const year = this.value;
                const period = document.getElementById('exportPeriod').value;

                if (period === 'bulanan' || period === 'tahunan') {
                    if (month && period === 'bulanan') {
                        const monthNames = {
                            '01': 'Januari', '02': 'Februari', '03': 'Maret',
                            '04': 'April', '05': 'Mei', '06': 'Juni',
                            '07': 'Juli', '08': 'Agustus', '09': 'September',
                            '10': 'Oktober', '11': 'November', '12': 'Desember'
                        };
                        document.getElementById('fileName').value =
                            `rekap-barang-${monthNames[month]}-${year}`;
                    } else {
                        document.getElementById('fileName').value =
                            `rekap-barang-tahunan-${year}`;
                    }
                }
            });
        });


    </script>

@endsection