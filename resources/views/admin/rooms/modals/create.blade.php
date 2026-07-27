<!-- Modal Tambah Kamar -->
<div class="modal-backdrop" id="createRoomModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Kamar / Unit Baru</h3>
            <button type="button" class="btn-close-modal" onclick="closeCreateRoomModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="code" class="form-label">Kode Kamar</label>
                <input type="text" id="code" name="code" class="form-input" placeholder="Masukan Kode Kamar (misal: KMR-001)" required>
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
                <input type="number" id="discount" name="discount" class="form-input" placeholder="Masukan Diskon (misal: 10 untuk 10%)" min="0" max="100" step="0.1">
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

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closeCreateRoomModal()">
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i>
                    <span>Simpan Kamar</span>
                </button>
            </div>
        </form>
    </div>
</div>
