<!-- Modal Edit Extra Fasilitas -->
<div class="modal-backdrop" id="editExtraFacilityModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Edit Extra Fasilitas</h3>
            <button type="button" class="btn-close-modal" onclick="closeEditExtraFacilityModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editExtraFacilityForm" method="POST" onsubmit="return confirmSubmit(this, 'Anda Yakin Akan Merubah Data Extra Fasilitas ini?', 'Konfirmasi Perubahan');">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit_name" class="form-label">Nama Extra Fasilitas</label>
                <input type="text" id="edit_name" name="name" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_price" class="form-label">Harga Biaya (Rp)</label>
                <input type="number" id="edit_price" name="price" class="form-input" min="0" required>
            </div>

            <div class="form-group">
                <label for="edit_description" class="form-label">Deskripsi <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <textarea id="edit_description" name="description" rows="3" class="form-textarea"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closeEditExtraFacilityModal()">
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
