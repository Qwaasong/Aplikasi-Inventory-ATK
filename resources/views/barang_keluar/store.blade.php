@extends('layouts/main')
@section('title', 'KopsisApp - Barang Keluar')
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
        .form-select,
        .form-textarea {
            width: 100%;
            /* height: 50%; */
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            background-color: #fafafa;
            box-sizing: border-box;
            min-height: 48%;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #999;
            font-style: italic;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #4a90e2;
            background-color: white;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
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

        /* Layout khusus untuk form */
        .form-row-top {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-row-middle {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .produk-column {
            flex: 1;
        }

        .tanggal-column {
            flex: 1;
        }

        .jumlah-column {
            flex: 1;
        }

        .harga-column {
            flex: 1;
        }

        /* CSS untuk input dengan suffix */
        .input-with-suffix {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-with-suffix .form-input {
            padding-right: 60px;
        }

        .input-suffix {
            position: absolute;
            right: 12px;
            color: #666;
            font-size: 14px;
            pointer-events: none;
            background: rgba(255, 255, 255, 0.8);
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Custom Styling untuk Tom Select agar tinggi sama */
        .ts-control {
            border: 1px solid #e0e0e0 !important;
            border-radius: 6px !important;
            padding: 12px !important;
            font-size: 16px !important;
            background-color: #fafafa !important;
            box-shadow: none !important;
            min-height: 48px !important;
            /* Sama dengan form-input */
            display: flex !important;
            align-items: center !important;
        }

        .ts-control.focus {
            outline: none !important;
            border-color: #4a90e2 !important;
            background-color: white !important;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1) !important;
        }

        /* Pastikan input di dalam Tom Select juga sama tingginya */
        .ts-control input {
            height: auto !important;
            min-height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
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
            .form-row-middle,
            .form-row {
                flex-direction: column;
                gap: 0;
                margin-bottom: 0;
            }

            .form-column,
            .produk-column,
            .tanggal-column,
            .jumlah-column,
            .harga-column {
                margin-bottom: 16px;
            }

            .form-input,
            .form-select,
            .form-textarea {
                font-size: 16px;
                padding: 14px 12px;
            }

            .input-with-suffix .form-input {
                padding-right: 50px;
            }

            /* Mobile Responsive untuk Tom Select */
            .ts-control {
                padding: 14px 12px !important;
                font-size: 16px !important;
            }

            .ts-dropdown .option {
                padding: 14px 12px !important;
                font-size: 16px !important;
            }

            .ts-control input {
                font-size: 16px !important;
            }

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

            .form-textarea {
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
            .form-select,
            .form-textarea {
                padding: 16px 12px;
            }

            .ts-control {
                padding: 16px 12px !important;
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
        <h2 class="font-bold">Tambah Barang Keluar</h2>
    </div>

    <div class="block">
        <form action="{{ route('barang_keluar.store') }}" method="POST">
            @csrf
    
            <div class="form-section">
                <div class="form-row-top">
                    <!-- Input Produk dengan fitur pencarian -->
                    <div class="produk-column">
                        <label class="form-label" for="product_id">Produk</label>
                        <select id="product_id" name="product_id" class="form-input" required>
                            <option value="" disabled selected>Pilih produk...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="tanggal-column">
                        <label class="form-label" for="tanggal_keluar">Tanggal</label>
                        <input type="date" id="tanggal_keluar" name="tanggal" class="form-input"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
    
                <div class="form-row-middle">
                    <div class="jumlah-column">
                        <label class="form-label" for="jumlah">Jumlah Pack</label>
                        <div class="input-with-suffix">
                            <input type="number" id="jumlah" name="jumlah_pack" class="form-input"
                                placeholder="Masukkan jumlah pack..." min="1" required>
                            <span class="input-suffix">Pack</span>
                        </div>
                    </div>
                </div>
    
                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3"
                            placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </div>
            </div>
    
            <div class="button-container">
                <button type="submit" name="save_and_create" value="1" class="btn btn-save-again">
                    Simpan Data Dan Buat Lagi
                </button>
                <button type="submit" class="btn btn-save">Simpan</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('barang_keluar.index') }}'">Batal</button>
            </div>
        </form>
    </div>

@endsection

@section('script')
<script>
   // Function to get available stock for a product
   function getAvailableStock(productId) {
       if (!productId) return;
       
       $.ajax({
           url: '/api/produk/' + productId + '/stock',
           method: 'GET',
           success: function(data) {
               if (data.success && data.data) {
                   var maxStock = data.data.available_stock || 0;
                   // Set max to the available stock plus a reasonable buffer to prevent negative values
                   $('#jumlah').attr('max', maxStock);
                   // Also update the min to ensure it's at least 1
                   $('#jumlah').attr('min', '1');
               }
           },
           error: function() {
               // If API call fails, set a default max value
               $('#jumlah').attr('max', '999999');
           }
       });
   }
   
   // Update max value when product selection changes
   document.getElementById('product_id').addEventListener('change', function() {
       var selectedProductId = this.value;
       if (selectedProductId) {
           getAvailableStock(selectedProductId);
       } else {
           // Reset to default if no product selected
           $('#jumlah').removeAttr('max');
           $('#jumlah').attr('max', '999999');
       }
   });
   
   // Also set max to current value if editing
   document.addEventListener('DOMContentLoaded', function() {
       var jumlahInput = document.getElementById('jumlah');
       if (jumlahInput) {
           // Set a default max value to prevent extremely large numbers
           jumlahInput.setAttribute('max', '999999');
           jumlahInput.setAttribute('min', '1');
           
           // If there's already a selected product, get its stock
           var selectedProduct = document.getElementById('product_id');
           if (selectedProduct && selectedProduct.value) {
               getAvailableStock(selectedProduct.value);
           }
       }
   });
   
   </script>
    
@endsection
