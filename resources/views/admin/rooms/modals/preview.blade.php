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
                <!-- Dynamic photos loaded via JS -->
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 0.75rem; background-color: #f8fafc; padding: 1.1rem; border-radius: 12px; margin-bottom: 1.25rem; border: 1px solid #e2e8f0;">
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Harga Weekday</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 0.2rem;" id="preview_price">Rp 0</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Harga Weekend</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #4338ca; margin-top: 0.2rem;" id="preview_weekend_price">Rp 0</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Diskon</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #dc2626; margin-top: 0.2rem;" id="preview_discount">0%</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Nett Weekday</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #16a34a; margin-top: 0.2rem;" id="preview_final_price">Rp 0</div>
            </div>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label class="form-label">Keterangan / Deskripsi Kamar</label>
            <div id="preview_description" style="font-size: 0.875rem; color: #334155; background: #f8fafc; padding: 0.85rem; border-radius: 10px; border: 1px solid #e2e8f0; line-height: 1.5; white-space: pre-line;">
                <!-- Loaded via JS -->
            </div>
        </div>

        <div>
            <label class="form-label">Fasilitas Terpasang</label>
            <div id="preview_facilities_container" style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem;">
                <!-- Dynamic facilities loaded via JS -->
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1.75rem;">
            <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closePreviewModal()">
                Tutup Preview
            </button>
        </div>
    </div>
</div>
