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
                <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closeEditModal()">
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
