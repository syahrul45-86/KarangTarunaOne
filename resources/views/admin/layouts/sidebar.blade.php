        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"> Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.AnggotaRT.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tambah Anggota</span></a>
            </li>
            
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.rekap.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Rekap Absensi</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.rekap.arisan.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Rekap Arisan</span></a>
            </li>
             <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.denda.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Rekap Denda</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.qrcode.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.qrcode.show') }}">
                    <i class="fas fa-fw fa-qrcode"></i>
                    <span>QR Code Saya</span>
                </a>
            </li>

            @php
                $activeFormsCount = \App\Models\AbsensiForm::where('rt_id', auth()->user()->rt_id)
                    ->whereDate('tanggal', '>=', now()->toDateString())
                    ->count();
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
             <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.setting_rt.index') }}">
                    <i class="fas fa-fw fa-qrcode"></i>
                    <span>Setting</span>
                </a>
            </li>
           

        </ul>
        <!-- End of Sidebar -->
