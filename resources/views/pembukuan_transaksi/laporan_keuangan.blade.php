<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 20mm;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            line-height: 1.4;
            background: #ffffff;
        }

        .document-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 28px;
            background: white;
        }

        /* Header Perusahaan */
        .company-header {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .company-info {
            display: table-cell;
            vertical-align: top;
            text-align: center;
        }

        .company-logo {
            display: table-cell;
            vertical-align: top;
            width: 80px;
            /* Cukup berikan satu padding-right untuk jarak logo ke teks */
            padding-right: 15px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #000;
            /* Cukup gunakan margin-bottom untuk memberi jarak ke alamat di bawahnya */
            margin-bottom: 10px;
            padding-right: 80px;
        }

        .company-details {
            font-size: 9pt;
            color: #4a4a4a;
            line-height: 1.5;
            padding-right: 80px;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin: 30px 0 25px 0;
        }

        .report-title h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .report-period {
            font-size: 10pt;
            color: #4a4a4a;
        }

        /* Informasi Ringkasan */
        .summary-box {
            background: #f8f9fa;
            border: 1px solid #d1d5db;
            padding: 20px;
            margin-bottom: 25px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 0 15px;
            border-right: 1px solid #d1d5db;
        }

        .summary-item:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 9pt;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 16pt;
            font-weight: bold;
            color: #000;
        }

        .summary-value.positive {
            color: #059669;
        }

        .summary-value.negative {
            color: #dc2626;
        }

        /* Tabel Transaksi */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10pt;
        }

        .transaction-table thead {
            background-color: #1f2937;
            color: #ffffff;
        }

        .transaction-table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #000000;
        }

        .transaction-table th.text-right {
            text-align: right;
        }

        .transaction-table th.text-center {
            text-align: center;
        }

        .transaction-table td {
            padding: 10px;
            border: 1px solid #000000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-radius: 3px;
        }

        .type-badge.income {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .type-badge.expense {
            background-color: #fee2e2;
            color: #91b1b;
            border: 1px solid #fca5a5;
        }

        .amount-debit {
            color: #059669;
            font-weight: 600;
        }

        .amount-credit {
            color: #dc2626;
            font-weight: 600;
        }

        /* Total Row */
        .total-row {
            background-color: #1f2937 !important;
            color: #ffffff;
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .total-row td {
            padding: 12px 10px;
            font-size: 11pt;
            border: 1px solid #000000;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }

        .signature-title {
            font-size: 10pt;
            font-weight: 600;
            margin-bottom: 60px;
        }

        .signature-line {
            border-top: 1px solid #0000;
            display: inline-block;
            width: 200px;
            margin-bottom: 5px;
        }

        .signature-name {
            font-size: 10pt;
            font-weight: 600;
        }

        .signature-position {
            font-size: 9pt;
            color: #6b7280;
        }

        .document-info {
            font-size: 8pt;
            color: #9ca3af;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="document-container">
        <div class="company-header">
            <div class="company-logo">
                <img src="{{ public_path('assets/images/logo.jpg') }}" alt="Logo Koperasi" style="max-width: 60px;">
            </div>
            <div class="company-info">
                <div class="company-name">
                    Koperasi Siswa SMKN 9 Malang
                </div>
                <div class="company-details">
                    Jl. Sampurna No.1, Cemorokandang, Kec. Kedungkandang, Kota Malang, Jawa Timur
                    65138<br>
                    Telp: 0341727998 | Email: humas@smkn9malang.sch.id<br>
                </div>
            </div>
        </div>

        <div class="report-title">
            <h1>Laporan Keuangan</h1>
            <div class="report-period">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Pemasukan</div>
                    <div class="summary-value positive">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Pengeluaran</div>
                    <div class="summary-value negative">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Saldo Bersih</div>
                    <div class="summary-value {{ $saldo >= 0 ? 'positive' : 'negative' }}">Rp
                        {{ number_format($saldo, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <table class="transaction-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 15%;">Nofaktur</th>
                    <th style="width: 40%;">Keterangan</th>
                    <th style="width: 20%;" class="text-right">Pemasukan</th>
                    <th style="width: 20%;" class="text-right">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $transaction)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}
                    </td>
                    {{-- PERBAIKAN: Menambahkan kolom untuk Nofaktur --}}
                    <td>{{ $transaction->no_faktur ?? '-' }}</td> 
                    
                    <td>{{ $transaction->keterangan }}</td>

                    {{-- Ini adalah logika untuk memisah kolom --}}
                    @if ($transaction->tipe === 'pemasukan')
                    <td class="text-right amount-debit">
                        {{ number_format($transaction->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="text-right">-</td>
                    @else
                    <td class="text-right">-</td>
                    <td class="text-right amount-credit">
                        {{ number_format($transaction->jumlah, 0, ',', '.') }}
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    {{-- PERBAIKAN: Colspan sekarang 6 karena ada 6 kolom --}}
                    <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                </tr>
                @endforelse

                {{-- Baris Total (diperbaiki) --}}
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="5" class="text-right">SALDO BERSIH</td>
                    {{-- PERBAIKAN: Kolom Saldo Bersih menggunakan colspan 5 dan menempati kolom Pengeluaran --}}
                    <td class="text-right">{{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="report-footer">
            {{-- <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-title">Mengetahui</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Kepala Sekolah</div>
                    <div class="signature-position">SMKN 9 Malang</div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">Pembukuan</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Admin Keuangan</div>
                    <div class="signature-position">Koperasi Siswa</div>
                </div>
            </div> --}}
            <div class="document-info">
                Dokumen ini dicetak secara otomatis pada {{ now('Asia/jakarta')->format('d M Y H:i:s') }} WIB<br>
            </div>
        </div>
    </div>
</body>

</html>