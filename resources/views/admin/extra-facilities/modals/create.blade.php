<!-- Modal Tambah Extra Fasilitas -->
<div class="modal-backdrop" id="createExtraFacilityModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Extra Fasilitas Baru</h3>
            <button type="button" class="btn-close-modal" onclick="closeCreateExtraFacilityModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.extra-facilities.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Extra Fasilitas</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Misal: Extra Bed / Kasur Tambahan" required>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Harga Biaya (Rp)</label>
                <input type="number" id="price" name="price" class="form-input" placeholder="Misal: 75000" min="0" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <textarea id="description" name="description" rows="3" class="form-textarea" placeholder="Masukan Deskripsi Keterangan Fasilitas"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closeCreateExtraFacilityModal()">
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i>
                    <span>Simpan Extra Fasilitas</span>
                </button>
            </div>
        </form>
    </div>
</div>
