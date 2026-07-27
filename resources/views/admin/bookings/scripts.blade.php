<script>
    function openEditModal(booking, room) {
        const form = document.getElementById('editForm');
        form.action = `/admin/bookings/${booking.id}`;

        document.getElementById('edit_status').value = booking.status;
        document.getElementById('edit_room_id').value = booking.room_id;
        document.getElementById('edit_customer_name').value = booking.customer_name;
        document.getElementById('edit_customer_phone').value = booking.customer_phone;
        document.getElementById('edit_customer_sosmed').value = booking.customer_sosmed ?? '';
        document.getElementById('edit_customer_address').value = booking.customer_address;
        
        // Format dates YYYY-MM-DD
        const checkIn = booking.check_in_date.split('T')[0];
        const checkOut = booking.check_out_date.split('T')[0];
        
        document.getElementById('edit_check_in_date').value = checkIn;
        document.getElementById('edit_check_out_date').value = checkOut;

        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function openPreviewModal(booking, room) {
        document.getElementById('preview_booking_code').innerText = booking.booking_code;
        document.getElementById('preview_customer_name').innerText = booking.customer_name;
        document.getElementById('preview_customer_phone').innerText = booking.customer_phone;
        document.getElementById('preview_customer_sosmed').innerText = booking.customer_sosmed ? booking.customer_sosmed : '-';
        document.getElementById('preview_customer_address').innerText = booking.customer_address;
        document.getElementById('preview_room_name').innerText = room ? `${room.code} - ${room.name}` : '-';

        // WA Direct Link
        const cleanPhone = booking.customer_phone.replace(/[^0-9]/g, '');
        const formattedPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
        const waText = encodeURIComponent(`Halo Kak ${booking.customer_name}, mengenai pemesanan Kamar ${room ? room.name : ''} (${booking.booking_code})...`);
        document.getElementById('preview_wa_link').href = `https://wa.me/${formattedPhone}?text=${waText}`;

        // Format dates
        const checkInDate = new Date(booking.check_in_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        const checkOutDate = new Date(booking.check_out_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        
        document.getElementById('preview_check_in').innerText = checkInDate;
        document.getElementById('preview_check_out').innerText = checkOutDate;
        document.getElementById('preview_total_nights').innerText = `${booking.total_nights} Malam`;

        document.getElementById('preview_room_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(booking.room_price);
        document.getElementById('preview_discount_percent').innerText = booking.discount && booking.discount > 0 ? `${booking.discount}% OFF` : 'Tidak Ada';
        document.getElementById('preview_total_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(booking.total_price);

        // Render Status Badge
        const statusBadge = document.getElementById('preview_status_badge');
        if (booking.status === 2) {
            statusBadge.innerHTML = '<span style="background-color: #dcfce7; color: #166534; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-circle-check"></i> Lunas / Terkonfirmasi</span>';
        } else if (booking.status === 1) {
            statusBadge.innerHTML = '<span style="background-color: #fef3c7; color: #b45309; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-clock"></i> Pending</span>';
        } else {
            statusBadge.innerHTML = '<span style="background-color: #fee2e2; color: #991b1b; padding: 0.25rem 0.65rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-circle-xmark"></i> Dibatalkan / Expired</span>';
        }

        document.getElementById('previewModal').classList.add('show');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.remove('show');
    }
</script>
