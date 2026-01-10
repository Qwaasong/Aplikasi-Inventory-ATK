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
            margin-bottom: 16px;
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

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            background-color: #fafafa;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-input::placeholder {
            color: #999;
            font-style: italic;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #4a90e2;
            background-color: white;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }

        /* Styling khusus untuk select element */
        .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
            padding-right: 40px;
            cursor: pointer;
        }

        .form-select option {
            padding: 8px;
            background-color: white;
            color: #333;
        }

        .form-select option:first-child {
            color: #999;
            font-style: italic;
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

        /* Layout khusus untuk form produk */
        .form-row-top {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-row-bottom {
            display: flex;
            gap: 16px;
        }

        .vendor-column {
            flex: 1;
        }

        .satuan-column {
            flex: 1;
        }

        .kategori-column {
            flex: 1;
        }

        .isi-column {
            flex: 1;
        }

        /* Mobile Responsive Styles */
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

            .form-column,
            .vendor-column,
            .satuan-column,
            .kategori-column,
            .isi-column {
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

            /* PERBAIKAN DI SINI: */
            .button-container {
                padding: 12px;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                /* <- TAMBAHKAN INI */
                gap: 12px;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                /* Optional: agar button tidak terlalu lebar */
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
        <h2 class="font-bold">Edit Produk</h2>
    </div>

    <div class="block">
        <form action="{{ route('produk.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
    
            <div class="form-section">
                <div class="form-row-top">
                    <div class="vendor-column">
                        <label class="form-label" for="nama">Nama Produk</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $product->nama) }}"
                            class="form-input" placeholder="Nama Produk..." required>
                    </div>
    
                    <div class="satuan-column">
                        <label class="form-label" for="satuan_pack">Satuan Pack</label>
                        <select id="satuan_pack" name="satuan_pack" class="form-select" required>
                            <option value="">Pilih Satuan Pack...</option>
                            @foreach ($satuanPackOptions as $key => $label)
                                <option value="{{ $key }}" {{ $product->satuan_pack === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
    
                <div class="form-row-bottom">
                    <div class="kategori-column">
                        <label class="form-label" for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="form-select" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach ($kategoriOptions as $key => $label)
                                <option value="{{ $key }}" {{ $product->kategori === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="isi-column">
                        <label class="form-label" for="isi_per_pack">Isi Per-Pack</label>
                        <input type="number" id="isi_per_pack" name="isi_per_pack"
                            value="{{ old('isi_per_pack', $product->isi_per_pack) }}" class="form-input"
                            placeholder="Isi Per-Pack...">
                    </div>
                </div>
            </div>
    
            <div class="button-container">
                <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                <button type="button" class="btn btn-cancel"
                    onclick="window.location.href='{{ route('produk.index') }}'">Batal</button>
            </div>
        </form>
    </div>
    

@endsection

@section('script')
    <script src="{{ asset('assets/js/fab.js') }}"></script>
@endsection
