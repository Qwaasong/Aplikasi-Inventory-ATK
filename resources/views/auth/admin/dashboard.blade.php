@extends('layouts.admin')

@section('title', 'Admin | Dashboard')

@section('content')

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* STYLE HYTAM */
        /* --- DARK MODE STYLES --- */

        /* Background utama */
        .dark body {
            background-color: #121212;
            /* Warna hitam elegan */
        }

        /* Box Container di Mode Gelap */
        .dark .box-container {
            background-color: #3C4446;
            /* Hitam abu-abu */
            color: #E0E0E0;
            /* Teks putih redup */
        }

        .dark .box-container span {
            color: #B0B0B0;
        }

        /* Chart Container di Mode Gelap */
        .dark .chart-main,
        .dark .chart-side {
            background-color: #3C4446;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        /* Filter & Teks Title */
        .dark .title {
            color: #FFFFFF;
        }

        .dark .filter {
            background: linear-gradient(135deg, #2D3748, #1A202C);
            border-color: #4A5568;
        }

        .dark .filter:hover {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-color: #3b82f6;
            color: #ffffff;
        }

        .dark .filter label,
        .dark .filter select {
            color: #E2E8F0;
        }

        /* Warna teks untuk dropdown select di Dark Mode */
        .dark .filter select {
            color: #E2E8F0;
            /* Teks putih keabu-abuan */
            background-color: transparent;
            /* Biar nyatu sama container filter */
        }

        /* Memperbaiki warna background dropdown menu (option) */
        .dark .filter select option {
            background-color: #1A202C;
            /* Warna gelap yang senada dengan filter */
            color: #E2E8F0;
            /* Pastikan teksnya kelihatan */
        }

        /* Biar lebih rapi, hilangkan border default browser pada select */
        .dark .filter select:focus {
            outline: none;
            box-shadow: none;
        }



        /* --- STYLE ASLI PUTIH --- */
        /* Saya biarkan 100% sama dengan kodemu sebelumnya */
        body {
            margin: 0;
            height: 100vh;
            overflow: hidden;
            /* Desktop gak bisa scroll (sesuai request) */
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
            /* Ukuran FIX sesuai keinginanmu */
            height: 152px;
            position: relative;
            padding: 16px;
            color: #000;
            background-color: #E7ECE6;
            border-radius: 12px;
            box-sizing: border-box;
        }

        .box-container span {
            position: absolute;
            top: 16px;
            left: 16px;
            font-size: 15px;
        }

        .box-container h3 {
            position: absolute;
            right: 16px;
            bottom: 16px;
            font-weight: 600;
            font-size: 45px;
            margin: 0;
        }

        .dashboard-charts {
            max-width: 1400px;
            width: 100%;
            margin: 20px auto;
            padding: 0 21px;
            display: grid;
            grid-template-columns: 3fr 1.3fr;
            /* Kiri Gede, Kanan Kecil */
            gap: 20px;
            height: calc(100vh - 280px);
            /* Tinggi Fix di Desktop */
        }

        .chart-main {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .chart-side {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 0px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .title {
            font-size: 16px;
            font-weight: 600;
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
            padding: 8px 14px;
            border-radius: 10px;
            background: linear-gradient(135deg, #EEF2FF, #F8FAFF);
            border: 1px solid #E0E7FF;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .filter i {
            color: #1C3AFF;
            font-size: 14px;
        }

        .filter label {
            font-weight: 500;
            color: #374151;
        }

        .filter select {
            border: none;
            background: transparent;
            font-size: 13px;
            color: #1F2937;
            outline: none;
            cursor: pointer;
            padding-right: 6px;
        }

        .filter:hover {
            background: linear-gradient(135deg, #E0E7FF, #EEF2FF);
            border-color: #1C3AFF;
        }

        .filter:focus-within {
            box-shadow: 0 0 0 3px rgba(28, 58, 255, 0.15);
        }

        /* --------------------------------------------------------- */
        /* --- BAGIAN MOBILE (MEDIA QUERY) --- */
        /* "Kalau layar kurang dari 991px (Tablet/HP), abaikan aturan atas, pakai aturan ini" */
        /* --------------------------------------------------------- */

        @media (max-width: 991px) {

            /* 1. Izinkan Scroll di HP */
            body {
                overflow-y: auto !important;
                /* Wajib, biar bisa scroll ke bawah */
                height: auto !important;
                padding-bottom: 50px;
                /* Jarak bawah biar gak mentok */
            }

            /* 2. Wrapper Kotak Atas jadi Tumpuk */
            .wrapper {
                flex-wrap: wrap;
                /* Biar kotak turun ke bawah kalau gak muat */
                padding: 20px;
                /* Jarak aman pinggir layar */
            }

            .box-container {
                /* Kita ganti width 310px jadi full width HP biar rapi */
                width: 100%;
                margin-bottom: 10px;
                /* Jarak antar kotak saat tumpuk */
            }

            /* 3. Header & Filter */
            .header-bar {
                margin: 20px;
                /* Rapikan margin */
                flex-direction: column;
                /* Judul di atas, Filter di bawah */
                align-items: flex-start;
                gap: 10px;
            }

            /* 4. Chart Section (Jantung Masalah) */
            .dashboard-charts {
                /* Ubah Grid jadi 1 Kolom ke bawah */
                grid-template-columns: 1fr !important;

                /* Matikan tinggi fix, biarkan memanjang */
                height: auto !important;
                display: flex;
                /* Atau pakai flex column juga bisa */
                flex-direction: column;
                padding: 0 20px;
                /* Jarak pinggir */
            }

            .chart-main,
            .chart-side {
                /* Beri tinggi pasti di HP agar grafik tidak gepeng */
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
                <select id="range">
                    <option value="minggu">Mingguan</option>
                    <option value="bulan">Bulanan</option>
                    <option value="tahun">Tahunan</option>
                </select>
            </div>
        </div>

        <div class="dashboard-charts">
            <div class="chart-main">
                <div id="chartMasukKeluar"></div>
            </div>
            <div class="chart-side">
                <div id="chartDistribusi"></div>
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
                        events: {
                            render: function () {
                                this.reflow;
                            }
                        }
                    },
                    title: {
                        text: 'Statistik Barang Masuk & Keluar',
                        style: { color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#333333' }
                    },
                    xAxis: {
                        categories: [],
                        labels: {
                            rotation: 0,
                            style: {
                                fontSize: '11px',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#333333'
                            },
                        }
                    },
                    yAxis: {
                        title: { text: 'Jumlah (Pcs)' },
                        allowDecimals: false,
                        labels: {
                            style: { color: document.documentElement.classList.contains('dark') ? '#B0B0B0' : '#666666' }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        itemStyle: {
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#333333'
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

                // Inisialisasi Chart Pie
                chartDistribusi = Highcharts.chart('chartDistribusi', {
                    chart: { type: 'pie', backgroundColor: 'transparent' },
                    title: {
                        text: 'Distribusi Barang',
                        style: { color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#333333' }
                    },
                    legend: {
                        itemStyle: {
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#333333'
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
            });
        </script>

@endsection