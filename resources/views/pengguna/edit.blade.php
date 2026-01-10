@extends('layouts.main')
@section('title', 'KopsisApp - Edit Pengguna')

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
        <h2 class="font-bold">Edit Pengguna</h2>
    </div>

    <div class="block">
        <form action="{{ route('pengguna.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-section">
                <div class="form-row">
                    <div class="form-column">
                        <label class="form-label" for="name">Nama <span style="color: #D20D24;">*</span></label>
                        <input type="text" id="name" name="name" class="form-input" 
                               value="{{ old('name', $user->name) }}" placeholder="Masukkan nama..." required>
                    </div>
                    <div class="form-column">
                        <label class="form-label" for="email">Email <span style="color: #D20D24;">*</span></label>
                        <input type="email" id="email" name="email" class="form-input" 
                               value="{{ old('email', $user->email) }}" placeholder="Masukkan email..." required>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                <a href="{{ route('pengguna.index') }}" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
@endsection
