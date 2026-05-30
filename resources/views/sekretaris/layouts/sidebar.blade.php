<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                        <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('logopangudi.png') }}" alt="Logo" style="width: 40px; height: 40px; object-fit: contain;">
                </div>
                <div class="sidebar-brand-text mx-3">{{ Auth::user() && Auth::user()->rt ? Auth::user()->rt->nama_rt : 'Nama RT' }}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('sekretaris.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.dashboard') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Absensi</div>

            <!-- Nav Item - Absensi -->
            <li class="nav-item {{ request()->routeIs('sekretaris.absensi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.absensi.index') }}">
                    <i class="fas fa-fw fa-clipboard-check"></i>
                    <span>Daftar Absensi</span>
                </a>
            </li>

            <!-- Nav Item - Izin Absensi -->
            @php
                $pendingIzinCount = \App\Models\IzinAbsensi::where('status', 'pending')
                    ->whereHas('user', fn($q) => $q->where('rt_id', auth()->user()->rt_id ?? 0))
                    ->count();
            @endphp
            <li class="nav-item {{ request()->routeIs('sekretaris.izin.list') ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center justify-content-between" href="{{ route('sekretaris.izin.list') }}">
                    <span>
                        <i class="fas fa-fw fa-calendar-check"></i>
                        <span>Izin Absensi</span>
                    </span>
                    @if($pendingIzinCount > 0)
                        <span class="badge badge-danger badge-counter">{{ $pendingIzinCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Arisan</div>

            <!-- Nav Item - Catatan Arisan -->
            <li class="nav-item {{ request()->routeIs('sekretaris.catatan.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.catatan.index') }}">
                    <i class="fas fa-fw fa-piggy-bank"></i>
                    <span>Catatan Arisan</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('sekretaris.spin.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.spin.index') }}">
                    <i class="fas fa-fw fa-sync-alt"></i>
                    <span>Spin Arisan</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('sekretaris.arisan.recap') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.arisan.recap') }}">
                    <i class="fas fa-fw fa-chart-pie"></i>
                    <span>Rekap Arisan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Akun</div>

            <!-- Nav Item - QR Code -->
            <li class="nav-item {{ request()->routeIs('sekretaris.qrcode.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.qrcode.show') }}">
                    <i class="fas fa-fw fa-qrcode"></i>
                    <span>QR Code Saya</span>
                </a>
            </li>

            @php
                $now = now();
                $activeFormsCount = \App\Models\AbsensiForm::where('rt_id', auth()->user()->rt_id)
                    ->whereDate('tanggal', '>=', $now->toDateString())
                    ->get()
                    ->filter(function($form) use ($now) {
                        $waktuSelesai = \Carbon\Carbon::parse($form->tanggal . ' ' . $form->jam_selesai);
                        if ($now->greaterThan($waktuSelesai)) return false;
                        
                        return !\App\Models\IzinAbsensi::where('form_id', $form->id)
                            ->where('user_id', auth()->id())
                            ->exists();
                    })->count();
            @endphp
            <li class="nav-item {{ request()->routeIs('user.izin.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.izin.index') }}">
                    <i class="fas fa-fw fa-envelope-open-text"></i>
                    <span>Ajukan Izin</span>
                    @if($activeFormsCount > 0)
                        <span class="badge badge-danger badge-counter" style="font-size: 10px; margin-left: 5px;">{{ $activeFormsCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ request()->routeIs('sekretaris.profile.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sekretaris.profile.index') }}">
                    <i class="fas fa-fw fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->