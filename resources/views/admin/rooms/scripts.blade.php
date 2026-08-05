<script>
    function openCreateRoomModal() {
        document.getElementById('createRoomModal').classList.add('show');
    }

    function closeCreateRoomModal() {
        document.getElementById('createRoomModal').classList.remove('show');
    }

    function openEditModal(id, code, name, price, weekendPrice, discount, description, selectedFacilityIds, roomImages) {
        const form = document.getElementById('editForm');
        form.action = `/admin/rooms/${id}`;

        document.getElementById('edit_code').value = code;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_weekend_price').value = weekendPrice > 0 ? weekendPrice : '';
        document.getElementById('edit_discount').value = discount > 0 ? discount : '';
        document.getElementById('edit_description').value = description || '';

        // Reset all facility checkboxes
        document.querySelectorAll('.edit-facility-checkbox').forEach(cb => {
            cb.checked = selectedFacilityIds.includes(parseInt(cb.value));
        });

        // Reset deleted images inputs & new file input
        const deletedContainer = document.getElementById('edit_deleted_inputs_container');
        if(deletedContainer) deletedContainer.innerHTML = '';
        const editImagesInput = document.getElementById('edit_images');
        if(editImagesInput) editImagesInput.value = '';

        // Render Existing Room Photos with Delete Buttons
        renderEditExistingImages(roomImages || []);

        document.getElementById('editModal').classList.add('show');
    }

    function renderEditExistingImages(images) {
        const container = document.getElementById('edit_existing_images_container');
        if (!container) return;
        container.innerHTML = '';

        if (images && Array.isArray(images) && images.length > 0) {
            images.forEach((imgPath, index) => {
                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';
                wrapper.style.borderRadius = '10px';
                wrapper.style.overflow = 'hidden';
                wrapper.style.border = '1px solid #cbd5e1';
                wrapper.style.height = '85px';

                wrapper.innerHTML = `
                    <div style="width: 100%; height: 100%; position: relative;">
                        <img src="/${imgPath}" alt="Foto ${index + 1}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                        <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.72rem; text-align: center; padding: 4px; background: #f1f5f9; flex-direction: column;">
                            <i class="fa-solid fa-image-slash" style="font-size: 1rem; margin-bottom: 0.2rem; color: #cbd5e1;"></i>
                            File Rusak
                        </div>
                    </div>
                    <button type="button" onclick="markImageForDeletion('${imgPath}', this)" 
                            style="position: absolute; top: 4px; right: 4px; width: 24px; height: 24px; border-radius: 50%; background: #ef4444; color: white; border: none; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.35); z-index: 10; transition: transform 0.15s ease;"
                            onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"
                            title="Hapus Foto Ini">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `;
                container.appendChild(wrapper);
            });
        } else {
            container.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; color: #94a3b8; font-size: 0.8rem; font-style: italic; padding: 0.5rem 0;">
                    Belum ada foto kamar yang diunggah.
                </div>
            `;
        }
    }

    function markImageForDeletion(imgPath, btnElem) {
        Swal.fire({
            title: 'Hapus Foto Ini?',
            text: 'Foto ini akan dihapus saat Anda menyimpan perubahan.',
            icon: 'warning',
            showCancelButton: true,
            confirmColor: '#ef4444',
            cancelColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Append hidden input for deleted image
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_images[]';
                hiddenInput.value = imgPath;
                document.getElementById('edit_deleted_inputs_container').appendChild(hiddenInput);

                // Remove wrapper element from DOM
                const wrapper = btnElem.closest('div');
                if (wrapper) {
                    wrapper.remove();
                }

                // Check if container is empty
                const container = document.getElementById('edit_existing_images_container');
                if (container && container.querySelectorAll('div').length === 0) {
                    container.innerHTML = `
                        <div style="grid-column: 1 / -1; text-align: center; color: #94a3b8; font-size: 0.8rem; font-style: italic; padding: 0.5rem 0;">
                            Belum ada foto kamar yang diunggah.
                        </div>
                    `;
                }
            }
        });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function openPreviewModal(room, facilities, finalPrice) {
        document.getElementById('preview_code').innerText = room.code;
        document.getElementById('preview_name').innerText = room.name;
        document.getElementById('preview_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(room.price);
        document.getElementById('preview_weekend_price').innerText = room.weekend_price && room.weekend_price > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(room.weekend_price) : 'Sama';
        document.getElementById('preview_discount').innerText = room.discount && room.discount > 0 ? room.discount + '%' : 'Tidak Ada';
        document.getElementById('preview_final_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice);

        const descElem = document.getElementById('preview_description');
        if (descElem) {
            descElem.innerText = room.description && room.description.trim() !== '' ? room.description : 'Belum ada keterangan untuk kamar ini.';
        }

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
