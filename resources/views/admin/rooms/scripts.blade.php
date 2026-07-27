<script>
    function openCreateRoomModal() {
        document.getElementById('createRoomModal').classList.add('show');
    }

    function closeCreateRoomModal() {
        document.getElementById('createRoomModal').classList.remove('show');
    }

    function openEditModal(id, code, name, price, discount, selectedFacilityIds) {
        const form = document.getElementById('editForm');
        form.action = `/admin/rooms/${id}`;

        document.getElementById('edit_code').value = code;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_discount').value = discount > 0 ? discount : '';

        // Reset all facility checkboxes
        document.querySelectorAll('.edit-facility-checkbox').forEach(cb => {
            cb.checked = selectedFacilityIds.includes(parseInt(cb.value));
        });

        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function openPreviewModal(room, facilities, finalPrice) {
        document.getElementById('preview_code').innerText = room.code;
        document.getElementById('preview_name').innerText = room.name;
        document.getElementById('preview_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(room.price);
        document.getElementById('preview_discount').innerText = room.discount && room.discount > 0 ? room.discount + '%' : 'Tidak Ada';
        document.getElementById('preview_final_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice);

        // Render Photos
        const imgContainer = document.getElementById('preview_images_container');
        imgContainer.innerHTML = '';

        if (room.images && Array.isArray(room.images) && room.images.length > 0) {
            room.images.forEach(imgUrl => {
                const imgElement = document.createElement('img');
                imgElement.src = `/${imgUrl}`;
                imgElement.style.width = '100%';
                imgElement.style.height = '90px';
                imgElement.style.objectFit = 'cover';
                imgElement.style.borderRadius = '10px';
                imgElement.style.border = '1px solid #e2e8f0';
                imgContainer.appendChild(imgElement);
            });
        } else {
            imgContainer.innerHTML = `
                <div style="grid-column: 1 / -1; padding: 1.5rem; text-align: center; background-color: #f8fafc; border-radius: 12px; color: #94a3b8; font-size: 0.85rem; border: 1px dashed #cbd5e1;">
                    <i class="fa-solid fa-images" style="font-size: 1.75rem; margin-bottom: 0.5rem; display: block; color: #cbd5e1;"></i>
                    Belum ada foto kamar yang diunggah.
                </div>
            `;
        }

        // Render Facilities
        const facContainer = document.getElementById('preview_facilities_container');
        facContainer.innerHTML = '';

        if (facilities && facilities.length > 0) {
            facilities.forEach(f => {
                const badge = document.createElement('span');
                badge.className = 'facility-badge-pill';
                badge.style.padding = '0.35rem 0.65rem';
                badge.style.fontSize = '0.8rem';
                badge.innerHTML = `<i class="fa-solid ${f.icon || 'fa-check'}" style="color: #4f46e5; margin-right: 0.35rem;"></i> ${f.name}`;
                facContainer.appendChild(badge);
            });
        } else {
            facContainer.innerHTML = '<span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Belum ada fasilitas yang dipasang.</span>';
        }

        document.getElementById('previewModal').classList.add('show');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.remove('show');
    }
</script>
