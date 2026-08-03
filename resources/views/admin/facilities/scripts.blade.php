<script>
    const fontAwesomeIconsList = [
        { class: 'fa-wifi', label: 'Wi-Fi Gratis / Internet', tags: 'wifi internet koneksi sinyal 100mbps jaringan' },
        { class: 'fa-snowflake', label: 'AC (Air Conditioner) / Pendingin', tags: 'ac snowflake dingin sejuk air conditioner' },
        { class: 'fa-tv', label: 'Smart TV / Televisi', tags: 'tv smarttv layar hiburan kabel netflix' },
        { class: 'fa-shower', label: 'Pemanas Air / Water Heater / Shower', tags: 'shower air hangat mandi pemanas water heater' },
        { class: 'fa-bath', label: 'Kamar Mandi / Bathtub', tags: 'bath bathtub kamar mandi toilet' },
        { class: 'fa-bed', label: 'Kasur / Tempat Tidur / Extra Bed', tags: 'bed kasur tempat tidur room kamar' },
        { class: 'fa-utensils', label: 'Dapur Mini & Alat Masak', tags: 'utensils dapur kitchen masak makan kulkas' },
        { class: 'fa-mug-hot', label: 'Sarapan Gratis / Kopi & Teh', tags: 'mug coffee kopi teh sarapan minum breakfast' },
        { class: 'fa-person-swimming', label: 'Kolam Renang', tags: 'swimming pool kolam renang berenang person-swimming' },
        { class: 'fa-square-parking', label: 'Parkir Luas & Safe', tags: 'parking parkir mobil motor garasi square-parking' },
        { class: 'fa-car', label: 'Antar Jemput Bandara / Mobil', tags: 'car mobil kendaraan jemput transport' },
        { class: 'fa-fan', label: 'Kipas Angin / Ventilasi', tags: 'fan kipas angin udara' },
        { class: 'fa-key', label: 'Akses Kunci Digital / Smart Lock', tags: 'key kunci pintu smartlock kebersihan' },
        { class: 'fa-shield-halved', label: 'Keamanan 24 Jam / CCTV', tags: 'shield cctv aman keamanan 24jam guard' },
        { class: 'fa-dumbbell', label: 'Fasilitas Gym & Kebugaran', tags: 'gym dumbbell olahraga fitness' },
        { class: 'fa-shirt', label: 'Layanan Laundry / Cuci', tags: 'shirt laundry cuci pakaian baju' },
        { class: 'fa-sun', label: 'Balkon / Teras Santai', tags: 'sun balkon teras pemandangan view' },
        { class: 'fa-soap', label: 'Sabun & Peralatan Mandi', tags: 'soap handuk alat mandi bersih' },
        { class: 'fa-fire-burner', label: 'Kompor / BBQ Grill Set', tags: 'bbq grill kompor panggang arang' },
        { class: 'fa-circle-check', label: 'Fasilitas Standar / Lengkap', tags: 'check circle-check centang gratis' }
    ];

    function selectIcon(iconClass, inputId, previewId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.value = iconClass;
        }
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.innerHTML = `<i class="fa-solid ${iconClass}"></i>`;
        }
        closeAllIconDropdowns();
    }

    function closeAllIconDropdowns() {
        document.querySelectorAll('.icon-suggestion-dropdown').forEach(d => {
            d.classList.remove('show');
            d.innerHTML = '';
        });
    }

    function attachIconAutocomplete(inputId, dropdownId, previewId) {
        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        const preview = document.getElementById(previewId);

        if (!input || !dropdown) return;

        function updatePreview(val) {
            if (!preview) return;
            const cleanVal = val.trim().replace(/^fa-solid\s+/, '');
            preview.innerHTML = `<i class="fa-solid ${cleanVal || 'fa-check-circle'}"></i>`;
        }

        input.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            updatePreview(query);

            if (!query) {
                dropdown.classList.remove('show');
                dropdown.innerHTML = '';
                return;
            }

            const matches = fontAwesomeIconsList.filter(item => {
                return item.class.toLowerCase().includes(query) ||
                       item.label.toLowerCase().includes(query) ||
                       item.tags.toLowerCase().includes(query);
            });

            if (matches.length > 0) {
                dropdown.innerHTML = matches.map(m => `
                    <div class="icon-suggest-item" onclick="selectIcon('${m.class}', '${inputId}', '${previewId}')">
                        <div class="icon-suggest-badge">
                            <i class="fa-solid ${m.class}"></i>
                        </div>
                        <div>
                            <div class="icon-suggest-class">${m.class}</div>
                            <div class="icon-suggest-label">${m.label}</div>
                        </div>
                    </div>
                `).join('');
                dropdown.classList.add('show');
            } else {
                dropdown.innerHTML = `
                    <div style="padding: 0.75rem; text-align: center; color: #94a3b8; font-size: 0.8rem; font-style: italic;">
                        Gunakan nama FontAwesome custom: <code>fa-${query.replace(/^fa-/, '')}</code>
                    </div>
                `;
                dropdown.classList.add('show');
            }
        });

        input.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                this.dispatchEvent(new Event('input'));
            }
        });
    }

    function openEditModal(id, name, icon, description) {
        const form = document.getElementById('editForm');
        form.action = `/admin/facilities/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_description').value = description;

        const preview = document.getElementById('icon_preview_edit');
        if(preview) {
            preview.innerHTML = `<i class="fa-solid ${icon || 'fa-check-circle'}"></i>`;
        }
        
        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
        closeAllIconDropdowns();
    }

    document.addEventListener('DOMContentLoaded', function() {
        attachIconAutocomplete('icon', 'icon_suggestions_create', 'icon_preview_create');
        attachIconAutocomplete('edit_icon', 'icon_suggestions_edit', 'icon_preview_edit');

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-group')) {
                closeAllIconDropdowns();
            }
        });
    });
</script>
