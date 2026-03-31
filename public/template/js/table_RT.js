
(function() {
    'use strict';

    // DOM Elements
    const elements = {
        btnAdd: document.getElementById('rtrwBtnAdd'),
        addModal: document.getElementById('rtrwAddModal'),
        editModal: document.getElementById('rtrwEditModal'),
        closeAdd: document.getElementById('rtrwCloseAdd'),
        closeEdit: document.getElementById('rtrwCloseEdit'),
        cancelAdd: document.getElementById('rtrwCancelAdd'),
        cancelEdit: document.getElementById('rtrwCancelEdit'),
        editForm: document.getElementById('rtrwEditForm'),
        editNamaRT: document.getElementById('rtrwEditNamaRT'),
        editRW: document.getElementById('rtrwEditRW'),
        searchInput: document.getElementById('rtrwSearchInput'),
        tableBody: document.getElementById('rtrwTableBody')
    };

    // Open Add Modal
    function openAddModal() {
        elements.addModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close Add Modal
    function closeAddModal() {
        elements.addModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Open Edit Modal
    function openEditModal(id, namaRT, rw) {
        const baseUrl = "{{ route('superadmin.rt.update', ':id') }}";
        elements.editForm.action = baseUrl.replace(':id', id);
        elements.editNamaRT.value = namaRT;
        elements.editRW.value = rw;
        elements.editModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close Edit Modal
    function closeEditModal() {
        elements.editModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Search Functionality
    function handleSearch() {
        const searchTerm = elements.searchInput.value.toLowerCase();
        const rows = elements.tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData) {
                if (searchData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    // Confirm Delete
    function handleDeleteConfirm(e) {
        if (!confirm('⚠️ Apakah Anda yakin ingin menghapus data RT/RW ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
            e.preventDefault();
            return false;
        }
        return true;
    }

    // Event Listeners
    elements.btnAdd.addEventListener('click', openAddModal);
    elements.closeAdd.addEventListener('click', closeAddModal);
    elements.closeEdit.addEventListener('click', closeEditModal);
    elements.cancelAdd.addEventListener('click', closeAddModal);
    elements.cancelEdit.addEventListener('click', closeEditModal);
    elements.searchInput.addEventListener('input', handleSearch);

    // Edit buttons
    document.querySelectorAll('.rtrw-btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const namaRT = this.getAttribute('data-nama-rt');
            const rw = this.getAttribute('data-rw');
            openEditModal(id, namaRT, rw);
        });
    });

    // Delete forms
    document.querySelectorAll('.rtrw-delete-form').forEach(form => {
        form.addEventListener('submit', handleDeleteConfirm);
    });

    // Close modal when clicking outside
    elements.addModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddModal();
        }
    });

    elements.editModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
})();

