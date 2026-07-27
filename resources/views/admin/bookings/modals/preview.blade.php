<!-- Modal Preview Detail Pemesanan -->
<div class="modal-backdrop" id="previewModal">
    <div class="modal-card" style="max-width: 620px;">
        <div class="modal-header">
            <div>
                <span class="badge-code" id="preview_booking_code">CODE123</span>
                <h3 class="modal-title" style="margin-top: 0.25rem;">Detail Pemesanan Kamar</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closePreviewModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Customer & Booking Overview -->
        <div style="background-color: #f8fafc; padding: 1.25rem; border-radius: 14px; margin-bottom: 1.25rem; border: 1px solid #e2e8f0;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.85rem;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">NAMA PEMESAN</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-top: 0.15rem;" id="preview_customer_name">-</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">NO. HP / WHATSAPP</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-top: 0.15rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span id="preview_customer_phone">-</span>
                        <a id="preview_wa_link" href="#" target="_blank" style="background-color: #25d366; color: white; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.75rem; text-decoration: none; font-weight: 600;">
                            <i class="fa-brands fa-whatsapp"></i> Chat WA
                        </a>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.85rem;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">MEDIA SOSIAL</div>
                    <div style="font-size: 0.9rem; font-weight: 600; color: #4338ca; margin-top: 0.15rem;" id="preview_customer_sosmed">-</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">KAMAR DIPESAN</div>
                    <div style="font-size: 0.9rem; font-weight: 700; color: #0f172a; margin-top: 0.15rem;" id="preview_room_name">-</div>
                </div>
            </div>

            <div style="margin-bottom: 0.85rem;">
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">ALAMAT PEMESAN</div>
                <div style="font-size: 0.875rem; color: #334155; margin-top: 0.15rem;" id="preview_customer_address">-</div>
            </div>

            <div>
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">STATUS PEMESANAN</div>
                <div style="margin-top: 0.25rem;" id="preview_status_badge">-</div>
            </div>
        </div>

        <!-- Dates & Price Breakdown -->
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.85rem; background-color: #e0e7ff; padding: 1.1rem; border-radius: 12px; margin-bottom: 1.25rem;">
            <div>
                <div style="font-size: 0.72rem; color: #4338ca; font-weight: 700; text-transform: uppercase;">CHECK-IN</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #1e1b4b; margin-top: 0.15rem;" id="preview_check_in">-</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #4338ca; font-weight: 700; text-transform: uppercase;">CHECK-OUT</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #1e1b4b; margin-top: 0.15rem;" id="preview_check_out">-</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #4338ca; font-weight: 700; text-transform: uppercase;">DURASI</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #1e1b4b; margin-top: 0.15rem;" id="preview_total_nights">1 Malam</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.85rem; background-color: #f8fafc; padding: 1.1rem; border-radius: 12px; margin-bottom: 1.25rem; border: 1px solid #e2e8f0;">
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">HARGA KAMAR</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 0.15rem;" id="preview_room_price">Rp 0</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">DISKON KAMAR</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #dc2626; margin-top: 0.15rem;" id="preview_discount_percent">Tidak Ada</div>
            </div>
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase;">TOTAL BIAYA</div>
                <div style="font-size: 1.05rem; font-weight: 800; color: #16a34a; margin-top: 0.15rem;" id="preview_total_price">Rp 0</div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
            <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closePreviewModal()">
                Tutup Preview
            </button>
        </div>
    </div>
</div>
