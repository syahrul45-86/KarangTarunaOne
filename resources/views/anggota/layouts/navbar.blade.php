<style>
    /* Scoped Navbar Styles for Admin */
    .anggota-navbar-wrapper {
        background: white;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        padding: 0;
        margin-bottom: 30px;
    }

    .anggota-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: white;
    }

    .anggota-topbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .anggota-sidebar-toggle {
        background: none;
        border: none;
        font-size: 20px;
        color: #64748b;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .anggota-sidebar-toggle:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .anggota-search-box {
        position: relative;
        display: none;
    }

    .anggota-search-input {
        width: 350px;
        padding: 10px 45px 10px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .anggota-search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .anggota-search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .anggota-topbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .anggota-nav-item {
        position: relative;
    }

    .anggota-nav-icon-btn {
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

    .anggota-nav-icon-btn:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .anggota-badge-counter {
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

    .anggota-divider {
        width: 1px;
        height: 30px;
        background: #e2e8f0;
    }

    .anggota-user-menu {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .anggota-user-menu:hover {
        background: #f1f5f9;
    }

    .anggota-user-info {
        text-align: right;
    }

    .anggota-user-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        display: block;
        line-height: 1.2;
    }

    .anggota-user-role {
        font-size: 12px;
        color: #64748b;
        display: block;
    }

    .anggota-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    /* Dropdown Styles */
    .anggota-dropdown {
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

    .anggota-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .anggota-dropdown-header {
        padding: 15px 20px;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    .anggota-dropdown-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .anggota-dropdown-item:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
        color: #3b82f6;
        text-decoration: none;
    }

    .anggota-dropdown-item i {
        width: 20px;
        text-align: center;
        color: #94a3b8;
    }

    .anggota-dropdown-item:hover i {
        color: #3b82f6;
    }

    .anggota-dropdown-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 8px 0;
    }

    .anggota-dropdown-footer {
        padding: 12px 20px;
        text-align: center;
    }

    .anggota-dropdown-footer a {
        color: #3b82f6;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .anggota-dropdown-footer a:hover {
        text-decoration: underline;
    }

    /* Notification Item */
    .anggota-notif-item {
        padding: 15px 20px;
        display: flex;
        gap: 12px;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .anggota-notif-item:hover {
        background: #f8fafc;
        border-left-color: #3b82f6;
    }

    .anggota-notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .anggota-notif-icon.primary {
        background: #dbeafe;
        color: #3b82f6;
    }

    .anggota-notif-icon.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .anggota-notif-icon.warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .anggota-notif-content {
        flex: 1;
    }

    .anggota-notif-date {
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .anggota-notif-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.4;
    }

    /* Mobile Search */
    .anggota-mobile-search {
        display: none;
    }

    /* Responsive */
    @media (min-width: 768px) {
        .anggota-search-box {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .anggota-topbar {
            padding: 12px 15px;
        }

        .anggota-user-info {
            display: none;
        }

        .anggota-mobile-search {
            display: block;
        }

        .anggota-dropdown {
            min-width: 250px;
        }
    }

    @media (max-width: 480px) {
        .anggota-topbar-right {
            gap: 8px;
        }

        .anggota-nav-icon-btn {
            width: 36px;
            height: 36px;
        }

        .anggota-dropdown {
            right: -10px;
            min-width: 280px;
            max-width: calc(100vw - 30px);
        }
    }
</style>

<nav class="anggota-navbar-wrapper">
    <div class="anggota-topbar">
        <!-- Left Side -->
        <div class="anggota-topbar-left">
            <!-- Sidebar Toggle -->
            <button id="anggotaSidebarToggle" class="anggota-sidebar-toggle d-md-none">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search Box (Desktop) -->
            <div class="anggota-search-box" style="position: relative;">
                <i class="fas fa-search anggota-search-icon"></i>
                <input type="text"
                       id="anggotaSearchInput"
                       class="anggota-search-input"
                       placeholder="Cari anggota atau kegiatan..."
                       autocomplete="off"
                       data-search-url="{{ route('anggota.search') }}">
                <div id="anggotaSearchResults" style="
                    display: none;
                    position: absolute;
                    top: calc(100% + 8px);
                    left: 0;
                    width: 100%;
                    min-width: 380px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                    z-index: 9999;
                    max-height: 420px;
                    overflow-y: auto;
                    border: 1px solid #e2e8f0;
                "></div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="anggota-topbar-right">
            <!-- Mobile Search Icon -->
            <div class="anggota-mobile-search anggota-nav-item">
                <button class="anggota-nav-icon-btn" id="anggotaMobileSearchBtn" title="Cari">
                    <i class="fas fa-search"></i>
                </button>
                <div id="anggotaMobileSearchPanel" style="
                    display: none;
                    position: fixed;
                    top: 70px; left: 0; right: 0;
                    background: white;
                    z-index: 9999;
                    padding: 15px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                ">
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
                        <input type="text"
                               id="anggotaMobileSearchInput"
                               class="anggota-search-input"
                               style="width:100%; padding-left: 38px;"
                               placeholder="Cari anggota, kegiatan..."
                               autocomplete="off"
                               data-search-url="{{ route('anggota.search') }}">
                    </div>
                    <div id="anggotaMobileSearchResults" style="margin-top: 8px; max-height: 300px; overflow-y: auto;"></div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="anggota-nav-item">
                <button class="anggota-nav-icon-btn" id="anggotaNotifBtn">
                    <i class="fas fa-bell"></i>
                    @if(count($notifications) > 0)
                    <span class="anggota-badge-counter">{{ count($notifications) }}</span>
                    @endif
                </button>

                <!-- Notifications Dropdown -->
                <div class="anggota-dropdown" id="anggotaNotifDropdown">
                    <div class="anggota-dropdown-header">
                        Notifikasi Terbaru
                    </div>
                    <div>
                        @forelse($notifications as $notif)
                        <div class="anggota-notif-item">
                            <div class="anggota-notif-icon {{ $notif->icon_bg }}">
                                <i class="{{ $notif->icon }}"></i>
                            </div>
                            <div class="anggota-notif-content">
                                <div class="anggota-notif-date">{{ $notif->date }}</div>
                                <div class="anggota-notif-text">{{ $notif->text }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="anggota-notif-item justify-content-center text-muted" style="padding: 15px; text-align: center;">
                            Belum ada notifikasi
                        </div>
                        @endforelse
                    </div>
                    <div class="anggota-dropdown-footer">
                        <a href="#">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="anggota-divider d-none d-md-block"></div>

            <!-- User Menu -->
            <div class="anggota-nav-item">
                <div class="anggota-user-menu" id="anggotaUserMenuBtn">
                    <div class="anggota-user-info">
                        <span class="anggota-user-name">{{ Auth::user()->name }}</span>
                        <span class="anggota-user-role">Anggota</span>
                    </div>
                    <img class="anggota-user-avatar"
                         src="{{ Auth::user()->image
                                 ? asset('storage/' . Auth::user()->image)
                                 : asset('template/img/undraw_profile.svg') }}"
                         alt="User Avatar">
                </div>

                <!-- User Dropdown -->
                <div class="anggota-dropdown" id="anggotaUserDropdown">
                    <div class="anggota-dropdown-header">
                        Akun Saya
                    </div>
                    <a class="anggota-dropdown-item" href="{{ route('anggota.profile.index') }}">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                    
                    <div class="anggota-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="anggota-dropdown-item" style="width: 100%; border: none; background: none; text-align: left;">
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
        sidebarToggle: document.getElementById('anggotaSidebarToggle'),
        notifBtn: document.getElementById('anggotaNotifBtn'),
        notifDropdown: document.getElementById('anggotaNotifDropdown'),
        userMenuBtn: document.getElementById('anggotaUserMenuBtn'),
        userDropdown: document.getElementById('anggotaUserDropdown')
    };

    // Toggle Dropdown
    function toggleDropdown(dropdown, button) {
        const isActive = dropdown.classList.contains('active');

        // Close all dropdowns
        document.querySelectorAll('.anggota-dropdown').forEach(d => {
            d.classList.remove('active');
        });

        // Open this dropdown if it was closed
        if (!isActive) {
            dropdown.classList.add('active');
        }
    }

    // Close dropdown when clicking outside
    function handleClickOutside(e) {
        if (!e.target.closest('.anggota-nav-item')) {
            document.querySelectorAll('.anggota-dropdown').forEach(d => {
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
            document.querySelectorAll('.anggota-dropdown').forEach(d => {
                d.classList.remove('active');
            });
        }
    });
})();
</script>

@include('shared.search-js', ['prefix' => 'anggota', 'searchRoute' => 'anggota.search'])
