@extends('layouts.admin')

@section('title', 'Admin | User')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... CSS Anda tetap sama ... */
        body { background-color: #EEEFF1; }
        .modal-bg { background-color: #F5F3F4; }
        .input-field {
            background-color: transparent; border: 1px solid #333;
            border-radius: 4px; padding: 8px 12px; width: 100%; outline: none;
        }
        .input-field:focus { border-color: #2563EB; }
        .btn-modal-action {
            border: 1px solid #6B7280; color: #6B7280; padding: 6px 12px;
            border-radius: 4px; font-size: 0.85rem; cursor: pointer;
        }
        .btn-modal-action:hover { background-color: #E5E7EB; color: #111; }
    </style>

    <div class="px-8 py-6">
        <div class="flex flex-col space-y-4">
             <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold text-gray-900 m-0">Daftar User</h2>
                <div class="hidden md:flex gap-2">
                    <button onclick="openModal('modalTambahUser')"
                        class="bg-[#1e5bb5] hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-semibold transition shadow-sm">
                        Tambah User <i class="fa-solid fa-user-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <x-table :data-table="['Nama User' => 'nama_user', 'Username' => 'username', 'Role' => 'role']" 
                 data-url="{{ route('api.user.index') }}" 
                 primary_key="id_user">
        </x-table>
    </div>

    <div id="modalTambahUser" class="fixed inset-0 bg-black bg-opacity-30 hidden flex justify-center items-center z-50 p-4">
        <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
            <button onclick="closeModal('modalTambahUser')" class="absolute top-4 right-4 text-3xl font-light hover:text-red-600">&times;</button>
            
            <form id="formTambahUser" onsubmit="submitTambahUser(event)">
                <div class="p-8 pt-4">
                    <h3 class="text-lg mb-6 text-black">Tambah User Baru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="nama_user" placeholder="Nama User" class="input-field" required>
                        </div>
                        <div>
                            <select name="role" class="input-field text-gray-700" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="username" placeholder="Username" class="input-field" required>
                        </div>
                        <div>
                            <input type="password" 
                                   name="password" 
                                   placeholder="Password (Minimal 6 karakter)" 
                                   class="input-field placeholder-gray-500" 
                                   minlength="6" 
                                   required>
                            <small class="text-gray-500 text-xs">* Minimal 6 karakter</small>
</div>
                    </div>
                    <div class="flex justify-end gap-3 mt-10">
                        <button type="submit" class="btn-modal-action hover:bg-blue-50 text-blue-600 border-blue-400">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditUser" class="fixed inset-0 bg-black bg-opacity-30 hidden flex justify-center items-center z-50 p-4">
    <div class="modal-bg rounded-lg shadow-lg w-full max-w-2xl relative">
        <button onclick="closeModal('modalEditUser')" class="absolute top-4 right-4 text-3xl font-light hover:text-red-600">&times;</button>
        
        <form id="formEditUser" onsubmit="submitEditUser(event)">
            <input type="hidden" id="edit_id_user" name="id_user">
            <div class="p-8 pt-4">
                <h3 class="text-lg mb-6 text-black font-semibold">Edit Data User</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs text-gray-500">Nama User</label>
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
                    <button type="submit" class="btn-modal-action text-green-600 border-green-400 hover:bg-green-50">Update Data</button>
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
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
    window.onclick = function(e) { if(e.target == document.getElementById('modalTambahUser')) closeModal('modalTambahUser'); }

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

// --- FUNGSI SUBMIT UPDATE ---
    async function submitEditUser(e) {
        e.preventDefault();
        
        const id = document.getElementById('edit_id_user').value;
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

            if (response.ok && result.success) {
                alert('Data User berhasil diperbarui!');
                closeModal('modalEditUser');
                window.location.reload();
            } else {
                alert('Gagal: ' + (result.message || 'Terjadi kesalahan validasi'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem saat update');
        }
    }

// ================= FUNGSI DELETE =================
async function deleteUser(id) {
    if (!id || !confirm('Apakah Anda yakin ingin menghapus user ini?')) return;

    try {
        // TAMBAHKAN PREFIX /api/ DI DEPAN URL
        const response = await fetch(`/api/user/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok) {
            alert('User berhasil dihapus');
            window.location.reload();
        } else {
            alert('Gagal: ' + (result.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan koneksi.');
    }
}
// ================= FUNGSI UPDATE (SIMPAN PERUBAHAN) =================
async function submitEditUser(e) {
    e.preventDefault();
    const id = document.getElementById('edit_id_user').value;
    const form = document.getElementById('formEditUser');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        // GUNAKAN /api/user/
        const response = await fetch(`/api/user/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        // ... sisa kode sama ...
        const result = await response.json();

        if (response.ok) {
            alert('Data berhasil diperbarui!');
            closeModal('modalEditUser');
            window.location.reload();
        } else {
            // Tampilkan error validasi jika password < 6 atau username duplikat
            let msg = result.message || 'Gagal update data';
            if (result.errors) {
                msg = Object.values(result.errors).flat().join('\n');
            }
            alert(msg);
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan sistem saat memperbarui data.');
    }
}
</script>
@endsection