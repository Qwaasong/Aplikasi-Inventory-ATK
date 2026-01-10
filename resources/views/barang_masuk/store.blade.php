@extends('layouts.main')
@section('title', 'KopsisApp - Tambah Barang Masuk')

@php
    $vendors = \App\Models\Vendor::all();
    $products = \App\Models\Product::all(); 
@endphp

@section('content')
    <style>
        /* Reset dan variabel warna */
        :root {
            --primary: #4a90e2;
            --primary-dark: #357ABD;
            --success: #28a745;
            --success-dark: #1e7e34;
            --danger: #dc3545;
            --danger-dark: #c82333;
            --gray-light: #f8f9fa;
            --gray-medium: #e9ecef;
            --gray-dark: #6c757d;
            --text-dark: #333;
            --text-light: #6c757d;
            --border-radius: 8px;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }
        
        .judul { 
            padding: 3% 6% 2%; 
            font-size: 24px; 
            text-align: center; 
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 1px solid var(--gray-medium);
            margin-bottom: 24px;
        }
        
        .block { 
            margin: 0 6%; 
            padding-bottom: 40px;
        }
        
        .form-section { 
            margin-bottom: 32px; 
            border-radius: var(--border-radius); 
            padding: 24px; 
            background-color: white;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-medium);
        }
        
        .form-section h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            padding-bottom: 12px;
            border-bottom: 1px solid var(--gray-medium);
        }
        
        .form-row { 
            display: flex; 
            gap: 20px; 
            margin-bottom: 20px; 
        }
        
        .form-column { 
            flex: 1; 
        }
        
        .form-label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 500; 
            color: var(--text-dark); 
            font-size: 14px; 
        }
        
        .form-input, .form-select, .form-textarea { 
            width: 100%; 
            padding: 12px 16px; 
            border: 1px solid var(--gray-medium); 
            border-radius: 6px; 
            font-size: 15px; 
            transition: var(--transition); 
            background-color: white; 
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus { 
            outline: none; 
            border-color: var(--primary); 
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15); 
        }
        
        .item-section { 
            background-color: white; 
            padding: 24px; 
            border: 1px solid var(--gray-medium); 
            border-radius: var(--border-radius); 
            margin-bottom: 24px;
            box-shadow: var(--shadow);
        }
        
        .item-section h3 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .item-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 16px; 
        }
        
        .item-table th, .item-table td { 
            padding: 14px 12px; 
            text-align: left; 
            border-bottom: 1px solid var(--gray-medium); 
        }
        
        .item-table th { 
            background-color: var(--gray-light); 
            font-weight: 600; 
            font-size: 14px; 
            color: var(--text-dark);
        }
        
        .item-table td .form-input, .item-table td .form-select {
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .btn-add-item, .btn-remove-item {
            padding: 10px 16px; 
            font-size: 14px; 
            cursor: pointer; 
            border-radius: 6px;
            transition: var(--transition);
            font-weight: 500;
            border: none;
        }
        
        .btn-add-item {
            background-color: var(--success); 
            color: white; 
        }
        
        .btn-add-item:hover {
            background-color: var(--success-dark);
            transform: translateY(-1px);
        }
        
        .btn-remove-item {
            background-color: var(--danger); 
            color: white; 
            padding: 8px 12px;
        }
        
        .btn-remove-item:hover {
            background-color: var(--danger-dark);
        }
        
        .total-row td { 
            border-top: 2px solid var(--gray-dark); 
            font-weight: bold; 
            font-size: 16px;
            padding-top: 16px;
        }
        
        .button-container { 
            display: flex; 
            justify-content: flex-end; 
            gap: 12px; 
            margin-top: 32px; 
            padding-bottom: 4%; 
        }
        
        .btn { 
            padding: 12px 24px; 
            border: none; 
            border-radius: 6px; 
            font-size: 15px; 
            font-weight: 500; 
            cursor: pointer; 
            transition: var(--transition);
        }
        
        .btn-save { 
            background-color: var(--primary); 
            color: white; 
        }
        
        .btn-save:hover { 
            background-color: var(--primary-dark); 
            transform: translateY(-1px);
        }
        
        .btn-save-again { 
            background-color: var(--success); 
            color: white; 
        }
        
        .btn-save-again:hover { 
            background-color: var(--success-dark); 
            transform: translateY(-1px);
        }
        
        .btn-cancel { 
            background-color: var(--gray-dark); 
            color: white; 
            text-decoration: none; 
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-cancel:hover { 
            background-color: #5a6268; 
            color: white;
        }
        
        .error-message { 
            color: var(--danger); 
            margin-top: 6px; 
            font-size: 13px; 
        }
        
        .required-star {
            color: var(--danger);
        }
        
        /* Responsif */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 16px;
            }
            
            .button-container {
                flex-direction: column-reverse;
            }
            
            .btn {
                width: 100%;
            }
            
            .judul, .block {
                margin-left: 4%;
                margin-right: 4%;
            }
            
            .item-section h3 {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .btn-add-item {
                align-self: flex-end;
            }
        }
    </style>

    <div class="judul">Form Tambah Barang Masuk</div>
    
    @if ($errors->any())
        <div style="margin: 0 6%; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 16px; border-radius: 6px; margin-bottom: 24px;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="block">
        <form id="purchase-form" action="{{ route('barang_masuk.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3>Header Barang Masuk</h3>
                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="vendor_id">
                            Vendor <span class="required-star">*</span>
                        </label>
                        <select id="vendor_id" name="vendor_id" class="form-select" required>
                            <option value="">Pilih Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="no_faktur">No Faktur</label>
                        <input type="text" id="no_faktur" name="no_faktur" class="form-input" 
                                placeholder="Masukkan nomor faktur..." value="{{ old('no_faktur') }}">
                        @error('no_faktur')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="tanggal">
                            Tanggal <span class="required-star">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" class="form-input" required 
                                value="{{ old('tanggal') ?? date('Y-m-d') }}">
                        @error('tanggal')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3" 
                                    placeholder="Tambahkan keterangan (opsional)...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="item-section">
                <h3>
                    Detail Barang Masuk 
                    <button type="button" id="add-item-btn" class="btn-add-item">
                        Tambah Item
                    </button>
                </h3>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="width: 15%;">Jml. Pack</th>
                            <th style="width: 20%;">Harga Beli/Pack</th>
                            <th style="width: 20%;">Harga Jual/Pack</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="item-list">
                        </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2" style="text-align: right; padding-right: 20px;">Total Pengeluaran:</td>
                            <td colspan="3" id="total-pengeluaran">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
                @error('items')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="button-container">
                <button type="submit" name="save_and_create" value="1" class="btn btn-save-again">
                    Simpan Data Dan Buat Lagi
                </button>
                <button type="submit" class="btn btn-save">
                    Simpan
                </button>
                <a href="{{ route('barang_masuk.index') }}" class="btn btn-cancel">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const itemList = document.getElementById('item-list');
        const addItemBtn = document.getElementById('add-item-btn');
        const form = document.getElementById('purchase-form'); 
        let itemIndex = 0;

        /* ---------------------------------------------------------------------- */
        /* FUNGSI FORMAT RUPIAH (untuk tampilan label Total)                      */
        /* ---------------------------------------------------------------------- */
        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';
            let number_string = angka.toString().replace(/[^0-9]/g, '');
            if (number_string === '') return 'Rp 0';
            
            let reverse = number_string.split('').reverse().join('');
            let ribuan = reverse.match(/\d{1,3}/g);
            let result = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + result;
        }

        /* ---------------------------------------------------------------------- */
        /* FUNGSI FORMAT RUPIAH UNTUK INPUT (untuk field Harga Beli/Jual)         */
        /* ---------------------------------------------------------------------- */
        function formatRupiahInput(angka) {
            // Hilangkan semua non-digit dan titik koma
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
            // Gunakan prefix 'Rp. ' hanya untuk input
            return rupiah ? 'Rp. ' + rupiah : '';
        }

        // Fungsi untuk membersihkan nilai Rupiah menjadi angka murni
        function cleanRupiah(rupiah) {
            // Hapus 'Rp. ', koma, dan semua titik (separator ribuan)
            return rupiah.replace(/[^0-9]/g, '');
        }

        // Template HTML untuk satu baris item
        function createItemRow(index) {
            const row = document.createElement('tr');
            row.id = `row-${index}`;
            row.innerHTML = `
                <td>
                    <select name="items[${index}][product_id]" class="form-select item-product" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option 
                                value="{{ $product->id }}" 
                                data-harga-beli="{{ $product->harga_beli }}" 
                                data-harga-jual="{{ $product->harga_jual }}"
                            >
                                {{ $product->nama }} (Stok: {{ $product->stok }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][jumlah_pack]" class="form-input item-qty" 
                            min="1" value="1" required>
                </td>
                <td>
                    <input type="text" name="items[${index}][harga_beli]" class="form-input item-beli rupiah-input" 
                            value="" required data-numeric-value="0">
                </td>
                <td>
                    <input type="text" name="items[${index}][harga_jual]" class="form-input item-jual rupiah-input" 
                            value="" required data-numeric-value="0">
                </td>
                <td>
                    <button type="button" class="btn-remove-item" data-index="${index}" title="Hapus item">Hapus</button>
                </td>
            `;
            
            // Setelah baris dibuat, terapkan event listeners ke input Rupiah
            const beliInput = row.querySelector('.item-beli');
            const jualInput = row.querySelector('.item-jual');
            
            // Terapkan event listener untuk format otomatis saat ketik pada Harga Beli
            beliInput.addEventListener('keyup', function() {
                this.value = formatRupiahInput(this.value);
                calculateTotal(); // Hitung total ulang setelah format
            });
            
            // Terapkan event listener untuk format otomatis saat ketik pada Harga Jual
            jualInput.addEventListener('keyup', function() {
                this.value = formatRupiahInput(this.value);
            });
            
            // Format nilai default jika ada
            if (beliInput.value) {
                beliInput.value = formatRupiahInput(beliInput.value);
            }
            if (jualInput.value) {
                jualInput.value = formatRupiahInput(jualInput.value);
            }
            
            return row;
        }

        // Fungsi untuk menghitung total
        function calculateTotal() {
            let total = 0;
            itemList.querySelectorAll('tr').forEach(row => {
                const qtyInput = row.querySelector('.item-qty');
                const beliInput = row.querySelector('.item-beli'); 
                
                const qty = parseInt(qtyInput ? qtyInput.value : 0);
                
                // Gunakan fungsi cleanRupiah untuk mendapatkan nilai angka murni
                const beliRupiah = beliInput ? beliInput.value : '0';
                const beli = parseInt(cleanRupiah(beliRupiah)) || 0;
                
                total += qty * beli;
            });

            document.getElementById('total-pengeluaran').textContent = formatRupiah(total);
        }

        // Handler untuk menambah item
        addItemBtn.addEventListener('click', () => {
            const newRow = createItemRow(itemIndex);
            itemList.appendChild(newRow);
            itemIndex++;
            calculateTotal();
        });

        // Handler untuk menghapus item
        itemList.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-remove-item')) {
                const index = e.target.getAttribute('data-index');
                const row = document.getElementById(`row-${index}`);
                if (row) {
                    row.remove();
                    calculateTotal();
                }
            }
        });

        // Handler untuk perubahan di kolom Produk, Qty
        itemList.addEventListener('change', (e) => {
            const target = e.target;
            if (target.classList.contains('item-product')) {
                const selectedOption = target.options[target.selectedIndex];
                const hargaBeli = selectedOption.getAttribute('data-harga-beli');
                const hargaJual = selectedOption.getAttribute('data-harga-jual');
                
                const row = target.closest('tr');
                if (row) {
                    // Format harga beli dan jual yang diambil dari data atribut
                    row.querySelector('.item-beli').value = formatRupiahInput(hargaBeli || '0');
                    row.querySelector('.item-jual').value = formatRupiahInput(hargaJual || '0');
                }
                calculateTotal();
            } else if (target.classList.contains('item-qty')) {
                calculateTotal();
            }
        });
        
        // Handle input event untuk Qty dan Harga Beli (untuk update total saat ketik)
        itemList.addEventListener('input', (e) => {
            const target = e.target;
            if (target.classList.contains('item-qty') || target.classList.contains('item-beli')) {
                calculateTotal();
            }
        });
        
        /* ---------------------------------------------------------------------- */
        /* LOGIKA KRUSIAL: Membersihkan nilai sebelum form submit                 */
        /* ---------------------------------------------------------------------- */
        form.addEventListener('submit', function() {
            // Iterasi semua input yang memiliki class 'rupiah-input' (Harga Beli & Harga Jual)
            document.querySelectorAll('.rupiah-input').forEach(input => {
                // Set nilai input ke angka murni sebelum form submit
                input.value = cleanRupiah(input.value);
            });
            // Nilai yang dikirim ke backend sekarang adalah angka murni
        });


        // menambahkan satu baris item secara default saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            if (itemList.children.length === 0) {
                addItemBtn.click();
            }
            // perhitungan total saat halaman dimuat
            calculateTotal();
        });
    </script>
    
@endsection