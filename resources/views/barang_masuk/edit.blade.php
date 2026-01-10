@extends('layouts.main')
@section('title', 'KopsisApp - Edit Barang Masuk')

@php
    // Memastikan variabel $vendors dan $products tersedia
    $vendors = \App\Models\Vendor::all();
    // Diasumsikan model Product tersedia
    $products = \App\Models\Product::all();
    // Diasumsikan $purchase sudah dilewatkan dari controller
@endphp

@section('content')
    {{-- Menggunakan style yang sama dari store.blade.php untuk konsistensi --}}
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
        .button-container { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; transition: background-color 0.3s; min-width: 10px; display: inline-flex; align-items: center; justify-content: center; height: auto; min-height: 20px; }
        .btn-cancel { background-color: #D20D24; color: white; }
        .btn-cancel:hover { background-color: #b00a1f; }
        .btn-save { background-color: #22C55E; color: white; }
        .btn-save:hover { background-color: #1ea34e; }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .judul { padding: 8% 5% 6%; }
            .block { margin: 0 4%; }
            .form-row { flex-direction: column; gap: 0; margin-bottom: 0; }
            .form-column { margin-bottom: 16px; }
            .form-input, .form-select, .form-textarea { font-size: 16px; padding: 14px 12px; }
            .button-container { flex-direction: column; justify-content: center; gap: 12px; }
            .btn { width: 100%; padding: 14px 24px; font-size: 16px; min-height: 44px; }
            textarea.form-textarea { min-height: 100px; }
        }
    </style>

    <div class="judul">
        <h2 class="font-bold">Edit Barang Masuk</h2>
    </div>

    <div class="block">
        <form action="{{ route('barang_masuk.update', ['id' => $purchase->id]) }}" method="POST">
            @csrf
            @method('PUT') {{-- Metode wajib untuk operasi Update --}}

            <div class="form-section">
                {{-- Bagian Header --}}
                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="vendor_id">Vendor <span style="color: #D20D24;">*</span></label>
                        <select id="vendor_id" name="vendor_id" class="form-select" required>
                            <option value="">Pilih Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ $purchase->vendor_id == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="no_faktur">No Faktur</label>
                        <input type="text" id="no_faktur" name="no_faktur" class="form-input" 
                               value="{{ $purchase->no_faktur }}" placeholder="Masukkan nomor faktur...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="tanggal">Tanggal <span style="color: #D20D24;">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="form-input" 
                            value="{{ $purchase->tanggal }}" required>
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3" 
                                placeholder="Tambahkan keterangan (opsional)...">{{ $purchase->keterangan }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Bagian Item Barang Masuk (Mirip dengan store.blade.php) --}}
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Detail Barang</h3>
            <div class="overflow-x-auto shadow-md rounded-lg mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Produk <span style="color: #D20D24;">*</span></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Qty <span style="color: #D20D24;">*</span></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Harga Beli <span style="color: #D20D24;">*</span></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Harga Jual</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Subtotal</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="item-list" class="bg-white divide-y divide-gray-200">
                        {{-- Loop untuk Item yang Sudah Ada --}}
                        @if (isset($purchase->items) && $purchase->items->count() > 0)
                            @foreach ($purchase->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="items[{{ $loop->index }}][product_id]" class="form-select item-product" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $product)
                                                <option 
                                                    value="{{ $product->id }}" 
                                                    data-harga-beli="{{ $product->harga_beli }}" 
                                                    data-harga-jual="{{ $product->harga_jual }}"
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}
                                                >
                                                    {{ $product->nama_vendor }} ({{ $product->nama }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="items[{{ $loop->index }}][jumlah_pack]" class="form-input item-qty" min="1" value="{{ $item->jumlah_pack }}" required
                                        >
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="items[{{ $loop->index }}][harga_beli]" class="form-input item-beli" 
                                            value="{{ $item->harga_beli }}" min="0" required>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="items[{{ $loop->index }}][harga_jual]" class="form-input item-jual" 
                                            value="{{ $item->harga_jual }}" min="0">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium item-subtotal">
                                        {{ number_format($item->qty * $item->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Input ID item agar controller tahu item mana yang diupdate --}}
                                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                        <button type="button" class="text-red-600 hover:text-red-900 remove-item-btn" title="Hapus Item">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mb-6">
                <button type="button" id="add-item-btn"
                    class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    + Tambah Produk
                </button>
                <div class="text-lg font-bold">
                    Total Akhir: <span id="total-akhir">0</span>
                </div>
            </div>
            {{-- END: Bagian Item Barang Masuk --}}


            <div class="button-container">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('barang_masuk.index') }}'">Batal</button>
                <button type="submit" class="btn btn-save">Update Data</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        // Data Produk dari PHP untuk JavaScript
        const productData = @json($products->keyBy('id'));
        // Set indeks awal untuk item baru, dimulai setelah item yang sudah ada
        let itemIndex = {{ isset($purchase->items) ? $purchase->items->count() : 0 }};

        // Fungsi yang sama dari store.blade.php
        function calculateSubtotal(row) {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const hargaBeli = parseFloat(row.querySelector('.item-beli').value) || 0;
            const subtotal = qty * hargaBeli;
            row.querySelector('.item-subtotal').textContent = subtotal.toLocaleString('id-ID');
            return subtotal;
        }

        function calculateTotal() {
            let total = 0;
            const itemList = document.getElementById('item-list');
            itemList.querySelectorAll('tr').forEach(row => {
                total += calculateSubtotal(row);
            });
            document.getElementById('total-akhir').textContent = total.toLocaleString('id-ID');
        }
        
        // Fungsi untuk membuat baris item baru
        function createItemRow() {
            const index = itemIndex++;
            const row = document.createElement('tr');
            // Gunakan nilai index untuk array items[]
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <select name="items[${index}][product_id]" class="form-select item-product" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option 
                                value="{{ $product->id }}" 
                                data-harga-beli="{{ $product->harga_beli }}" 
                                data-harga-jual="{{ $product->harga_jual }}"
                            >
                                {{ $product->nama_vendor }} ({{ $product->nama }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="items[${index}][qty]" class="form-input item-qty" min="1" value="1" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="items[${index}][harga_beli]" class="form-input item-beli" min="0" value="0" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="items[${index}][harga_jual]" class="form-input item-jual" min="0" value="0">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right font-medium item-subtotal">
                    0
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    {{-- Tidak perlu input hidden id karena ini item baru --}}
                    <button type="button" class="text-red-600 hover:text-red-900 remove-item-btn" title="Hapus Item">
                        Hapus
                    </button>
                </td>
            `;
            return row;
        }

        const itemList = document.getElementById('item-list');
        const addItemBtn = document.getElementById('add-item-btn');

        // Event Listener untuk Tambah Item
        addItemBtn.addEventListener('click', () => {
            itemList.appendChild(createItemRow());
            calculateTotal();
        });

        // Delegated Event Listener untuk Perubahan/Penghapusan Item
        itemList.addEventListener('change', (e) => {
            const target = e.target;
            if (target.classList.contains('item-product')) {
                // Ketika produk diubah, isi Harga Beli dan Harga Jual dari data-attribute
                const selectedOption = target.options[target.selectedIndex];
                const hargaBeli = selectedOption.getAttribute('data-harga-beli');
                const hargaJual = selectedOption.getAttribute('data-harga-jual');
                
                const row = target.closest('tr');
                if (row) {
                    row.querySelector('.item-beli').value = hargaBeli || 0;
                    row.querySelector('.item-jual').value = hargaJual || 0;
                }
                calculateTotal();
            } else if (target.classList.contains('item-qty') || target.classList.contains('item-beli')) {
                calculateTotal();
            }
        });
        
        itemList.addEventListener('input', (e) => {
             const target = e.target;
             if (target.classList.contains('item-qty') || target.classList.contains('item-beli')) {
                 calculateTotal();
             }
        });

        itemList.addEventListener('click', (e) => {
            const target = e.target;
            if (target.classList.contains('remove-item-btn')) {
                // Hapus baris item
                target.closest('tr').remove();
                calculateTotal();
            }
        });
        
        // Lakukan perhitungan total saat halaman dimuat (untuk item yang sudah ada)
        document.addEventListener('DOMContentLoaded', calculateTotal);
        
        // Tambahkan satu baris item secara default jika tidak ada item yang dimuat
        if (itemList.children.length === 0) {
            addItemBtn.click();
        }

    </script>
    <script src="{{ asset('assets/js/fab.js') }}"></script>
@endsection