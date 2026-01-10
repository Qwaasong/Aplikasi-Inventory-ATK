@extends('layouts/main')
@section('title', 'KopsisApp - Vendor')
@section('content')

    <style>
        .judul {
            padding: 4% 6% 5%;
            font-size: 25px;
            text-align: center;
        }

        .block {
            margin: 0 6%;
        }

        .form-section {
            margin-bottom: 24px;
            border-radius: 8px;
            padding: 0;
        }

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-column {
            flex: 1;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            background-color: #fafafa;
        }

        .form-input::placeholder {
            color: #999;
            font-style: italic;
        }

        .form-input:focus {
            outline: none;
            border-color: #4a90e2;
            background-color: white;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }

        textarea.form-input {
            resize: vertical;
            min-height: 80px;
            width: 100%;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            align-items: center;
            min-width: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: auto;
            min-height: 20px;
        }

        .btn-cancel {
            background-color: #D20D24;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #b00a1f;
        }

        .btn-save {
            background-color: #22C55E;
            color: white;
        }

        .btn-save:hover {
            background-color: #1ea34e;
        }

        .btn-save-again {
            background-color: #4a90e2;
            color: white;
        }

        .btn-save-again:hover {
            background-color: #3a7bc8;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .judul {
                padding: 8% 5% 6%;
            }

            .block {
                margin: 0 4%;
            }

            .form-row-top,
            .form-row-bottom {
                flex-direction: column;
                gap: 0;
                margin-bottom: 0;
            }

            .form-column {
                margin-bottom: 16px;
            }

            .form-input,
            .form-select {
                font-size: 16px;
                padding: 14px 12px;
            }

            .form-select {
                background-position: right 12px center;
                padding-right: 40px;
            }

            .button-container {
                padding: 12px;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 12px;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                padding: 14px 24px;
                font-size: 16px;
                min-height: 44px;
            }

            textarea.form-input {
                min-height: 100px;
            }
        }

        /* Small Mobile Devices */
        @media (max-width: 480px) {
            .judul {
                padding: 10% 4% 8%;
            }

            .block {
                margin: 0 2%;
            }

            .form-input,
            .form-select {
                padding: 16px 12px;
            }

            .btn {
                padding: 16px 24px;
                font-size: 17px;
            }

            .judul h2 {
                font-size: 1.5rem;
                text-align: center;
            }
        }

        /* Very Small Devices */
        @media (max-width: 320px) {
            .judul {
                padding: 12% 3% 10%;
            }

            .block {
                margin: 0 1%;
            }

            .btn {
                padding: 18px 16px;
                font-size: 16px;
            }
        }
    </style>

<div class="judul">
    <h2 class="font-bold">Tambah Hutang/Piutang</h2>
</div>

<div class="block">
    <!-- PERBAIKAN: Ganti route ke ledger_entries.store -->
    <form action="{{ route('ledger_entries.store') }}" method="POST">
        @csrf
        <div class="form-section">
            <!-- Baris Atas: Nama, Tipe, Nominal -->
            <div class="form-row">
                <div class="form-column">
                    <!-- PERBAIKAN: name="nama" -->
                    <label class="form-label" for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-input" placeholder="Nama..." required>
                </div>

                <div class="form-column">
                    <label class="form-label" for="tipe">Tipe</label>
                    <select name="tipe" class="form-input" required>
                        <option value="">Pilih Tipe</option>
                        <!-- PERBAIKAN: value="hutang" bukan "utang" -->
                        <option value="hutang">Hutang</option>
                        <option value="piutang">Piutang</option>
                    </select>
                </div>

                <div class="form-column">
                    <label class="form-label" for="nominal">Nominal</label>
                    <input type="text" id="nominal" name="nominal" class="form-input" placeholder="Rp. 0" required>
                </div>
            </div>

            <!-- Baris Bawah: Telepon, Tanggal, Jatuh Tempo -->
            <div class="form-row">
                <div class="form-column">
                    <!-- TAMBAHKAN: Field telepon -->
                    <label class="form-label" for="telepon">Telepon</label>
                    <input type="text" id="telepon" name="telepon" class="form-input" placeholder="+62...">
                </div>

                <div class="form-column">
                    <!-- PERBAIKAN: name="tanggal_transaksi" -->
                    <label class="form-label" for="tanggal_transaksi">Tanggal Transaksi</label>
                    <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-column">
                    <!-- PERBAIKAN: name="jatuh_tempo" -->
                    <label class="form-label" for="jatuh_tempo">Jatuh Tempo</label>
                    <input type="date" id="jatuh_tempo" name="jatuh_tempo" class="form-input">
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-row">
                <div class="form-column">
                    <label class="form-label" for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" class="form-input" rows="3" placeholder="Keterangan..." required></textarea>
                </div>
            </div>
        </div>

        <div class="button-container">
            <button type="submit" name="save_and_create" value="1" class="btn btn-save-again">Simpan Data Dan Buat Lagi</button>
            <button type="submit" class="btn btn-save">Simpan</button>
            <!-- PERBAIKAN: Tombol batal redirect ke index -->
            <a href="{{ route('ledger_entries.index') }}" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>
    <script src="{{ asset('assets/js/fab.js') }}"></script>
@endsection

@section('script')

    <script>

        var nominalInput = document.getElementById('nominal');
        
        // 2. Ambil elemen form
        var form = nominalInput.closest('form'); 

        // 3. EVENT LISTENER: Format Rupiah saat pengguna mengetik
        nominalInput.addEventListener('keyup', function(e) {
            nominalInput.value = formatRupiah(this.value, 'Rp. ');
        });

        // 4. 🔥 EVENT LISTENER KRUSIAL: Membersihkan nilai sebelum dikirim ke backend
        form.addEventListener('submit', function() {
            var formattedValue = nominalInput.value;
            
            // Hapus semua karakter non-angka (Rp, titik, spasi)
            // Hanya menyisakan angka murni. Contoh: "Rp. 10.000" menjadi "10000"
            var cleanValue = formattedValue.replace(/[^0-9]/g, ''); 
            
            // Set nilai input ke angka murni sebelum form submit
            nominalInput.value = cleanValue;
            
            // Catatan: Setelah form dikirim dan halaman direfresh/redirect,
            // nilai Rupiah akan muncul lagi karena event listener keyup di atas.
        });


        /* Fungsi untuk Format Rupiah */
        function formatRupiah(angka, prefix) {
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
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
    <script src="{{ asset('assets/js/fab.js') }}"></script>
@endsection
