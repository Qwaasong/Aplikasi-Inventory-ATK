function fetchBerandaData() {
    $.ajax({
        url: '/api/beranda',
        method: 'GET',
        success: function (response) {
            if (response.success) {
                // console.table('Data beranda:', response.data);
                if (response.data.statistik) {
                    tampilkanStatistik(response.data.statistik);
                }
            } else {
                // console.error('Error:', response.message);
            }
        },
        error: function (error) {
            // console.error('Ajax error:', error);
        }
    });
}

function fetchBerandaChartData() {
    $.ajax({
        url: '/api/beranda/chart',
        method: 'GET',
        success: function (response) {
            if (response.success) {
                // console.table('Data Chart:', response.data);
                if (response.data.chart) {
                    tampilkanChartPemasukanPengeluaran(response.data.chart);
                }
            } else {
                // console.error('Error:', response.message);
            }
        },
        error: function (error) {
            // console.error('Ajax error:', error);
        }
    });
}

function fetchBerandaChartDistribusiData() {
    $.ajax({
        url: '/api/beranda/distribusi-produk',
        method: 'GET',
        success: function (response) {
            if (response.success) {
                // console.log(response.data);
                if (response.data) {
                    tampilkanChartDistribusiProduk(response.data);
                }
            } else {
                // console.error('Error:', response.message);
            }
        },
        error: function (error) {
            // console.error('Ajax error:', error);
        }
    });
}

function fetchBerandaPersentaseData() {
    $.ajax({
        url: '/api/beranda/persentase',
        method: 'GET',
        success: function (response) {
            if (response.success) {
                // console.table(response.data);
                if (response.data) {
                    tampilkanPersentase(response.data);
                }
            } else {
                // console.error('Error:', response.message);
            }
        },
        error: function (error) {
            // console.error('Ajax error:', error);
        }
    });
}

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

document.addEventListener('DOMContentLoaded', function () {
    fetchBerandaData();
    fetchBerandaChartData();
    fetchBerandaChartDistribusiData();
    fetchBerandaPersentaseData();
});

function tampilkanStatistik(statistik) {
    document.getElementById('stat-pemasukan').textContent = formatRupiah(statistik.pemasukan);
    document.getElementById('stat-pengeluaran').textContent = formatRupiah(statistik.pengeluaran);
    document.getElementById('stat-saldo').textContent = formatRupiah(statistik.saldo);
    document.getElementById('stat-total-produk').textContent = statistik.total_produk;
}

function tampilkanPersentase(persentase) {
    // ======================
    // PEMASUKAN & PENGELUARAN
    // ======================
    const pp = persentase.persentase_pemasukan_pengeluaran;

    const ppValue = document.getElementById('persentase-pemasukan-pengeluaran-value');
    const ppPeriod = document.getElementById('persentase-pemasukan-pengeluaran-period');

    // Tampilkan value (+12%)
    ppValue.textContent = (pp.is_positive ? '+' : '-') + pp.value + '%';

    // Tampilkan period (Minggu Ini +12%)
    ppPeriod.textContent = `${pp.period} ${(pp.is_positive ? '+' : '-') + pp.value + '%'}`;

    // Ubah warna period saja
    ppPeriod.classList.toggle('text-green-600', pp.is_positive);
    ppPeriod.classList.toggle('text-red-600', !pp.is_positive);


    // ======================
    // DISTRIBUSI PRODUK
    // ======================
    const dp = persentase.persentase_distribusi_produk;

    const dpValue = document.getElementById('persentase-distribusi-produk-value');
    const dpPeriod = document.getElementById('persentase-distribusi-produk-period');

    // Tampilkan value (+15%)
    dpValue.textContent = (dp.is_positive ? '+' : '-') + dp.value + '%';

    // Tampilkan period (Bulan Ini +15%)
    dpPeriod.textContent = `${dp.period} ${(dp.is_positive ? '+' : '-') + dp.value + '%'}`;

    // Warna period saja
    dpPeriod.classList.toggle('text-green-600', dp.is_positive);
    dpPeriod.classList.toggle('text-red-600', !dp.is_positive);
}


function tampilkanChartPemasukanPengeluaran(chart) {
    // --- Chart.js ---
    const ctx = document.getElementById('weeklyChart').getContext('2d');

    // Membuat gradient fill
    const gradientPemasukan = ctx.createLinearGradient(0, 0, 0, 150);
    gradientPemasukan.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradientPemasukan.addColorStop(1, 'rgba(99, 102, 241, 0)');

    const gradientPengeluaran = ctx.createLinearGradient(0, 0, 0, 150);
    gradientPengeluaran.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
    gradientPengeluaran.addColorStop(1, 'rgba(239, 68, 68, 0)');

    const labels = chart.map(item => item.hari); // Sumbu X (Tanggal)
    const dataPemasukan = chart.map(item => item.total_pemasukan); // Data untuk garis pemasukan
    const dataPengeluaran = chart.map(item => item.total_pengeluaran); // Data untuk garis pengeluaran

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pemasukan',
                data: dataPemasukan,
                borderColor: '#6366F1', // Indigo-500
                backgroundColor: gradientPemasukan,
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#6366F1',
                pointRadius: 0,
                pointHoverRadius: 5,
            }, {
                label: 'Pengeluaran',
                data: dataPengeluaran,
                borderColor: '#EF4444', // Red-500
                backgroundColor: gradientPengeluaran,
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#EF4444',
                pointRadius: 0,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        maxTicksLimit: 10 ,
                        color: '#9CA3AF'
                    },
                    grid: {
                        drawBorder: false,
                        color: '#E5E7EB',
                    }
                },
                x: {
                    ticks: {
                        color: '#9CA3AF'
                    },
                    grid: {
                        display: false,
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 20,
                        padding: 20,
                        color: '#374151',
                    }
                },
                tooltip: {
                    backgroundColor: '#1F2937',
                    titleFont: { size: 14 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    boxPadding: 4
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
}

function tampilkanChartDistribusiProduk(chart_distribusi) {
    const ctx = document.getElementById('productDistributionChart');

    const labels = chart_distribusi.map(item => item.kategori);
    const data = chart_distribusi.map(item => item.total);
    // Using Tailwind's default color palette for a similar look
    const colors = ['#6366F1', '#10B981', '#F59E0B', '#EF4444']

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Produk',
                data: data,
                backgroundColor: colors,
                borderRadius: 8, // To match the rounded-lg in the original design
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'x', // Vertical bars
            plugins: {
                legend: {
                    display: false // Hide the legend
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // Hide x-axis grid lines
                    },
                    border: {
                        display: false // Hide x-axis border line
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false // Hide y-axis grid lines
                    },
                    ticks: {
                        display: false // Hide y-axis labels
                    },
                    border: {
                        display: false // Hide y-axis border line
                    }
                }
            }
        }
    });
}

