<script>
    function openCreateExtraFacilityModal() {
        document.getElementById('createExtraFacilityModal').classList.add('show');
    }

    function closeCreateExtraFacilityModal() {
        document.getElementById('createExtraFacilityModal').classList.remove('show');
    }

    function openEditExtraFacilityModal(id, name, price, description) {
        const form = document.getElementById('editExtraFacilityForm');
        form.action = `/admin/extra-facilities/${id}`;

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_description').value = description ?? '';

        document.getElementById('editExtraFacilityModal').classList.add('show');
    }

    function closeEditExtraFacilityModal() {
        document.getElementById('editExtraFacilityModal').classList.remove('show');
    }
</script>
