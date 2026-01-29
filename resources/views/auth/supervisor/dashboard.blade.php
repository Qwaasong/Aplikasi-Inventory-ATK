@extends('layouts.admin')

@section('title', 'Admin | Dashboard')

@section('content')

    <script src="https://code.highcharts.com/12/highcharts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* --- STYLE ASLI (DESKTOP) --- */
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
            padding-top: 20px;
        }

        .box-container {
            width: 310px;
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
                <h3 id="totalPack">0</h3>
            </div>

            <div class="box-container">
                <span>Total Pcs</span>
                <h3 id="totalPcs">0</h3>
            </div>

            <div class="box-container">
                <span>Total User</span>
                <h3 id="totalUser">0</h3>
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


        <script>
            // Saya tambahkan credits: false sesuai request sebelumnya biar rapi
            // Sisanya SAMA PERSIS dengan kodemu
            Highcharts.chart('chartMasukKeluar', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    spacing: [20, 20, 20, 20],
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Statistik Barang Masuk & Keluar'
                },
                xAxis: {
                    categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah'
                    }
                },
                plotOptions: {
                    column: {
                        borderRadius: 6,
                        pointPadding: 0.1,
                        groupPadding: 0.15,
                        clipping: true,
                    }
                },
                tooltip: {
                    outside: false,
                },
                series: [
                    {
                        name: 'Barang Masuk',
                        data: [12, 19, 8, 15, 22, 10, 5],
                        color: '#1C3AFF'
                    },
                    {
                        name: 'Barang Keluar',
                        data: [7, 14, 6, 8, 18, 9, 4],
                        color: '#FF2929'
                    }
                ]
            });
        </script>

        <script>
            Highcharts.chart('chartDistribusi', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    spacing: [20, 20, 20, 20],
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Distribusi Barang'
                },
                xAxis: {
                    categories: ['Buku Tulis', 'Alat Tulis', 'Seragam']
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah'
                    }
                },
                plotOptions: {
                    column: {
                        borderRadius: 6
                    }
                },
                series: [
                    {
                        name: 'Jumlah',
                        data: [40, 25, 15],
                        color: '#1C3AFF'
                    }
                ]
            });
        </script>

@endsection

    @section('script')
        <script>
            // Inisialisasi Chart Global agar bisa diupdate
            let chartMasukKeluar;
            let chartDistribusi;

            // Fungsi untuk mengambil data dari API
            async function loadDashboardData(filter = 'mingguan') {
                try {
                    const response = await fetch(`/api/dashboard/stats?filter=${filter}`);
                    const res = await response.json();

                    if (res.success) {
                        // 1. Update Summary Cards
                        document.getElementById('totalPack').innerText = res.summary.total_pack;
                        document.getElementById('totalPcs').innerText = res.summary.total_pcs;
                        document.getElementById('totalUser').innerText = res.summary.total_user;

                        // 2. Update Chart Statistik (Masuk & Keluar)
                        chartMasukKeluar.update({
                            xAxis: { categories: res.charts.statistik_masuk_keluar.labels },
                            series: [
                                { name: 'Barang Masuk', data: res.charts.statistik_masuk_keluar.masuk },
                                { name: 'Barang Keluar', data: res.charts.statistik_masuk_keluar.keluar }
                            ]
                        });

                        // 3. Update Chart Distribusi (Berdasarkan Kategori)
                        const kategoriLabels = res.charts.distribusi_barang.map(item => item.label);
                        const kategoriData = res.charts.distribusi_barang.map(item => item.jumlah);

                        chartDistribusi.update({
                            xAxis: { categories: kategoriLabels },
                            series: [{ data: kategoriData }]
                        });
                    }
                } catch (error) {
                    console.error("Gagal mengambil data dashboard:", error);
                }
            }

            // Inisialisasi Chart saat halaman pertama kali dibuka
            document.addEventListener('DOMContentLoaded', function () {
                chartMasukKeluar = Highcharts.chart('chartMasukKeluar', {
                    chart: { type: 'column' },
                    title: { text: 'Statistik Barang Masuk & Keluar' },
                    credits: { enabled: false },
                    xAxis: { categories: [] },
                    series: [
                        { name: 'Barang Masuk', color: '#1C3AFF', data: [] },
                        { name: 'Barang Keluar', color: '#FF2929', data: [] }
                    ]
                });

                chartDistribusi = Highcharts.chart('chartDistribusi', {
                    chart: { type: 'column' },
                    title: { text: 'Distribusi Barang per Kategori' },
                    credits: { enabled: false },
                    xAxis: { categories: [] },
                    series: [{ name: 'Jumlah Barang', color: '#1C3AFF', data: [] }]
                });

                // Load data pertama kali (default mingguan)
                loadDashboardData('mingguan');

                // Event listener untuk Filter Dropdown
                document.getElementById('range').addEventListener('change', function () {
                    loadDashboardData(this.value);
                });
            });
        </script>

    @endsection