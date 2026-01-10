@extends('layouts/main')
@section('title', 'KopsisApp - Edit Penghutang')
@section('content')
    <style>
        .container {
            padding: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Bagian atas: total utang dan tombol lunaskan */
        .header-utang {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .total-text {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #666;
        }

        .saldo-utang {
            font-size: 24px;
            font-weight: bold;
            color: #d32f2f;
            margin: 0;
        }

        .btn-lunaskan {
            background-color: #fbb300;
            border: none;
            color: #000;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .btn-lunaskan:hover {
            background-color: #e0a800;
        }

        /* Atur tanggal jatuh tempo */
        .jatuh-tempo {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .jatuh-tempo:hover {
            background-color: #f9f9f9;
        }

        .icon-calendar {
            display: inline-block;
            margin-right: 8px;
            font-size: 18px;
        }

        .arrow-right {
            border: solid #888;
            border-width: 0 2px 2px 0;
            display: inline-block;
            padding: 3px;
            transform: rotate(-45deg);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-weight: 600;
            font-size: 16px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 15px;
            text-align: center;
        }

        .form-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-confirm {
            flex: 1;
            padding: 10px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-cancel-modal {
            flex: 1;
            padding: 10px;
            background: #999;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        /* Tabel utang */
        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        th,
        td {
            padding: 12px 8px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
        }

        td:nth-child(2) {
            text-align: center;
            background-color: #e8f5e8;
            color: #2e7d32;
            font-weight: bold;
        }

        td:nth-child(3) {
            text-align: right;
            color: #d32f2f;
            font-weight: bold;
        }

        .subtext {
            display: block;
            font-size: 11px;
            color: #999;
            margin-top: 2px;
            font-weight: normal;
        }

        .info-icon {
            font-size: 12px;
            vertical-align: middle;
            color: #2196f3;
            margin-left: 4px;
            cursor: help;
        }

        /* Tombol bawah */
        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .button-group button {
            flex: 1;
            padding: 14px 0;
            border: none;
            font-weight: 600;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-berikan {
            background-color: #d32f2f;
        }

        .btn-berikan:hover {
            background-color: #b22a2a;
        }

        .btn-terima {
            background-color: #2e7d32;
        }

        .btn-terima:hover {
            background-color: #1b5e20;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        .status-lunas {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .status-belum-lunas {
            background-color: #ffebee;
            color: #d32f2f;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }

            .header-utang {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn-lunaskan {
                align-self: stretch;
                text-align: center;
            }

            .button-group {
                flex-direction: column;
            }

            th,
            td {
                padding: 10px 6px;
                font-size: 13px;
            }

            .modal-content {
                width: 90%;
                margin: 20px;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 5px;
                padding: 10px;
            }

            .saldo-utang {
                font-size: 20px;
            }

            .button-group button {
                padding: 12px 0;
                font-size: 14px;
            }
        }
    </style>

<div class="container">
    <!-- Header utang - DINAMIS -->
    <div class="header-utang">
        <div>
            <p class="total-text">Total {{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }} {{ $entry->nama }}</p>
            <p class="saldo-utang">Rp {{ number_format($entry->nominal, 0, ',', '.') }}</p>
            <span class="status-badge status-belum-lunas">BELUM LUNAS</span>
        </div>
        <button class="btn-lunaskan" onclick="bukaModalLunaskan()">Lunaskan</button>
    </div>

    <!-- Informasi jatuh tempo - DINAMIS -->
    <div class="jatuh-tempo" onclick="bukaModalJatuhTempo()">
        <span class="icon-calendar">📅</span>
        <span id="textJatuhTempo">
            @if($entry->jatuh_tempo)
                Jatuh Tempo: {{ $entry->jatuh_tempo->format('d M Y') }}
            @else
                Atur Tanggal Jatuh Tempo
            @endif
        </span>
        <span class="arrow-right"></span>
    </div>

    <!-- Modal Jatuh Tempo -->
    <div id="modalJatuhTempo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Pilih Tanggal Jatuh Tempo</div>
                <button class="close-modal" onclick="tutupModal('modalJatuhTempo')">&times;</button>
            </div>
            <input type="date" id="datePicker" class="form-input" 
                   value="{{ $entry->jatuh_tempo ? $entry->jatuh_tempo->format('Y-m-d') : '' }}">
            <div class="modal-buttons">
                <button class="btn-cancel-modal" onclick="tutupModal('modalJatuhTempo')">Batal</button>
                <button class="btn-confirm" onclick="simpanJatuhTempo({{ $entry->id }})">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Utang -->
    <div id="modalTambahUtang" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Tambah {{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }}</div>
                <button class="close-modal" onclick="tutupModal('modalTambahUtang')">&times;</button>
            </div>
            <input type="text" id="inputTambahUtang" class="form-input" placeholder="Masukkan nominal"
                onkeyup="formatRupiahInput(this)">
            <div class="modal-buttons">
                <button class="btn-cancel-modal" onclick="tutupModal('modalTambahUtang')">Batal</button>
                <button class="btn-confirm" onclick="simpanTambahUtang({{ $entry->id }})">Tambah</button>
            </div>
        </div>
    </div>

    <!-- Modal Bayar Utang -->
    <div id="modalBayarUtang" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Bayar {{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }}</div>
                <button class="close-modal" onclick="tutupModal('modalBayarUtang')">&times;</button>
            </div>
            <input type="text" id="inputBayarUtang" class="form-input" placeholder="Masukkan nominal pembayaran"
                onkeyup="formatRupiahInput(this)">
            <div class="modal-buttons">
                <button class="btn-cancel-modal" onclick="tutupModal('modalBayarUtang')">Batal</button>
                <button class="btn-confirm" onclick="simpanBayarUtang({{ $entry->id }})">Bayar</button>
            </div>
        </div>
    </div>

    <!-- Modal Lunaskan - DINAMIS -->
    <div id="modalLunaskan" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Lunaskan {{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }}</div>
                <button class="close-modal" onclick="tutupModal('modalLunaskan')">&times;</button>
            </div>
            <p style="margin-bottom: 15px; color: #666;">Apakah Anda yakin ingin melunasi seluruh {{ $entry->tipe == 'hutang' ? 'hutang' : 'piutang' }} sebesar:</p>
            <p style="font-size: 18px; font-weight: bold; color: #d32f2f; margin-bottom: 20px;">
                Rp {{ number_format($entry->nominal, 0, ',', '.') }}
            </p>
            <div class="modal-buttons">
                <button class="btn-cancel-modal" onclick="tutupModal('modalLunaskan')">Batal</button>
                <button class="btn-confirm" onclick="lunaskanUtang({{ $entry->id }})">Ya, Lunaskan</button>
            </div>
        </div>
    </div>

    <!-- Tabel pembukuan transaksi - DINAMIS -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="text-align: center">Tanggal</th>
                    <th style="text-align: center">Bayar <span class="info-icon" title="Penerimaan pembayaran">ℹ️</span></th>
                    <th style="text-align: center">{{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }} <span class="info-icon" title="Penambahan utang">ℹ️</span></th>
                </tr>
            </thead>
            <tbody>
                <!-- SEMENTARA: Tampilkan hanya data utama -->
                <tr>
                    <td style="text-align: center">
                        {{ $entry->tanggal_transaksi->format('d M Y') }}
                        <span class="subtext">{{ $entry->tipe }}</span>
                    </td>
                    <td style="text-align: center">----</td>
                    <td style="text-align: center">
                        Rp {{ number_format($entry->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tombol aksi -->
    <div class="button-group">
        <button class="btn-berikan" onclick="bukaModalTambahUtang()">
            Tambah {{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }}
        </button>
        <button class="btn-terima" onclick="bukaModalBayarUtang()">
            Bayar {{ $entry->tipe == 'hutang' ? 'Hutang' : 'Piutang' }}
        </button>
    </div>
</div>

@endsection

@section('script')
    <script>
        let currentJatuhTempo = '{{ $entry->jatuh_tempo ? $entry->jatuh_tempo->format("Y-m-d") : "" }}';
        let entryId = {{ $entry->id }};

        // Fungsi umum untuk buka/tutup modal
        function bukaModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function tutupModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Fungsi khusus untuk masing-masing modal
        function bukaModalJatuhTempo() {
            const datePicker = document.getElementById('datePicker');
            // Jika sudah ada jatuh tempo, gunakan yang ada, jika tidak default +30 hari
            if (!currentJatuhTempo) {
                const defaultDate = new Date();
                defaultDate.setDate(defaultDate.getDate() + 30);
                datePicker.value = defaultDate.toISOString().split('T')[0];
            } else {
                datePicker.value = currentJatuhTempo;
            }
            bukaModal('modalJatuhTempo');
        }

        function bukaModalTambahUtang() {
            document.getElementById('inputTambahUtang').value = '';
            bukaModal('modalTambahUtang');
        }

        function bukaModalBayarUtang() {
            document.getElementById('inputBayarUtang').value = '';
            bukaModal('modalBayarUtang');
        }

        function bukaModalLunaskan() {
            bukaModal('modalLunaskan');
        }

        // Format input rupiah
        function formatRupiahInput(input) {
            input.value = formatRupiah(input.value, 'Rp. ');
        }

        function formatRupiah(angka, prefix) {
            if (!angka) return '';

            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
        }

        // Fungsi simpan untuk masing-masing modal - DENGAN ID
        function simpanJatuhTempo(id) {
            const datePicker = document.getElementById('datePicker');
            const selectedDate = datePicker.value;

            if (!selectedDate) {
                alert('Silakan pilih tanggal jatuh tempo');
                return;
            }

            fetch(`/ledger_entries/${id}/update-jatuh-tempo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        jatuh_tempo: selectedDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const dateObj = new Date(selectedDate);
                        const formattedDate = dateObj.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        document.getElementById('textJatuhTempo').textContent = `Jatuh Tempo: ${formattedDate}`;
                        currentJatuhTempo = selectedDate;
                        alert('Jatuh tempo berhasil diupdate!');
                        tutupModal('modalJatuhTempo');
                    } else {
                        alert('Gagal update jatuh tempo: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat update jatuh tempo');
                });
        }

        function simpanTambahUtang(id) {
    const input = document.getElementById('inputTambahUtang');
    const nominal = input.value.replace(/[^0-9]/g, '');

    if (!nominal || nominal === '0') {
        alert('Silakan masukkan nominal yang valid');
        return;
    }

    fetch(`/ledger_entries/${id}/tambah-utang`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nominal: parseInt(nominal)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Tampilkan pesan dengan total baru
                tutupModal('modalTambahUtang');
                location.reload();
            } else {
                alert('Gagal menambah utang: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambah utang');
        });
}

        function simpanBayarUtang(id) {
    const input = document.getElementById('inputBayarUtang');
    const nominal = input.value.replace(/[^0-9]/g, '');

    if (!nominal || nominal === '0') {
        alert('Silakan masukkan nominal yang valid');
        return;
    }

    fetch(`/ledger_entries/${id}/bayar-utang`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nominal: parseInt(nominal)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Tampilkan pesan dengan sisa nominal
                tutupModal('modalBayarUtang');
                location.reload(); // Refresh untuk update tampilan
            } else {
                alert('Gagal melakukan pembayaran: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat melakukan pembayaran');
        });
}

        function lunaskanUtang(id) {
            fetch(`/ledger_entries/${id}/lunaskan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Utang berhasil dilunasi!');
                        tutupModal('modalLunaskan');
                        window.location.href = '/ledger_entries';
                    } else {
                        alert('Gagal melunasi utang: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat melunasi utang');
                });
        }

        // Tutup modal ketika klik di luar konten
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });
    </script>
@endsection