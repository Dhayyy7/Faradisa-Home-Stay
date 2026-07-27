@extends('admin.layouts.app')

@section('title', 'Kamar & Unit')
@section('page_title', 'Pengelolaan Kamar & Unit')

@section('styles')
<style>
    .room-grid {
        display: grid;
        grid-template-columns: 1fr 2.4fr;
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .room-grid {
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

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Checkbox Group for Select to Many Facilities */
    .facility-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.6rem;
        background: #f8fafc;
        padding: 0.875rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        max-height: 180px;
        overflow-y: auto;
    }

    .facility-checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.825rem;
        color: #334155;
        cursor: pointer;
    }

    .facility-checkbox-item input[type="checkbox"] {
        accent-color: #4f46e5;
        width: 16px;
        height: 16px;
        cursor: pointer;
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

    .badge-code {
        background-color: #e0e7ff;
        color: #4338ca;
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .badge-discount-percent {
        background-color: #fee2e2;
        color: #dc2626;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .facility-badge-pill {
        background-color: #f1f5f9;
        color: #475569;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        font-size: 0.75rem;
        display: inline-block;
        margin: 0.15rem 0.1rem;
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.35rem;
        width: 100%;
        max-width: 100px;
        margin: 0 auto;
    }

    .action-btns form {
        width: 100%;
        margin: 0;
    }

    .btn-preview {
        background-color: #ccfbf1;
        color: #0f766e;
        border: none;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-preview:hover {
        background-color: #99f6e4;
    }

    .btn-edit {
        background-color: #e0e7ff;
        color: #4338ca;
        border: none;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-edit:hover {
        background-color: #c7d2fe;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 0.35rem 0.6rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        transition: all 0.2s;
        width: 100%;
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
        max-width: 580px;
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: translateY(20px);
        transition: transform 0.25s ease;
        max-height: 90vh;
        overflow-y: auto;
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

<div class="room-grid">
    <!-- Form Tambah Kamar -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-plus-circle" style="color: #4f46e5;"></i>
            Tambah Kamar / Unit
        </h2>

        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="code" class="form-label">Kode Kamar</label>
                <input type="text" id="code" name="code" class="form-input" placeholder="Masukan Kode Kamar" required>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Nama Kamar / Unit</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Masukan Nama Kamar" required>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Harga per Malam (Rp)</label>
                <input type="number" id="price" name="price" class="form-input" placeholder="Masukan Harga Kamar" min="0" required>
            </div>

            <div class="form-group">
                <label for="discount" class="form-label">Diskon (%) <span style="font-weight: 400; color: #64748b;">(Persen, Opsional)</span></label>
                <input type="number" id="discount" name="discount" class="form-input" placeholder="Masukan Diskon" min="0" max="100" step="0.1">
            </div>

            <div class="form-group">
                <label class="form-label">Fasilitas Kamar <span style="font-weight: 400; color: #64748b;">(Pilih Banyak)</span></label>
                <div class="facility-checkbox-grid">
                    @foreach($facilities as $f)
                    <label class="facility-checkbox-item">
                        <input type="checkbox" name="facilities[]" value="{{ $f->id }}">
                        <span>{{ $f->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="images" class="form-label">Foto Kamar <span style="font-weight: 400; color: #64748b;">(Maksimal 5 Foto)</span></label>
                <input type="file" id="images" name="images[]" class="form-input" multiple accept="image/*">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-save"></i>
                <span>Simpan Kamar</span>
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kamar -->
    <div class="card">
        <h2 class="card-title">
            <i class="fa-solid fa-bed" style="color: #4f46e5;"></i>
            Daftar Kamar & Unit Terdaftar
        </h2>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode & Nama Kamar</th>
                        <th>Harga Normal</th>
                        <th>Diskon</th>
                        <th>Fasilitas</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $index => $room)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div><span class="badge-code">{{ $room->code }}</span></div>
                            <div style="font-weight: 700; color: #1e293b; margin-top: 0.25rem;">{{ $room->name }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                            @if($room->discount && $room->discount > 0)
                            <div style="font-size: 0.78rem; color: #16a34a; font-weight: 600;">
                                Nett: Rp {{ number_format($room->final_price, 0, ',', '.') }}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($room->discount && $room->discount > 0)
                            <span class="badge-discount-percent">{{ number_format($room->discount, 0) }}% OFF</span>
                            @else
                            <span style="color: #94a3b8; font-style: italic;">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            @forelse($room->facilities as $f)
                            <span class="facility-badge-pill">
                                <i class="fa-solid {{ $f->icon ?? 'fa-check' }}" style="font-size: 0.7rem; color: #4f46e5;"></i>
                                {{ $f->name }}
                            </span>
                            @empty
                            <span style="color: #94a3b8; font-style: italic;">Belum ada fasilitas</span>
                            @endforelse
                        </td>
                        <td style="text-align: center;">
                            <div class="action-btns" style="justify-content: center;">
                                <button type="button" class="btn-preview" onclick="openPreviewModal({{ json_encode($room) }}, {{ json_encode($room->facilities) }}, {{ $room->final_price }})">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Preview</span>
                                </button>

                                <button type="button" class="btn-edit" onclick="openEditModal({{ $room->id }}, '{{ addslashes($room->code) }}', '{{ addslashes($room->name) }}', {{ $room->price }}, {{ $room->discount ?? 0 }}, {{ json_encode($room->facilities->pluck('id')->toArray()) }})">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </button>

                                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar {{ $room->name }}?')">
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

<!-- Modal Edit Kamar -->
<div class="modal-backdrop" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Edit Data Kamar / Unit</h3>
            <button type="button" class="btn-close-modal" onclick="closeEditModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit_code" class="form-label">Kode Kamar</label>
                <input type="text" id="edit_code" name="code" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_name" class="form-label">Nama Kamar / Unit</label>
                <input type="text" id="edit_name" name="name" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_price" class="form-label">Harga (Rp)</label>
                <input type="number" id="edit_price" name="price" class="form-input" min="0" required>
            </div>

            <div class="form-group">
                <label for="edit_discount" class="form-label">Diskon (%)</label>
                <input type="number" id="edit_discount" name="discount" class="form-input" min="0" max="100" step="0.1">
            </div>

            <div class="form-group">
                <label class="form-label">Fasilitas Kamar (Pilih Banyak)</label>
                <div class="facility-checkbox-grid">
                    @foreach($facilities as $f)
                    <label class="facility-checkbox-item">
                        <input type="checkbox" name="facilities[]" value="{{ $f->id }}" class="edit-facility-checkbox" id="edit_facility_{{ $f->id }}">
                        <span>{{ $f->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="edit_images" class="form-label">Unggah Foto Baru <span style="font-weight: 400; color: #64748b;">(Maksimal 5 Foto)</span></label>
                <input type="file" id="edit_images" name="images[]" class="form-input" multiple accept="image/*">
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

<!-- Modal Preview Kamar & Foto -->
<div class="modal-backdrop" id="previewModal">
    <div class="modal-card" style="max-width: 650px;">
        <div class="modal-header">
            <div>
                <span class="badge-code" id="preview_code">KMR-001</span>
                <h3 class="modal-title" id="preview_name" style="margin-top: 0.25rem;">Detail Preview Kamar</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closePreviewModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label class="form-label">Galeri Foto Kamar</label>
            <div id="preview_images_container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                <!-- Dynamic photos loaded here -->
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; background-color: #f8fafc; padding: 1.1rem; border-radius: 12px; margin-bottom: 1.25rem; border: 1px solid #e2e8f0;">
            <div>
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Harga Normal</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-top: 0.2rem;" id="preview_price">Rp 0</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Diskon</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #dc2626; margin-top: 0.2rem;" id="preview_discount">0%</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Harga Nett</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #16a34a; margin-top: 0.2rem;" id="preview_final_price">Rp 0</div>
            </div>
        </div>

        <div>
            <label class="form-label">Fasilitas Terpasang</label>
            <div id="preview_facilities_container" style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem;">
                <!-- Dynamic facilities loaded here -->
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1.75rem;">
            <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; padding: 0.6rem 1.25rem;" onclick="closePreviewModal()">
                Tutup Preview
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openEditModal(id, code, name, price, discount, selectedFacilityIds) {
        const form = document.getElementById('editForm');
        form.action = `/admin/rooms/${id}`;

        document.getElementById('edit_code').value = code;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_discount').value = discount > 0 ? discount : '';

        // Reset all facility checkboxes
        document.querySelectorAll('.edit-facility-checkbox').forEach(cb => {
            cb.checked = selectedFacilityIds.includes(parseInt(cb.value));
        });

        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function openPreviewModal(room, facilities, finalPrice) {
        document.getElementById('preview_code').innerText = room.code;
        document.getElementById('preview_name').innerText = room.name;
        document.getElementById('preview_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(room.price);
        document.getElementById('preview_discount').innerText = room.discount && room.discount > 0 ? room.discount + '%' : 'Tidak Ada';
        document.getElementById('preview_final_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice);

        // Render Photos
        const imgContainer = document.getElementById('preview_images_container');
        imgContainer.innerHTML = '';

        if (room.images && Array.isArray(room.images) && room.images.length > 0) {
            room.images.forEach(imgUrl => {
                const imgElement = document.createElement('img');
                imgElement.src = `/${imgUrl}`;
                imgElement.style.width = '100%';
                imgElement.style.height = '90px';
                imgElement.style.objectFit = 'cover';
                imgElement.style.borderRadius = '10px';
                imgElement.style.border = '1px solid #e2e8f0';
                imgContainer.appendChild(imgElement);
            });
        } else {
            imgContainer.innerHTML = `
                <div style="grid-column: 1 / -1; padding: 1.5rem; text-align: center; background-color: #f8fafc; border-radius: 12px; color: #94a3b8; font-size: 0.85rem; border: 1px dashed #cbd5e1;">
                    <i class="fa-solid fa-images" style="font-size: 1.75rem; margin-bottom: 0.5rem; display: block; color: #cbd5e1;"></i>
                    Belum ada foto kamar yang diunggah.
                </div>
            `;
        }

        // Render Facilities
        const facContainer = document.getElementById('preview_facilities_container');
        facContainer.innerHTML = '';

        if (facilities && facilities.length > 0) {
            facilities.forEach(f => {
                const badge = document.createElement('span');
                badge.className = 'facility-badge-pill';
                badge.style.padding = '0.35rem 0.65rem';
                badge.style.fontSize = '0.8rem';
                badge.innerHTML = `<i class="fa-solid ${f.icon || 'fa-check'}" style="color: #4f46e5; margin-right: 0.35rem;"></i> ${f.name}`;
                facContainer.appendChild(badge);
            });
        } else {
            facContainer.innerHTML = '<span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Belum ada fasilitas yang dipasang.</span>';
        }

        document.getElementById('previewModal').classList.add('show');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.remove('show');
    }
</script>
@endsection