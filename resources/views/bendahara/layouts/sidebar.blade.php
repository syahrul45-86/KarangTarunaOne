        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bendahara.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item ">
                <a href="{{ route('bendahara.catatan-keuangan.index') }}" class="nav-link">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Data keuangan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Denda</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
                        <a class="collapse-item" href="{{ route('bendahara.denda.index') }}">Denda Keseluruhan</a>
                        <a class="collapse-item" href="{{ route('bendahara.denda.absensi') }}">Denda absensi</a>
                    </div>
                </div>
            </li>

            <li class="nav-item {{ request()->routeIs('bendahara.qrcode.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('bendahara.qrcode.show') }}">
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
                <a class="nav-link" href="{{ route('bendahara.kas.index') }}">
                    <i class="fas fa-fw fa-qrcode"></i>
                    <span>Data Kas</span>
                </a>
            </li>
            {{-- <li class="nav-item active">
                <a href="{{ route('bendahara.rt.index') }}" class="nav-link">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tambah RT</span>
            </a>
            </li> --}}
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>



            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li>



        </ul>
        <!-- End of Sidebar -->