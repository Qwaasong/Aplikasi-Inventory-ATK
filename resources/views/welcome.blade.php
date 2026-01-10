<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KopsisApp SMKN 9 Malang</title>
    <link rel="icon" sizes="16x16"
        href="https://preview.redd.it/random-question-but-does-anyone-have-versions-of-this-cat-v0-ya8qikz9kn0f1.png?auto=webp&s=c2fdba9a3904ab3bec9e7367e380f66343c2929a">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --blue-50: #f0f7ff;
            --blue-100: #e0efff;
            --blue-200: #b9dcff;
            --blue-300: #7cc0ff;
            --blue-400: #36a3ff;
            --blue-500: #0084ff;
            --blue-600: #0070e0;
            --blue-700: #005bb5;
            --blue-800: #004a94;
            --gray-50: #fafafa;
            --gray-100: #f5f5f5;
            --gray-200: #e5e5e5;
            --gray-300: #d4d4d4;
            --gray-600: #525252;
            --gray-700: #404040;
            --gray-900: #171717;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #fafbfc;
            color: var(--gray-900);
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: white;
            border-bottom: 1px solid #e1e4e8;
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .logo-text h1 {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 2px;
        }

        .logo-text p {
            font-size: 13px;
            color: var(--gray-600);
        }

        .nav-links {
            display: flex;
            gap: 4px;
            list-style: none;
        }

        .nav-links a {
            padding: 8px 16px;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            transition: background 0.2s;
        }

        .nav-links a:hover {
            background: var(--gray-100);
        }

        .nav-links a.active {
            background: var(--blue-50);
            color: var(--blue-600);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn-login {
            padding: 8px 20px;
            background: var(--blue-500);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: var(--blue-600);
        }

        /* Hero */
        .hero {
            max-width: 1280px;
            margin: 0 auto;
            padding: 25px 24px 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-content h2 {
            font-size: 44px;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1.2;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .hero-content p {
            font-size: 18px;
            color: var(--gray-600);
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: var(--blue-500);
            color: white;
        }

        .btn-primary:hover {
            background: var(--blue-600);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        .hero-image {
            position: relative;
        }

        .image-box {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--gray-200);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .image-box img {
            width: 100%;
            height: auto;
            display: block;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .image-box:hover img {
            transform: scale(1.02);
        }

        /* Chart */
        .chart-section {
            max-width: 1280px;
            margin: 0 auto 80px;
            padding: 0 24px;
        }

        .chart-box {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 32px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .chart-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .chart-tabs {
            display: flex;
            gap: 4px;
            background: var(--gray-100);
            padding: 4px;
            border-radius: 8px;
        }

        .tab {
            padding: 6px 16px;
            background: transparent;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab.active {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .chart-canvas {
            height: 320px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Footer */
        footer {
            background: #f8f9fa;
            border-top: 1px solid var(--gray-200);
            padding: 60px 24px 32px;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 16px;
        }

        .footer-section p {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--gray-600);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--blue-600);
        }

        .social {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            align-items: center;
        }

        .social a {
            width: 36px;
            height: 36px;
            background: var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.2s;
        }

        .social svg {
            width: 23px;
            height: auto;
            fill: currentColor;
        }

        .social a:hover {
            background: var(--blue-500);
            color: white;
        }

        .contact-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            transition: color 0.2s;
        }

        .contact-link:hover {
            color: var(--blue-500);
        }

        .contact-item {
            margin-bottom: 12px;
            font-size: 14px;
            color: var(--gray-600);
        }

        .contact-item {
            display: block;
        }

        .contact-item i,
        .contact-icon {
            color: var(--blue-500);
            width: 29px;
            height: auto;
            fill: currentColor;
            flex-shrink: 0;
        }

        .contact-icon svg {
            width: 19px;
            height: auto;
            display: block;
        }

        .footer-bottom {
            max-width: 1280px;
            margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
            text-align: center;
            font-size: 14px;
            color: var(--gray-600);
        }

        /* Responsive */
        @media (max-width: 768px) {

            .nav-links {
                display: none;
            }

            .nav-links.mobile-open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border-bottom: 1px solid var(--gray-200);
                padding: 16px;
            }

            .hero {
                grid-template-columns: 1fr;
                padding: 40px 24px;
                gap: 40px;
            }

            .hero-content h2 {
                font-size: 32px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                padding: 24px;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('/assets/images/logo.jpg') }}" width="30" height="50">
                </div>
                <div class="logo-text">
                    <h1>KopsisApp</h1>
                    <p>SMKN 9 Malang</p>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="#">Beranda</a></li>
                <li><a href="#pendapatan">Pendapatan</a></li>
                <li><a href="#kontak">Kontak</a></li>
                <li><a href="#tentangkami">Tentang Kami</a></li>
            </ul>

            <div class="nav-actions" id="#">
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
            </div>

        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h2>Selamat Datang di KopsisApp SMKN 9 Malang</h2>
            <p>
                KopsisApp adalah sistem informasi koperasi sekolah yang dibuat khusus buat nge-simplify semua urusan
                koperasi di SMKN 9 Malang. Mulai dari stok barang, transaksi, laporan keuangan, sampai manajemen
                pengguna semua dirangkum dalam satu aplikasi dengan tampilan yang clean dan gampang dipakai. Biar
                kerjaan jadi lebih cepat, anti-ribet, dan pastinya lebih modern.

            </p>
        </div>
        <div class="hero-image">
            <div class="image-box">
                <img src="{{ asset('assets/images/kopsis.jpg') }}" alt="Dashboard KopsisApp" id="heroImg">
            </div>
        </div>
    </section>

    <!-- Chart -->
    <section class="chart-section" id='pendapatan'>
        <div class="chart-box">
            <div class="chart-header">
                <h3>Ringkasan Pendapatan</h3>
                <div class="chart-tabs" id="filter">
                    <button class="tab" value="minggu">Minggu</button>
                    <button class="tab" value="bulan">Bulan</button>
                    <button class="tab" value="tahun">Tahun</button>
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal" id="modal">
        <button class="modal-close" id="modalClose">×</button>
        <img id="modalImg" alt="Preview">
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>KopsisApp</h4>
                <p>
                    Aplikasi ini dibangun dengan teknologi modern biar semua proses koperasi jadi lebih cepat dan
                    efisien. Dengan sistem yang lebih rapi dan otomatis, staf koperasi bisa fokus ngasih pelayanan
                    terbaik buat siswa dan guru tanpa ribet.

                </p>
                <div class="social">
                    <a href="https://smkn9malang.sch.id/">
                        {!! file_get_contents(public_path('assets/icon/www.svg')) !!}
                    </a>
                    <a href="https://www.tiktok.com/@smknegeri9malang">
                        {!! file_get_contents(public_path('assets/icon/tiktok.svg')) !!}
                    </a>
                    <a href="https://www.instagram.com/smknegeri9malang">
                        {!! file_get_contents(public_path('assets/icon/instagram.svg')) !!}
                    </a>
                    <a href="https://www.youtube.com/SkanawaTV">
                        {!! file_get_contents(public_path('assets/icon/youtube.svg')) !!}
                    </a>
                </div>
            </div>

            <div class="footer-section" id="tentangkami">
                <h4>Tentang Kami</h4>
                <p>
                    Kopsis SMKN 9 Malang selalu upgrade biar makin relevan. Lewat KopsisApp, kami menghadirkan koperasi
                    yang lebih cepat, transparan, dan nyaman dipakai semua warga sekolah. Simple, modern, tinggal klik!

                </p>
            </div>

            <div class="footer-section">
                <h4>Menu</h4>
                <ul class="footer-links">
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#pendapatan">Pendapatan</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                    <li><a href="#tentangkami">Tentang Kami</a></li>
                </ul>
            </div>

            <div class="footer-section" id="kontak">
                <h4>Kontak</h4>
                <div class="contact-item">
                    <a href="https://maps.app.goo.gl/64m3BUMz7vhgVY9Y7" class="contact-link">
                        <span class="contact-icon">{!! file_get_contents(public_path('assets/icon/lokasi.svg')) !!}</span>
                        <span>Jl. Soekarno Hatta No.9, Malang</span>
                    </a>
                </div>
                <div class="contact-item">
                    <a href="tel:0341727998" class="contact-link">
                        <span class="contact-icon">{!! file_get_contents(public_path('assets/icon/ponsel.svg')) !!}</span>
                        <span>(0341) 727998</span>
                    </a>
                </div>
                <div class="contact-item">
                    <a href="mailto:info@kopsisapp.com" class="contact-link">
                        <span class="contact-icon">{!! file_get_contents(public_path('assets/icon/email.svg')) !!}</span>
                        <span>humas@smkn9malang.sch.id</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2024 KopsisApp SMKN 9 Malang</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- ================= FILTER MINGGU BULAN TAHUN TIDAK BISA  ======================= --}}
    <script>
        // Modal
        const modal = document.getElementById('modal');
        const modalImg = document.getElementById('modalImg');
        const heroImg = document.getElementById('heroImg');
        const modalClose = document.getElementById('modalClose');
        const filtercontainer = document.getElementById('filter');
        const chartData = @json($dataChart);
        let currentFilter = "{{ $filter }}";

        let labels = [];
        let pemasukanData = [];
        let pengeluaranData = [];
        let financeChart = null; // Ubah dari const ke let

        document.addEventListener('DOMContentLoaded', () => {
            const scrollKe = new URLSearchParams(window.location.search).get('scroll');

            if (scrollKe === 'filter') {
                const el = document.getElementById('pendapatan'); // scroll ke section chart
                if (el) {
                    setTimeout(() => {
                        scrollWithOffset(el, 90); // 140px ruang dari atas — bisa disesuaikan
                    }); // tunggu page keload dulu
                }
            }

            function scrollWithOffset(element, offset) {
                const y = element.getBoundingClientRect().top + window.pageYOffset - offset;

                window.scrollTo({
                    top: y,
                    behavior: "smooth"
                });
            }

        });

        // Fungsi untuk mengelompokkan data berdasarkan periode
        function groupChartData(data, filterType) {
            if (!data || data.length === 0) {
                return { labels: [], pemasukan: [], pengeluaran: [] };
            }
            
            const groupedData = {};
            const labels = [];
            const pemasukan = [];
            const pengeluaran = [];
            
            data.forEach(item => {
                const date = new Date(item.tanggal);
                
                let key;
                switch(filterType) {
                    case 'minggu':
                        // Group by day of week (Monday=1, Tuesday=2, ..., Sunday=7)
                        const dayOfWeek = date.getDay(); // 0=Sunday, 1=Monday, ..., 6=Saturday
                        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        key = dayNames[dayOfWeek];
                        break;
                    case 'bulan':
                        // Group by day of month (1, 2, 3, ...)
                        key = date.getDate();
                        break;
                    case 'tahun':
                        // Group by month (Jan, Feb, Mar, ...)
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                        key = monthNames[date.getMonth()];
                        break;
                    default:
                        // Default to month grouping
                        key = date.getDate();
                }
                
                if (!groupedData[key]) {
                    groupedData[key] = {
                        total_pemasukan: 0,
                        total_pengeluaran: 0
                    };
                }
                
                groupedData[key].total_pemasukan += parseInt(item.total_pemasukan);
                groupedData[key].total_pengeluaran += parseInt(item.total_pengeluaran);
            });
            
            // Convert to arrays for chart
            Object.keys(groupedData).forEach(key => {
                labels.push(key);
                pemasukan.push(groupedData[key].total_pemasukan / 1000);
                pengeluaran.push(groupedData[key].total_pengeluaran / 1000);
            });
            
            // Sort labels appropriately
            if (filterType === 'minggu') {
                const dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                const sortedLabels = labels.sort((a, b) => {
                    return dayOrder.indexOf(a) - dayOrder.indexOf(b);
                });
                const sortedPemasukan = [];
                const sortedPengeluaran = [];
                sortedLabels.forEach(label => {
                    const index = labels.indexOf(label);
                    sortedPemasukan.push(pemasukan[index]);
                    sortedPengeluaran.push(pengeluaran[index]);
                });
                return {
                    labels: sortedLabels,
                    pemasukan: sortedPemasukan,
                    pengeluaran: sortedPengeluaran
                };
            } else if (filterType === 'tahun') {
                // Sort months chronologically
                const monthOrder = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                const sortedLabels = labels.sort((a, b) => {
                    return monthOrder.indexOf(a) - monthOrder.indexOf(b);
                });
                const sortedPemasukan = [];
                const sortedPengeluaran = [];
                sortedLabels.forEach(label => {
                    const index = labels.indexOf(label);
                    sortedPemasukan.push(pemasukan[index]);
                    sortedPengeluaran.push(pengeluaran[index]);
                });
                return {
                    labels: sortedLabels,
                    pemasukan: sortedPemasukan,
                    pengeluaran: sortedPengeluaran
                };
            } else {
                // For monthly grouping, sort by date
                const sortedLabels = labels.sort((a, b) => a - b);
                const sortedPemasukan = [];
                const sortedPengeluaran = [];
                sortedLabels.forEach(label => {
                    const index = labels.indexOf(label);
                    sortedPemasukan.push(pemasukan[index]);
                    sortedPengeluaran.push(pengeluaran[index]);
                });
                return {
                    labels: sortedLabels,
                    pemasukan: sortedPemasukan,
                    pengeluaran: sortedPengeluaran
                };
            }
        }
        
        // Fungsi untuk merender chart dengan data yang sudah dikelompokkan
        function renderChartWithGroupedData(filterType) {
            const groupedData = groupChartData(chartData, filterType);
            labels = groupedData.labels;
            pemasukanData = groupedData.pemasukan;
            pengeluaranData = groupedData.pengeluaran;
            
            // Destroy existing chart if it exists
            try {
                if (financeChart) {
                    financeChart.destroy();
                }
            } catch (error) {
                // Ignore errors when destroying chart
            }
            
            // Reinitialize chart with new data
            financeChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: pemasukanData,
                        borderColor: '#0084ff',
                        backgroundColor: 'rgba(0, 132, 255, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#0084ff',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }, {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        borderColor: '#FF0000',
                        backgroundColor: 'rgba(212, 212, 212, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#FF0000',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                color: '#525252',
                                font: {
                                    size: 13
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#171717',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: (context) => {
                                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString(
                                        'id-ID') + 'k';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            border: {
                                display: false
                            },
                            grid: {
                                color: '#f5f5f5'
                            },
                            ticks: {
                                color: '#737373',
                                callback: (value) => 'Rp ' + value + 'k'
                            }
                        },
                        x: {
                            border: {
                                display: false
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#737373'
                            }
                        }
                    }
                }
            });
        }
        
        filtercontainer.addEventListener('click', function(event) {
            // 'event.target' adalah elemen spesifik yang DIKLIK
            // (bisa <button> atau <div> itu sendiri)
            var clickedValue = event.target.value;

            // --- Tambahan Penting ---
            // Jika user mengklik spasi di antara tombol (yaitu mengklik <div> nya),
            // 'clickedValue' akan 'undefined'. Kita harus menanganinya.
            if (!clickedValue) {
                // Abaikan klik ini, karena bukan tombol yang diklik
                return;
            }
            // --- Akhir Tambahan ---

            var $filter;

            // Logika IF Anda (tidak berubah sama sekali)
            if (clickedValue === 'minggu' || clickedValue === 'bulan' || clickedValue === 'tahun') {
                $filter = clickedValue;
            } else {
                $filter = null; // Atau nilai default, misal 'harian'
            }

            // Lakukan sesuatu dengan $filter
            if ($filter) {
                currentFilter = $filter;
                // Update tab active state
                tabs.forEach(t => t.classList.remove('active'));
                event.target.classList.add('active');
                // Render chart with grouped data without page reload
                renderChartWithGroupedData($filter);
            } else {
                // Handle nilai tidak relevan
            }
        });

        // Initial data processing for default view
        if (chartData && chartData.length > 0) {
            // Group data initially based on current filter
            const groupedData = groupChartData(chartData, currentFilter);
            labels = groupedData.labels;
            pemasukanData = groupedData.pemasukan;
            pengeluaranData = groupedData.pengeluaran;
        }

        // Set initial active state for filter buttons
        document.addEventListener('DOMContentLoaded', () => {
            // Set Minggu as active by default
            const mingguTab = document.querySelector('.tab[value="minggu"]');
            if (mingguTab) {
                mingguTab.classList.add('active');
            }
        });

        heroImg.onclick = () => {
            modal.style.display = 'flex';
            modalImg.src = heroImg.src;
        };

        modalClose.onclick = () => {
            modal.style.display = 'none';
        };

        modal.onclick = (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        };

        // Tabs
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });


        // Chart
        const ctx = document.getElementById('financeChart').getContext('2d');

        // Hapus deklarasi const financeChart di sini karena sudah ada di atas
        financeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan',
                    data: pemasukanData,
                    borderColor: '#0084ff',
                    backgroundColor: 'rgba(0, 132, 255, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0084ff',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }, {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: '#FF0000',
                    backgroundColor: 'rgba(212, 212, 212, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#FF0000',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            color: '#525252',
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#171717',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: (context) => {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString(
                                    'id-ID') + 'k';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false
                        },
                        grid: {
                            color: '#f5f5f5'
                        },
                        ticks: {
                            color: '#737373',
                            callback: (value) => 'Rp ' + value + 'k'
                        }
                    },
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#737373'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
