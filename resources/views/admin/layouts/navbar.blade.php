<style>
    /* Scoped Navbar Styles for Admin */
    .admin-navbar-wrapper {
        background: white;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        padding: 0;
        margin-bottom: 30px;
    }

    .admin-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: white;
    }

    .admin-topbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .admin-sidebar-toggle {
        background: none;
        border: none;
        font-size: 20px;
        color: #64748b;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .admin-sidebar-toggle:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .admin-search-box {
        position: relative;
        display: none;
    }

    .admin-search-input {
        width: 350px;
        padding: 10px 45px 10px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .admin-search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .admin-search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .admin-topbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-nav-item {
        position: relative;
    }

    .admin-nav-icon-btn {
        background: none;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .admin-nav-icon-btn:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .admin-badge-counter {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
    }

    .admin-divider {
        width: 1px;
        height: 30px;
        background: #e2e8f0;
    }

    .admin-user-menu {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .admin-user-menu:hover {
        background: #f1f5f9;
    }

    .admin-user-info {
        text-align: right;
    }

    .admin-user-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        display: block;
        line-height: 1.2;
    }

    .admin-user-role {
        font-size: 12px;
        color: #64748b;
        display: block;
    }

    .admin-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    /* Dropdown Styles */
    .admin-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        min-width: 280px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .admin-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .admin-dropdown-header {
        padding: 15px 20px;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    .admin-dropdown-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .admin-dropdown-item:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
        color: #3b82f6;
        text-decoration: none;
    }

    .admin-dropdown-item i {
        width: 20px;
        text-align: center;
        color: #94a3b8;
    }

    .admin-dropdown-item:hover i {
        color: #3b82f6;
    }

    .admin-dropdown-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 8px 0;
    }

    .admin-dropdown-footer {
        padding: 12px 20px;
        text-align: center;
    }

    .admin-dropdown-footer a {
        color: #3b82f6;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .admin-dropdown-footer a:hover {
        text-decoration: underline;
    }

    /* Notification Item */
    .admin-notif-item {
        padding: 15px 20px;
        display: flex;
        gap: 12px;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .admin-notif-item:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
    }

    .admin-notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .admin-notif-icon.primary {
        background: #dbeafe;
        color: #3b82f6;
    }

    .admin-notif-icon.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .admin-notif-icon.warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .admin-notif-content {
        flex: 1;
    }

    .admin-notif-date {
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .admin-notif-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.4;
    }

    /* Mobile Search */
    .admin-mobile-search {
        display: none;
    }

    /* Responsive */
    @media (min-width: 768px) {
        .admin-search-box {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .admin-topbar {
            padding: 12px 15px;
        }

        .admin-user-info {
            display: none;
        }

        .admin-mobile-search {
            display: block;
        }

        .admin-dropdown {
            min-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .admin-topbar-right {
            gap: 8px;
        }

        .admin-nav-icon-btn {
            width: 36px;
            height: 36px;
        }

        .admin-dropdown {
            right: -10px;
            min-width: 280px;
            max-width: calc(100vw - 30px);
        }
    }
</style>

<nav class="admin-navbar-wrapper">
    <div class="admin-topbar">
        <!-- Left Side -->
        <div class="admin-topbar-left">
            <!-- Sidebar Toggle -->
            <button id="adminSidebarToggle" class="admin-sidebar-toggle d-md-none">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search Box (Desktop) -->
            <div class="admin-search-box">
                <i class="fas fa-search admin-search-icon"></i>
                <input type="text"
                       class="admin-search-input"
                       placeholder="Cari anggota, kegiatan, atau transaksi...">
            </div>
        </div>

        <!-- Right Side -->
        <div class="admin-topbar-right">
            <!-- Mobile Search Icon -->
            <div class="admin-mobile-search admin-nav-item">
                <button class="admin-nav-icon-btn" id="adminMobileSearchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="admin-nav-item">
                <button class="admin-nav-icon-btn" id="adminNotifBtn">
                    <i class="fas fa-bell"></i>
                    <span class="admin-badge-counter">3</span>
                </button>

                <!-- Notifications Dropdown -->
                <div class="admin-dropdown" id="adminNotifDropdown">
                    <div class="admin-dropdown-header">
                        Notifikasi Terbaru
                    </div>
                    <div>
                        <div class="admin-notif-item">
                            <div class="admin-notif-icon primary">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="admin-notif-content">
                                <div class="admin-notif-date">Hari ini, 10:30</div>
                                <div class="admin-notif-text">Anggota baru telah bergabung dengan RT</div>
                            </div>
                        </div>

                        <div class="admin-notif-item">
                            <div class="admin-notif-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="admin-notif-content">
                                <div class="admin-notif-date">Kemarin, 15:45</div>
                                <div class="admin-notif-text">Pembayaran arisan bulan ini telah lunas</div>
                            </div>
                        </div>

                        <div class="admin-notif-item">
                            <div class="admin-notif-icon warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="admin-notif-content">
                                <div class="admin-notif-date">2 hari lalu</div>
                                <div class="admin-notif-text">Ada 5 anggota dengan denda tertunggak</div>
                            </div>
                        </div>
                    </div>
                    <div class="admin-dropdown-footer">
                        <a href="#">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="admin-divider d-none d-md-block"></div>

            <!-- User Menu -->
            <div class="admin-nav-item">
                <div class="admin-user-menu" id="adminUserMenuBtn">
                    <div class="admin-user-info">
                        <span class="admin-user-name">{{ Auth::user()->name }}</span>
                        <span class="admin-user-role">Admin RT</span>
                    </div>
                    <img class="admin-user-avatar"
                         src="{{ Auth::user()->image
                                 ? asset('storage/' . Auth::user()->image)
                                 : asset('template/img/undraw_profile.svg') }}"
                         alt="User Avatar">
                </div>

                <!-- User Dropdown -->
                <div class="admin-dropdown" id="adminUserDropdown">
                    <div class="admin-dropdown-header">
                        Akun Saya
                    </div>
                    <a class="admin-dropdown-item" href="{{ route('admin.profile.index') }}">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a class="admin-dropdown-item" href="{{ route('admin.setting_rt.index') }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan RT</span>
                    </a>
                    <div class="admin-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="admin-dropdown-item" style="width: 100%; border: none; background: none; text-align: left;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
(function() {
    'use strict';

    // DOM Elements
    const elements = {
        sidebarToggle: document.getElementById('adminSidebarToggle'),
        notifBtn: document.getElementById('adminNotifBtn'),
        notifDropdown: document.getElementById('adminNotifDropdown'),
        userMenuBtn: document.getElementById('adminUserMenuBtn'),
        userDropdown: document.getElementById('adminUserDropdown')
    };

    // Toggle Dropdown
    function toggleDropdown(dropdown, button) {
        const isActive = dropdown.classList.contains('active');

        // Close all dropdowns
        document.querySelectorAll('.admin-dropdown').forEach(d => {
            d.classList.remove('active');
        });

        // Open this dropdown if it was closed
        if (!isActive) {
            dropdown.classList.add('active');
        }
    }

    // Close dropdown when clicking outside
    function handleClickOutside(e) {
        if (!e.target.closest('.admin-nav-item')) {
            document.querySelectorAll('.admin-dropdown').forEach(d => {
                d.classList.remove('active');
            });
        }
    }

    // Event Listeners
    if (elements.notifBtn && elements.notifDropdown) {
        elements.notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(elements.notifDropdown, elements.notifBtn);
        });
    }

    if (elements.userMenuBtn && elements.userDropdown) {
        elements.userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(elements.userDropdown, elements.userMenuBtn);
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', handleClickOutside);

    // Sidebar Toggle (if you have sidebar)
    if (elements.sidebarToggle) {
        elements.sidebarToggle.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
            document.querySelector('.sidebar')?.classList.toggle('toggled');
        });
    }

    // Close dropdown on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.admin-dropdown').forEach(d => {
                d.classList.remove('active');
            });
        }
    });
})();
</script>
