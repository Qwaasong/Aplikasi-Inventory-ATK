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
            padding: 8px 14px;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border-primary);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
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