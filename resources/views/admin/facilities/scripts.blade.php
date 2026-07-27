<script>
    function openEditModal(id, name, icon, description) {
        const form = document.getElementById('editForm');
        form.action = `/admin/facilities/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_description').value = description;
        
        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
</script>
