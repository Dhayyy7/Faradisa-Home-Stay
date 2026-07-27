@extends('admin.layouts.app')

@section('title', 'Manajemen Fasilitas')
@section('page_title', 'Master Fasilitas Homestay')

@section('styles')
<style>
    .facility-grid {
        display: grid;
        grid-template-columns: 1fr 2.2fr;
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .facility-grid {
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

    .form-input, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-input:focus, .form-textarea:focus {
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

    .facility-icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background-color: #e0e7ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
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
        max-width: 500px;
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

<div class="facility-grid">
    <!-- Form Tambah Fasilitas -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-plus-circle" style="color: #4f46e5;"></i>
            Tambah Fasilitas Baru
        </h2>

        <form action="{{ route('admin.facilities.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Fasilitas</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Misal: Wi-Fi Gratis, AC, Water Heater" required>
            </div>

            <div class="form-group">
                <label for="icon" class="form-label">Icon FontAwesome <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <input type="text" id="icon" name="icon" class="form-input" placeholder="Misal: fa-wifi, fa-snowflake, fa-tv">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <textarea id="description" name="description" rows="3" class="form-textarea" placeholder="Keterangan singkat tentang fasilitas ini..."></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-save"></i>
                <span>Simpan Fasilitas</span>
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Fasilitas -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-list-check" style="color: #4f46e5;"></i>
            Daftar Fasilitas Terdaftar
        </h2>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Icon</th>
                        <th>Nama Fasilitas</th>
                        <th>Deskripsi</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilities as $index => $f)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="facility-icon-badge">
                                    <i class="fa-solid {{ $f->icon ?? 'fa-check-circle' }}"></i>
                                </div>
                            </td>
                            <td style="font-weight: 700; color: #1e293b;">{{ $f->name }}</td>
                            <td style="color: #64748b; font-size: 0.85rem;">{{ $f->description ?? '-' }}</td>
                            <td style="text-align: center;">
                                <div class="action-btns" style="justify-content: center;">
                                    <button type="button" class="btn-edit" onclick="openEditModal({{ $f->id }}, '{{ addslashes($f->name) }}', '{{ addslashes($f->icon ?? '') }}', '{{ addslashes($f->description ?? '') }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </button>

                                    <form action="{{ route('admin.facilities.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas {{ $f->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Fasilitas -->
<div class="modal-backdrop" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Edit Fasilitas</h3>
            <button type="button" class="btn-close-modal" onclick="closeEditModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit_name" class="form-label">Nama Fasilitas</label>
                <input type="text" id="edit_name" name="name" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_icon" class="form-label">Icon FontAwesome</label>
                <input type="text" id="edit_icon" name="icon" class="form-input">
            </div>

            <div class="form-group">
                <label for="edit_description" class="form-label">Deskripsi</label>
                <textarea id="edit_description" name="description" rows="3" class="form-textarea"></textarea>
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
    function openEditModal(id, name, icon, description) {
        const form = document.getElementById('editForm');
        form.action = `/admin/facilities/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_description').value = description;
        
        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>
@endsection
