@extends('layouts.main')

@section('title', 'KopsisApp - Tambah Pembukuan Transaksi')

@section('content')
    <style>
        .judul { padding: 4% 6% 5%; font-size: 25px; text-align: center; }
        .block { margin: 0 6%; }
        .form-section { margin-bottom: 24px; border-radius: 8px; padding: 0; }
        .form-row { display: flex; gap: 16px; margin-bottom: 24px; }
        .form-column { flex: 1; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; font-size: 14px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 6px;
            font-size: 16px; transition: border-color 0.3s; background-color: #fafafa;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: #4a90e2; background-color: white;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }
        .form-textarea { resize: vertical; min-height: 80px; width: 100%; }
        .button-container { display: flex; justify-content: center; align-items: center; gap: 12px; margin-top: 24px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; font-size: 15px; cursor: pointer;
               transition: background-color 0.3s; align-items: center; min-width: 10px;
               display: inline-flex; align-items: center; justify-content: center; height: auto; min-height: 20px; }
        .btn-cancel { background-color: #D20D24; color: white; text-decoration: none; }
        .btn-cancel:hover { background-color: #b00a1f; }
        .btn-save { background-color: #22C55E; color: white; }
        .btn-save:hover { background-color: #1ea34e; }
        .btn-save-again { background-color: #4a90e2; color: white; }
        .btn-save-again:hover { background-color: #3a7bc8; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .judul { padding: 8% 5% 6%; }
            .block { margin: 0 4%; }
            .form-row { flex-direction: column; gap: 0; margin-bottom: 0; }
            .form-column { margin-bottom: 16px; }
            .button-container { flex-direction: column; }
            .btn { width: 100%; max-width: 300px; }
        }
    </style>

    <div class="judul">
        <h2 class="font-bold">Tambah Pembukuan Transaksi</h2>
    </div>

    {{-- Kontainer utama --}}
    <div class="block">
        {{-- Form untuk menambah transaksi --}}
        <form action="{{ route('pembukuan_transaksi.store') }}" method="POST">
            {{-- csrf token biar secure --}}
            @csrf

            <div class="form-section">
                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="tipe_transaksi">Tipe Transaksi <span style="color: #D20D24;">*</span></label>
                        <select id="tipe_transaksi" name="tipe" class="form-select" required>
                            <option value="">Pilih Tipe Transaksi</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="jumlah">Jumlah <span style="color: #D20D24;">*</span></label>
                        <input type="number" id="jumlah" name="jumlah" class="form-input" placeholder="Masukkan jumlah..." required>
                    </div>
                </div>

                {{-- Tanggal dan Keterangan --}}
                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="tanggal">Tanggal <span style="color: #D20D24;">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="form-input" required>
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3" placeholder="Tambahkan keterangan (opsional)..."></textarea>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" name="save_and_create" value="1" class="btn btn-save-again">Simpan Data Dan Buat Lagi</button>
                <button type="submit" class="btn btn-save">Simpan</button>
                <a href="{{ route('pembukuan_transaksi.index') }}" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
@endsection
