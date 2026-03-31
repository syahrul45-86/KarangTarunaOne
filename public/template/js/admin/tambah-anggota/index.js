/**
 * Anggota RT Module
 * Mengelola fungsi-fungsi untuk halaman Anggota RT
 * Menggunakan IIFE untuk menghindari polusi global scope
 */

const AnggotaRTModule = (function() {
    'use strict';

    // Private variables
    const selectors = {
        searchInput: '#anggotaSearchInput',
        tableBody: '#anggotaTableBody',
        modal: '#anggotaAddModal',
        modalClass: '.anggota-modal',
        emptyState: '.anggota-empty-state',
        actionButtons: '[data-anggota-action]'
    };

    /**
     * Initialize search filter functionality
     */
    function initSearchFilter() {
        const searchInput = document.querySelector(selectors.searchInput);
        const tableBody = document.querySelector(selectors.tableBody);

        if (!searchInput || !tableBody) return;

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(function(row) {
                // Skip empty state row
                if (row.querySelector(selectors.emptyState)) {
                    return;
                }

                const text = row.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                row.style.display = shouldShow ? '' : 'none';
            });
        });
    }

    /**
     * Open modal
     */
    function openModal(modalId = 'anggotaAddModal') {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Close modal
     */
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * Handle click outside modal to close
     */
    function initModalClickOutside() {
        const modals = document.querySelectorAll(selectors.modalClass);

        modals.forEach(function(modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });
    }

    /**
     * Handle ESC key to close modals
     */
    function initEscapeKeyHandler() {
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll(selectors.modalClass + '[style*="display: block"]');
                openModals.forEach(function(modal) {
                    closeModal(modal.id);
                });
            }
        });
    }

    /**
     * Initialize action buttons using data attributes
     */
    function initActionButtons() {
        const actionButtons = document.querySelectorAll(selectors.actionButtons);

        actionButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                const action = this.getAttribute('data-anggota-action');
                const modalId = this.getAttribute('data-modal-id');

                if (action === 'open-modal') {
                    e.preventDefault();
                    openModal(modalId || 'anggotaAddModal');
                } else if (action === 'close-modal') {
                    e.preventDefault();
                    closeModal(modalId);
                }
            });
        });
    }

    /**
     * Check for validation errors and open modal if needed
     */
    function checkForErrors() {
        if (window.anggotaRTData && window.anggotaRTData.hasErrors) {
            openModal('anggotaAddModal');
        }
    }

    /**
     * Initialize all functionality
     */
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initSearchFilter();
                initModalClickOutside();
                initEscapeKeyHandler();
                initActionButtons();
                checkForErrors();
            });
        } else {
            // DOM is already ready
            initSearchFilter();
            initModalClickOutside();
            initEscapeKeyHandler();
            initActionButtons();
            checkForErrors();
        }
    }

    // Public API - only expose what's necessary
    return {
        init: init,
        openModal: openModal,
        closeModal: closeModal
    };
})();

// Auto-initialize the module
AnggotaRTModule.init();
