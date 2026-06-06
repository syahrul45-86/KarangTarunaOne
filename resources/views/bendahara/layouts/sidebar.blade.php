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
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bendahara.kas.index') }}">
                    <i class="fas fa-fw fa-wallet"></i>
                    <span>Data Kas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bendahara.tabungan.index') }}">
                    <i class="fas fa-fw fa-piggy-bank"></i>
                    <span>Data Tabungan</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('bendahara.tabungan_saya') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('bendahara.tabungan_saya') }}">
                    <i class="fas fa-fw fa-coins"></i>
                    <span>Tabungan Saya</span>
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


        </ul>
        <!-- End of Sidebar -->