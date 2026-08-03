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

            <div class="form-group" style="position: relative;">
                <label for="edit_icon" class="form-label">Icon FontAwesome <span style="font-weight: 400; color: #64748b;">(Pilih rekomendasi / ketik nama ikon)</span></label>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div id="icon_preview_edit" class="facility-icon-badge" style="flex-shrink: 0; background: #e0e7ff; color: #4f46e5;">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <input type="text" id="edit_icon" name="icon" class="form-input" placeholder="Ketik wifi, ac, tv, kasur..." autocomplete="off">
                </div>

                <!-- Quick Suggestion Pills -->
                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.5rem;">
                    <span class="icon-quick-pill" onclick="selectIcon('fa-wifi', 'edit_icon', 'icon_preview_edit')"><i class="fa-solid fa-wifi"></i> Wi-Fi</span>
                    <span class="icon-quick-pill" onclick="selectIcon('fa-snowflake', 'edit_icon', 'icon_preview_edit')"><i class="fa-solid fa-snowflake"></i> AC</span>
                    <span class="icon-quick-pill" onclick="selectIcon('fa-tv', 'edit_icon', 'icon_preview_edit')"><i class="fa-solid fa-tv"></i> TV</span>
                    <span class="icon-quick-pill" onclick="selectIcon('fa-shower', 'edit_icon', 'icon_preview_edit')"><i class="fa-solid fa-shower"></i> Water Heater</span>
                    <span class="icon-quick-pill" onclick="selectIcon('fa-bed', 'edit_icon', 'icon_preview_edit')"><i class="fa-solid fa-bed"></i> Kasur</span>
                    <span class="icon-quick-pill" onclick="selectIcon('fa-utensils', 'edit_icon', 'icon_preview_edit')"><i class="fa-solid fa-utensils"></i> Dapur</span>
                </div>

                <!-- Live Search Dropdown Popup -->
                <div id="icon_suggestions_edit" class="icon-suggestion-dropdown"></div>
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
