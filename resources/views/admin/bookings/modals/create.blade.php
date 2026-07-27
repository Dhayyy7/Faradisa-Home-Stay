<!-- Modal Input Pemesanan Baru -->
<div class="modal-backdrop" id="createBookingModal">
    <div class="modal-card" style="max-width: 580px;">
        <div class="modal-header">
            <h3 class="modal-title">Input Pemesanan Baru</h3>
            <button type="button" class="btn-close-modal" onclick="closeCreateBookingModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="room_id" class="form-label">Pilih Kamar / Unit</label>
                <select id="room_id" name="room_id" class="form-select" required>
                    <option value="">-- Pilih Kamar --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ old('room_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->code }} - {{ $r->name }} (Rp {{ number_format($r->price, 0, ',', '.') }}{{ $r->discount ? ' - Diskon ' . number_format($r->discount, 0) . '%' : '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="customer_name" class="form-label">Nama Pemesan</label>
                <input type="text" id="customer_name" name="customer_name" class="form-input" placeholder="Masukan Nama Pemesan" value="{{ old('customer_name') }}" required>
            </div>

            <div class="form-group">
                <label for="customer_phone" class="form-label">No. HP / WhatsApp</label>
                <input type="text" id="customer_phone" name="customer_phone" class="form-input" placeholder="Misal: 081234567890" value="{{ old('customer_phone') }}" required>
            </div>

            <div class="form-group">
                <label for="customer_sosmed" class="form-label">Media Sosial Pemesan <span style="font-weight: 400; color: #64748b;">(Opsional)</span></label>
                <input type="text" id="customer_sosmed" name="customer_sosmed" class="form-input" placeholder="Misal: @username (IG/FB/TikTok)" value="{{ old('customer_sosmed') }}">
            </div>

            <div class="form-group">
                <label for="customer_address" class="form-label">Alamat Pemesan</label>
                <textarea id="customer_address" name="customer_address" rows="2" class="form-textarea" placeholder="Masukan Alamat Lengkap Pemesan" required>{{ old('customer_address') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <div class="form-group">
                    <label for="check_in_date" class="form-label">Check-In</label>
                    <input type="date" id="check_in_date" name="check_in_date" class="form-input" value="{{ old('check_in_date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label for="check_out_date" class="form-label">Check-Out</label>
                    <input type="date" id="check_out_date" name="check_out_date" class="form-input" value="{{ old('check_out_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Extra Fasilitas <span style="font-weight: 400; color: #64748b;">(Pilih Banyak / Opsional)</span></label>
                <div class="facility-checkbox-grid">
                    @foreach($extraFacilities as $ef)
                    <label class="facility-checkbox-item">
                        <input type="checkbox" name="extra_facility_ids[]" value="{{ $ef->id }}">
                        <span>{{ $ef->name }} (+Rp {{ number_format($ef->price, 0, ',', '.') }})</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status Awal Pemesanan</label>
                <select id="status" name="status" class="form-select">
                    <option value="1" selected>🟡 Pending (Menunggu Bayar WA - Kunci 2 Jam)</option>
                    <option value="4">🟣 DP 50% (Panjar 50% - Terkonfirmasi Kunci)</option>
                    <option value="2">🟢 Lunas (Terkonfirmasi - Langsung Kunci)</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn-edit" style="background-color: #f1f5f9; color: #475569; width: auto; padding: 0.6rem 1.25rem;" onclick="closeCreateBookingModal()">
                    Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-save"></i>
                    <span>Simpan Pemesanan</span>
                </button>
            </div>
        </form>
    </div>
</div>
