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
            <li class="nav-item {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('anggota.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Kegiatan</div>

            <!-- Nav Item - QR Code -->
            <li class="nav-item {{ request()->routeIs('anggota.qrcode.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('anggota.qrcode.show') }}">
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
            <!-- Nav Item - Izin Absensi -->
            <li class="nav-item {{ request()->routeIs('user.izin.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.izin.index') }}">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Izin Absensi</span>
                    @if($activeFormsCount > 0)
                    <span class="badge badge-danger badge-counter" style="font-size: 10px; margin-left: 5px;">{{ $activeFormsCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Akun</div>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ request()->routeIs('anggota.profile.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('anggota.profile.index') }}">
                    <i class="fas fa-fw fa-user-circle"></i>
                    <span>Profile Saya</span>
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