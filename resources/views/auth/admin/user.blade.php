@extends('layouts.admin')

@section('title', 'Admin | User')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... CSS Anda tetap sama ... */
        .modal-bg {
            background-color: var(--bg-modal);
            border: 1px solid var(--border-primary);
            box-shadow: var(--shadow-lg);
            transition: background-color 0.3s;
        }

        .input-field {
            background-color: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 4px;
            padding: 8px 12px;
            width: 100%;
            outline: none;
            color: var(--text-main);
            transition: all 0.3s;
        }

        .input-field:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .btn-modal-action {
            border: 1px solid var(--border-input);
            color: var(--text-muted);
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            background-color: transparent;
            transition: all 0.2s;
        }

        .btn-modal-action:hover {
            background-color: var(--nav-hover-bg);
            color: var(--text-main);
        }

        /* 1. WRAPPER UTAMA FAB */
        .fab {
            z-index: 9999;
            /* KUNCI: Buat container selebar tombol utama (56px) & ratakan tengah */
            width: 56px !important;
            flex-direction: column !important;
            align-items: center !important;
            /* Semua tombol dipaksa ke tengah as */
            right: 24px !important;
            /* Jarak dari kanan layar (right-6) */
        }

        /* 2. ITEM LIST (ANAK-ANAK) */
        .fab-items {
            width: 100%;
            display: flex;
            flex-direction: column-reverse;
            align-items: center;
            /* Pastikan anak-anak juga rata tengah */
            gap: 12px;
            padding-bottom: 12px;
            /* Jarak antara tombol kecil & tombol utama */
        }

        .fab-item-wrapper {
            /* Wrapper memenuhi lebar 56px, tombol ditaruh di tengah */
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Tombol kecil dipaksa ke tengah */
            position: relative;
            /* Jangkar untuk label absolute */

            /* Animasi */
            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            opacity: 1;
            transform: translateY(0) scale(1);
            visibility: visible;
            height: 48px;
            /* Tinggi fix sesuai tombol kecil */
        }

        /* Kondisi Tersembunyi */
        .fab-item-wrapper.hidden-space {
            opacity: 0;
            transform: translateY(20px) scale(0.8);
            visibility: hidden;
            height: 0;
        }

        /* Tombol Bulat Anak (48px) */
        .fab-item {
            width: 48px;
            height: 48px;
            border: none;
            outline: none;
            cursor: pointer;
            border-radius: 9999px;
            /* Tidak perlu margin aneh-aneh lagi */
        }

        /* 3. LABEL TEKS (POSISI ABSOLUTE) */
        /* Label menempel melayang di sebelah kiri tombol */
        .fab-label {
            position: absolute;
            right: 56px;
            /* Geser ke kiri sejauh lebar container */
            top: 50%;
            transform: translateY(-50%);
            /* Trik CSS biar lurus vertikal tengah */

            font-family: 'Inter', sans-serif;
            background-color: #1f2937;
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-right: 12px;
            /* Jarak label ke tombol */
            white-space: nowrap;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        /* 4. TOMBOL UTAMA (TRIGGER) */
        #fabMain {
            width: 56px;
            height: 56px;
            position: relative;
            border: none;
            outline: none;
            cursor: pointer;
            transform: none !important;
            transition: background-color 0.2s;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            /* Otomatis rata tengah karena parent align-items: center */
        }

        /* ICON X */
        #iconX {
            position: static !important;
            transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        #fabMain[aria-expanded="true"] #iconX {
            transform: rotate(135deg) !important;
        }
    </style>

    {{-- HEADER --}}
    <div class="px-8 py-6 space-y-4">
        <div class="dark:text-white flex items-center text-sm text-gray-500">
            <span class="text-gray-500 dark:text-gray-500">Admin</span>
            <svg class="h-4 w-4 mx-1 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-500 dark:text-white-500 font-medium">User</span>
        </div>

        {{-- HEADER TAMBAH USER --}}
        <div class="flex flex-col space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white m-0">User</h2>
                <div class="hidden md:flex gap-2">
                    <button onclick="openModal('modalTambahUser')"
                        class="bg-[#1e5bb5] hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-semibold transition shadow-sm">
                        Tambah User <i class="fa-solid fa-user-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <x-table :data-table="['Nama Lengkap' => 'nama_user', 'Username' => 'username', 'Role' => 'role']"
            data-url="{{ route('api.user.index') }}" primary_key="id_user">
        </x-table>
    </div>

    {{-- FAB MOBILE --}}
    <!-- FAB Container (Mobile) -->
    <div class="md:hidden fab fixed bottom-6 right-6 flex flex-col z-50">
        <div class="fab-items">

            {{-- Tambah User Baru --}}
            <div class="fab-item-wrapper hidden-space">
                <span class="fab-label" onclick="openModal('modalTambahUser')">
                    Tambah User
                </span>
                <button onclick="openModal('modalTambahUser')"
                    class="fab-item shadow-lg flex items-center justify-center text-white bg-[#1e5bb5] hover:opacity-90">
                    <i class="fa-solid fa-user-plus"></i>
                </button>
            </div>
        </div>

        <button id="fabMain"
            class="fab-main rounded-full shadow-xl bg-[var(--accent-primary)] hover:opacity-90 text-white z-50"
            aria-expanded="false" aria-label="Open FAB">

            <span id="iconX" class="fab-icon visible text-xl">
                <i class="fa-solid fa-plus"></i>
            </span>
        </button>

    </div>

    <!-- Modal Tambah User -->
    <div id="modalTambahUser" class="fixed inset-0 bg-black/30 hidden justify-center items-center z-50 p-4">
        <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
            <button onclick="closeModal('modalTambahUser')"
                class="absolute top-4 right-4 text-3xl font-light hover:text-red-600">&times;</button>

            <form id="formTambahUser" onsubmit="submitTambahUser(event)">
                <div class="p-8 pt-4">
                    <h3 class="text-lg mb-6 text-black">Tambah User Baru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="nama_user" placeholder="Nama Lengkap" class="input-field" required>
                        </div>
                        <div>
                            <select name="role" class="input-field text-gray-700" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="username" placeholder="Username" class="input-field" required>
                        </div>
                        <div>
                            <input type="password" name="password" placeholder="Password (Minimal 6 karakter)"
                                class="input-field placeholder-gray-500" minlength="6" required>
                            <small class="text-gray-500 text-xs">* Minimal 6 karakter</small>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-10">
                        <button type="submit" class="btn-modal-action hover:bg-blue-50 text-blue-600 border-blue-400">Simpan
                            Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="modalEdit" class="fixed inset-0 bg-black/30 hidden justify-center items-center z-50 p-4">
        <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
            <button onclick="closeModal('modalEdit')"
                class="absolute top-4 right-4 text-3xl font-light hover:text-red-600">&times;</button>

            <form id="formEditUser" onsubmit="submitEditUser(event)">
                <input type="hidden" id="edit_id_user" name="id_user">
                <div class="p-8 pt-4">
                    <h3 class="text-lg mb-6 text-black font-semibold">Edit Data User</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs text-gray-500">Nama Lengkap</label>
                            <input type="text" id="edit_nama_user" name="nama_user" class="input-field" required>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Role</label>
                            <select id="edit_role" name="role" class="input-field text-gray-700" required>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Username</label>
                            <input type="text" id="edit_username" name="username" class="input-field" required>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="input-field" minlength="6">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-10">
                        <button type="submit"
                            class="btn-modal-action text-green-600 border-green-400 hover:bg-green-50">Update Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/fab.js') }}"></script>
    <script>
        // --- Modal Logic ---
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }
        window.onclick = function (e) {
            if (e.target == document.getElementById('modalTambahUser')) closeModal('modalTambahUser');
            if (e.target == document.getElementById('modalEdit')) closeModal('modalEdit');
        }

        // --- Submit Logic (DIPERBAIKI) ---
        async function submitTambahUser(e) {
            e.preventDefault();
            const form = document.getElementById('formTambahUser');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch("{{ route('api.user.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // <--- Wajib agar Laravel kirim JSON
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Sukses: ' + result.message);
                    window.location.reload();
                } else if (response.status === 422) {
                    // Tampilkan pesan error validasi (termasuk soal password < 6 karakter)
                    let errorMessages = "";
                    for (const field in result.errors) {
                        errorMessages += result.errors[field].join("\n") + "\n";
                    }
                    alert("Terjadi Kesalahan:\n" + errorMessages);
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan sistem.'));
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Tidak dapat terhubung ke server.');
            }
        }

        // --- Listener Edit Data (Dari Component Table) ---
        window.addEventListener('edit-data', function (e) {
            const rawData = e.detail.row || e.detail;
            const data = (rawData.data) ? rawData.data : rawData;

            console.log('Data User untuk Edit:', data);

            // Buka Modal
            openModal('modalEdit');

            // Isi Form Input
            document.getElementById('edit_id_user').value = data.id_user || '';
            document.getElementById('edit_nama_user').value = data.nama_user || '';
            document.getElementById('edit_username').value = data.username || '';
            document.getElementById('edit_role').value = data.role || 'staff';
        });

        // --- FUNGSI SUBMIT UPDATE ---
        async function submitEditUser(e) {
            e.preventDefault();

            const id = document.getElementById('edit_id_user').value;
            if (!id) {
                alert('ID User tidak ditemukan!');
                return;
            }

            const form = document.getElementById('formEditUser');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(`/api/user/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Data User berhasil diperbarui!');
                    closeModal('modalEdit');
                    window.location.reload();
                } else {
                    let msg = result.message || 'Gagal update data';
                    if (result.errors) {
                        msg = Object.values(result.errors).flat().join('\n');
                    }
                    alert(msg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem saat memperbarui data.');
            }
        }
    </script>
@endsection