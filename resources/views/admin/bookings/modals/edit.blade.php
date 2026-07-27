<!-- Modal Edit Pemesanan -->
<div class="modal-backdrop" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Edit Data Pemesanan</h3>
            <button type="button" class="btn-close-modal" onclick="closeEditModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editForm" method="POST" onsubmit="return confirmSubmit(this, 'Anda Yakin Akan Merubah Status Booking ini?', 'Konfirmasi Perubahan Status');">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="edit_status" class="form-label">Status Pemesanan</label>
                <select id="edit_status" name="status" class="form-select" required>
                    <option value="1">🟡 Pending (Menunggu Pembayaran / WA)</option>
                    <option value="4">🟣 DP 50% (Panjar 50% - Terkonfirmasi)</option>
                    <option value="2">🟢 Lunas (Terkonfirmasi - Berhasil)</option>
                    <option value="3">🔵 Selesai (Completed - Check-Out)</option>
                    <option value="0">🔴 Dibatalkan / Expired (Kamar Bebas)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="edit_room_id" class="form-label">Pilih Kamar / Unit</label>
                <select id="edit_room_id" name="room_id" class="form-select" required>
                    <option value="">-- Pilih Kamar --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}">{{ $r->code }} - {{ $r->name }} (Rp {{ number_format($r->price, 0, ',', '.') }}{{ $r->discount ? ' - Diskon ' . number_format($r->discount, 0) . '%' : '' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="edit_customer_name" class="form-label">Nama Pemesan</label>
                <input type="text" id="edit_customer_name" name="customer_name" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_customer_phone" class="form-label">No. HP / WhatsApp</label>
                <input type="text" id="edit_customer_phone" name="customer_phone" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="edit_customer_sosmed" class="form-label">Media Sosial Pemesan <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <input type="text" id="edit_customer_sosmed" name="customer_sosmed" class="form-input">
            </div>

            <div class="form-group">
                <label for="edit_customer_address" class="form-label">Alamat Pemesan</label>
                <textarea id="edit_customer_address" name="customer_address" rows="2" class="form-textarea" required></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="edit_check_in_date" class="form-label">Tanggal Check-In</label>
                    <input type="date" id="edit_check_in_date" name="check_in_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="edit_check_out_date" class="form-label">Tanggal Check-Out</label>
                    <input type="date" id="edit_check_out_date" name="check_out_date" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Extra Fasilitas <span style="font-weight: 400; color: #64748b;">(Pilih Banyak / Opsional)</span></label>
                <div class="facility-checkbox-grid">
                    @foreach($extraFacilities as $ef)
                    <label class="facility-checkbox-item">
                        <input type="checkbox" class="edit-extra-facility-checkbox" name="extra_facility_ids[]" value="{{ $ef->id }}">
                        <span>{{ $ef->name }} (+Rp {{ number_format($ef->price, 0, ',', '.') }})</span>
                    </label>
                    @endforeach
                </div>
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
