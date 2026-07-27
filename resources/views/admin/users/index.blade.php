@extends('admin.layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen Data User')

@section('styles')
<style>
    .user-grid {
        display: grid;
        grid-template-columns: 1fr 2.3fr;
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .user-grid {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background-color: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.15rem;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.4rem;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.9rem;
        outline: none;
        background-color: #ffffff;
        transition: border-color 0.2s;
    }

    .form-input:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .btn-submit {
        background-color: #4f46e5;
        color: #ffffff;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #4338ca;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .data-table th {
        background-color: #f8fafc;
        padding: 0.875rem 1rem;
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .badge-role-pill {
        background-color: #e0e7ff;
        color: #4338ca;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-no-role {
        background-color: #f1f5f9;
        color: #64748b;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-edit {
        background-color: #e0e7ff;
        color: #4338ca;
        border: none;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
    }

    .btn-edit:hover {
        background-color: #c7d2fe;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background-color: #fca5a5;
    }

    /* Modal Backdrop & Card */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease;
    }

    .modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-card {
        background: #ffffff;
        width: 100%;
        max-width: 520px;
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: translateY(20px);
        transition: transform 0.25s ease;
    }

    .modal-backdrop.show .modal-card {
        transform: translateY(0);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    .btn-close-modal {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #64748b;
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<div class="user-grid">
    <!-- Form Tambah User -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-user-plus" style="color: #4f46e5;"></i>
            Tambah User Baru
        </h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Masukkan nama pengguna" required>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Masukan Username" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Masukan Email" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Masukan Password" required>
            </div>

            <div class="form-group">
                <label for="role_user" class="form-label">Role User</label>
                <select id="role_user" name="role_user" class="form-select">
                    <option value="">-- Pilih Role (Opsional) --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-save"></i>
                <span>Simpan User</span>
            </button>
        </form>
    </div>

    <!-- Tabel Daftar User -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-users-gear" style="color: #4f46e5;"></i>
            Daftar Pengguna Sistem
        </h2>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama & Username</th>
                        <th>Email</th>
                        <th>Role User</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $u)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: #1e293b;">{{ $u->name }}</div>
                                <div style="font-size: 0.8rem; color: #64748b;">@ {{ $u->username }}</div>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->role_user)
                                    <span class="badge-role-pill">{{ $u->role_user }}</span>
                                @else
                                    <span class="badge-no-role">Belum diset</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="action-btns" style="justify-content: center;">
                                    <button type="button" class="btn-edit" onclick="openEditModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->username) }}', '{{ addslashes($u->email) }}', '{{ addslashes($u->role_user ?? '') }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </button>

                                    @if(Auth::id() !== $u->id)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal-backdrop" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Edit Data User</h3>
            <button type="button" class="btn-close-modal" onclick="closeEditModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit_name" class="form-label">Nama Lengkap</label>
                <input type="text" id="edit_name" name="name" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_username" class="form-label">Username</label>
                <input type="text" id="edit_username" name="username" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_email" class="form-label">Alamat Email</label>
                <input type="email" id="edit_email" name="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_password" class="form-label">Password Baru <span style="font-weight: 400; color: #64748b;">(Kosongkan jika tidak ingin mengubah)</span></label>
                <input type="password" id="edit_password" name="password" class="form-input" placeholder="Password baru...">
            </div>

            <div class="form-group">
                <label for="edit_role_user" class="form-label">Role User</label>
                <select id="edit_role_user" name="role_user" class="form-select">
                    <option value="">-- Tanpa Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569;" onclick="closeEditModal()">
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openEditModal(id, name, username, email, roleUser) {
        const form = document.getElementById('editForm');
        form.action = `/admin/users/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_role_user').value = roleUser;
        
        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>
@endsection
