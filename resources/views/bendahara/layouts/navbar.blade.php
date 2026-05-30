<style>
    /* Scoped Navbar Styles for Admin */
    .bendahara-navbar-wrapper {
        background: white;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        padding: 0;
        margin-bottom: 30px;
    }

    .bendahara-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: white;
    }

    .bendahara-topbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .bendahara-sidebar-toggle {
        background: none;
        border: none;
        font-size: 20px;
        color: #64748b;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .bendahara-sidebar-toggle:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .bendahara-search-box {
        position: relative;
        display: none;
    }

    .bendahara-search-input {
        width: 350px;
        padding: 10px 45px 10px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .bendahara-search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .bendahara-search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .bendahara-topbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .bendahara-nav-item {
        position: relative;
    }

    .bendahara-nav-icon-btn {
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

    .bendahara-nav-icon-btn:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .bendahara-badge-counter {
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

    .bendahara-divider {
        width: 1px;
        height: 30px;
        background: #e2e8f0;
    }

    .bendahara-user-menu {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .bendahara-user-menu:hover {
        background: #f1f5f9;
    }

    .bendahara-user-info {
        text-align: right;
    }

    .bendahara-user-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        display: block;
        line-height: 1.2;
    }

    .bendahara-user-role {
        font-size: 12px;
        color: #64748b;
        display: block;
    }

    .bendahara-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    /* Dropdown Styles */
    .bendahara-dropdown {
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

    .bendahara-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .bendahara-dropdown-header {
        padding: 15px 20px;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    .bendahara-dropdown-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .bendahara-dropdown-item:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
        color: #3b82f6;
        text-decoration: none;
    }

    .bendahara-dropdown-item i {
        width: 20px;
        text-align: center;
        color: #94a3b8;
    }

    .bendahara-dropdown-item:hover i {
        color: #3b82f6;
    }

    .bendahara-dropdown-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 8px 0;
    }

    .bendahara-dropdown-footer {
        padding: 12px 20px;
        text-align: center;
    }

    .bendahara-dropdown-footer a {
        color: #3b82f6;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .bendahara-dropdown-footer a:hover {
        text-decoration: underline;
    }

    /* Notification Item */
    .bendahara-notif-item {
        padding: 15px 20px;
        display: flex;
        gap: 12px;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .bendahara-notif-item:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
    }

    .bendahara-notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bendahara-notif-icon.primary {
        background: #dbeafe;
        color: #3b82f6;
    }

    .bendahara-notif-icon.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .bendahara-notif-icon.warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .bendahara-notif-content {
        flex: 1;
    }

    .bendahara-notif-date {
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .bendahara-notif-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.4;
    }

    /* Mobile Search */
    .bendahara-mobile-search {
        display: none;
    }

    /* Responsive */
    @media (min-width: 768px) {
        .bendahara-search-box {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .bendahara-topbar {
            padding: 12px 15px;
        }

        .bendahara-user-info {
            display: none;
        }

        .bendahara-mobile-search {
            display: block;
        }

        .bendahara-dropdown {
            min-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .bendahara-topbar-right {
            gap: 8px;
        }

        .bendahara-nav-icon-btn {
            width: 36px;
            height: 36px;
        }

        .bendahara-dropdown {
            right: -10px;
            min-width: 280px;
            max-width: calc(100vw - 30px);
        }
    }
</style>

<nav class="bendahara-navbar-wrapper">
    <div class="bendahara-topbar">
        <!-- Left Side -->
        <div class="bendahara-topbar-left">
            <!-- Sidebar Toggle -->
            <button id="bendaharaSidebarToggle" class="bendahara-sidebar-toggle d-md-none">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search Box (Desktop) -->
            <div class="bendahara-search-box">
                <i class="fas fa-search bendahara-search-icon"></i>
                <input type="text"
                       class="bendahara-search-input"
                       placeholder="Cari anggota, kegiatan, atau transaksi...">
            </div>
        </div>

        <!-- Right Side -->
        <div class="bendahara-topbar-right">
            <!-- Mobile Search Icon -->
            <div class="bendahara-mobile-search bendahara-nav-item">
                <button class="bendahara-nav-icon-btn" id="bendaharaMobileSearchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="bendahara-nav-item">
                <button class="bendahara-nav-icon-btn" id="bendaharaNotifBtn">
                    <i class="fas fa-bell"></i>
                    @if(count($notifications) > 0)
                    <span class="bendahara-badge-counter">{{ count($notifications) }}</span>
                    @endif
                </button>

                <!-- Notifications Dropdown -->
                <div class="bendahara-dropdown" id="bendaharaNotifDropdown">
                    <div class="bendahara-dropdown-header">
                        Notifikasi Terbaru
                    </div>
                    <div>
                        @forelse($notifications as $notif)
                        <div class="bendahara-notif-item">
                            <div class="bendahara-notif-icon {{ $notif->icon_bg }}">
                                <i class="{{ $notif->icon }}"></i>
                            </div>
                            <div class="bendahara-notif-content">
                                <div class="bendahara-notif-date">{{ $notif->date }}</div>
                                <div class="bendahara-notif-text">{{ $notif->text }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="bendahara-notif-item justify-content-center text-muted" style="padding: 15px; text-align: center;">
                            Belum ada notifikasi
                        </div>
                        @endforelse
                    </div>
                    <div class="bendahara-dropdown-footer">
                        <a href="#">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="bendahara-divider d-none d-md-block"></div>

            <!-- User Menu -->
            <div class="bendahara-nav-item">
                <div class="bendahara-user-menu" id="bendaharaUserMenuBtn">
                    <div class="bendahara-user-info">
                        <span class="bendahara-user-name">{{ Auth::user()->name }}</span>
                        <span class="bendahara-user-role">Bendahara</span>
                    </div>
                    <img class="bendahara-user-avatar"
                         src="{{ Auth::user()->image
                                 ? asset('storage/' . Auth::user()->image)
                                 : asset('template/img/undraw_profile.svg') }}"
                         alt="User Avatar">
                </div>

                <!-- User Dropdown -->
                <div class="bendahara-dropdown" id="bendaharaUserDropdown">
                    <div class="bendahara-dropdown-header">
                        Akun Saya
                    </div>
                    <a class="bendahara-dropdown-item" href="{{ route('bendahara.profile.index') }}">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                    
                    <div class="bendahara-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="bendahara-dropdown-item" style="width: 100%; border: none; background: none; text-align: left;">
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
        sidebarToggle: document.getElementById('bendaharaSidebarToggle'),
        notifBtn: document.getElementById('bendaharaNotifBtn'),
        notifDropdown: document.getElementById('bendaharaNotifDropdown'),
        userMenuBtn: document.getElementById('bendaharaUserMenuBtn'),
        userDropdown: document.getElementById('bendaharaUserDropdown')
    };

    // Toggle Dropdown
    function toggleDropdown(dropdown, button) {
        const isActive = dropdown.classList.contains('active');

        // Close all dropdowns
        document.querySelectorAll('.bendahara-dropdown').forEach(d => {
            d.classList.remove('active');
        });

        // Open this dropdown if it was closed
        if (!isActive) {
            dropdown.classList.add('active');
        }
    }

    // Close dropdown when clicking outside
    function handleClickOutside(e) {
        if (!e.target.closest('.bendahara-nav-item')) {
            document.querySelectorAll('.bendahara-dropdown').forEach(d => {
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
            document.querySelectorAll('.bendahara-dropdown').forEach(d => {
                d.classList.remove('active');
            });
        }
    });
})();
</script>
