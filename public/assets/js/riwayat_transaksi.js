function formatRupiah(angka) {
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
    document.getElementById('stat-total-produk').textContent = statistik.total_produk;
    document.getElementById('stat-keuntungan').textContent = formatRupiah(statistik.keuntungan);
}

function fetchStatistikData() {
    $.ajax({
        url: '/api/beranda',
        method: 'GET',
        success: function (response) {
            if (response.success && response.data.statistik) {
                tampilkanStatistik(response.data.statistik);
            } else {
                console.error('Error:', response.message);
                setDefaultValues();
            }
        },
        error: function (error) {
            console.error('Ajax error:', error);
            setDefaultValues();
        }
    });
}

function setDefaultValues() {
    document.getElementById('stat-saldo').textContent = '0';
    document.getElementById('stat-pemasukan').textContent = '0';
    document.getElementById('stat-pengeluaran').textContent = '0';
    document.getElementById('stat-total-produk').textContent = '0';
    document.getElementById('stat-saldo').textContent = '0';
}

document.addEventListener('DOMContentLoaded', function() {
    fetchStatistikData();
});