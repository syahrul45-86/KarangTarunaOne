
(function() {
    'use strict';

    // DOM Elements
    const elements = {
        btnAdd: document.getElementById('adminrtBtnAdd'),
        addModal: document.getElementById('adminrtAddModal'),
        closeAdd: document.getElementById('adminrtCloseAdd'),
        cancelAdd: document.getElementById('adminrtCancelAdd'),
        searchInput: document.getElementById('adminrtSearchInput'),
        tableBody: document.getElementById('adminrtTableBody'),
        passwordToggles: document.querySelectorAll('.adminrt-password-toggle')
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
        // Reset form
        document.getElementById('adminrtAddForm').reset();
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

    // Toggle Password Visibility
    function togglePassword(button) {
        const targetId = button.getAttribute('data-target');
        const input = document.getElementById(targetId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
        } else {
            input.type = 'password';
            button.textContent = '👁️';
        }
    }

    // Confirm Delete
    function handleDeleteConfirm(e) {
        if (!confirm('⚠️ Apakah Anda yakin ingin menghapus admin RT ini?\n\nAdmin yang dihapus tidak dapat dikembalikan dan RT-nya akan menjadi tidak memiliki admin.')) {
            e.preventDefault();
            return false;
        }
        return true;
    }

    // Validate Password Match
    function validatePasswordMatch() {
        const password = document.getElementById('adminrtPassword');
        const confirm = document.getElementById('adminrtPasswordConfirm');

        if (confirm.value && password.value !== confirm.value) {
            confirm.setCustomValidity('Password tidak cocok!');
        } else {
            confirm.setCustomValidity('');
        }
    }

    // Event Listeners
    if (elements.btnAdd) {
        elements.btnAdd.addEventListener('click', openAddModal);
    }

    if (elements.closeAdd) {
        elements.closeAdd.addEventListener('click', closeAddModal);
    }

    if (elements.cancelAdd) {
        elements.cancelAdd.addEventListener('click', closeAddModal);
    }

    if (elements.searchInput) {
        elements.searchInput.addEventListener('input', handleSearch);
    }

    // Password toggle buttons
    elements.passwordToggles.forEach(button => {
        button.addEventListener('click', function() {
            togglePassword(this);
        });
    });

    // Password validation
    const passwordInput = document.getElementById('adminrtPassword');
    const confirmInput = document.getElementById('adminrtPasswordConfirm');

    if (passwordInput && confirmInput) {
        confirmInput.addEventListener('input', validatePasswordMatch);
        passwordInput.addEventListener('input', validatePasswordMatch);
    }

    // Delete forms
    document.querySelectorAll('.adminrt-delete-form').forEach(form => {
        form.addEventListener('submit', handleDeleteConfirm);
    });

    // Close modal when clicking outside
    if (elements.addModal) {
        elements.addModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
        }
    });
})();

