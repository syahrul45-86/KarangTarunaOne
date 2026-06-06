@extends('superadmin.layouts.master')

@section('title', 'Dashboard Superadmin')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .sa-page { font-family: 'Inter', 'Nunito', sans-serif; }

    /* ===== HERO HEADER ===== */
    .sa-hero {
        background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #1e40af 100%);
        border-radius: 24px;
        padding: 32px 36px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(30,41,59,0.4);
    }
    .sa-hero::before {
        content: ''; position: absolute; top: -60px; right: -60px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .sa-hero::after {
        content: ''; position: absolute; bottom: -40px; left: 40%;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .sa-hero-badge {
        background: rgba(59,130,246,0.3); color: #93c5fd;
        border: 1px solid rgba(59,130,246,0.4);
        border-radius: 20px; padding: 4px 14px;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; display: inline-flex; align-items: center; gap: 6px;
        margin-bottom: 12px;
    }
    .sa-hero-title {
        font-size: 1.7rem; font-weight: 800; color: #fff;
        margin: 0 0 6px; letter-spacing: -0.5px; position: relative; z-index: 1;
    }
    .sa-hero-sub { color: rgba(255,255,255,0.6); font-size: 0.88rem; margin: 0; position: relative; z-index: 1; }
    .sa-hero-avatar {
        width: 56px; height: 56px; border-radius: 16px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 800; color: #fff;
        box-shadow: 0 8px 24px rgba(59,130,246,0.4);
        position: relative; z-index: 1; flex-shrink: 0;
    }
    .sa-hero-time {
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px; padding: 10px 16px;
        color: rgba(255,255,255,0.8); font-size: 0.82rem; font-weight: 500;
        display: flex; align-items: center; gap: 8px; position: relative; z-index: 1;
        white-space: nowrap;
    }

    /* ===== STAT CARDS ===== */
    .sa-stat {
        border-radius: 20px; padding: 22px 24px; margin-bottom: 24px;
        position: relative; overflow: hidden; transition: transform 0.3s ease;
        border: none; cursor: default;
    }
    .sa-stat:hover { transform: translateY(-5px); }
    .sa-stat::after {
        content: ''; position: absolute; bottom: -25px; right: -25px;
        width: 90px; height: 90px; border-radius: 50%; background: rgba(255,255,255,0.12);
    }
    .sa-stat-1 { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); box-shadow: 0 10px 36px rgba(59,130,246,0.35); }
    .sa-stat-2 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 36px rgba(16,185,129,0.35); }
    .sa-stat-3 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 36px rgba(245,158,11,0.35); }
    .sa-stat-4 { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); box-shadow: 0 10px 36px rgba(139,92,246,0.35); }

    .sa-stat-label { color: rgba(255,255,255,0.82); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    .sa-stat-val { color: #fff; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px; line-height: 1; margin-bottom: 6px; }
    .sa-stat-sub { color: rgba(255,255,255,0.65); font-size: 0.75rem; }
    .sa-stat-icon {
        width: 46px; height: 46px; border-radius: 14px; background: rgba(255,255,255,0.22);
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff;
        flex-shrink: 0; position: relative; z-index: 1;
    }

    /* ===== SECTION CARDS ===== */
    .sa-card { background: #fff; border-radius: 20px; border: none; box-shadow: 0 4px 30px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 24px; }
    .sa-card-header { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .sa-card-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px; }
    .sa-card-dot { width: 8px; height: 8px; border-radius: 50%; }
    .sa-card-dot-blue { background: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }
    .sa-card-dot-green { background: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.2); }

    /* RT List */
    .rt-item {
        display: flex; align-items: center; padding: 14px 24px; border-bottom: 1px solid #f8fafc;
        transition: background 0.2s ease;
    }
    .rt-item:last-child { border-bottom: none; }
    .rt-item:hover { background: #f8fafc; }
    .rt-badge {
        width: 42px; height: 42px; border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 800; color: #fff; margin-right: 14px; flex-shrink: 0;
    }
    .rt-name { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
    .rt-rw { font-size: 0.75rem; color: #94a3b8; }
    .rt-anggota-badge {
        background: rgba(59,130,246,0.1); color: #2563eb; border-radius: 20px;
        padding: 4px 12px; font-size: 0.75rem; font-weight: 700; white-space: nowrap;
    }

    /* Quick links */
    .quick-link {
        display: flex; align-items: center; gap: 14px; padding: 16px 24px;
        border-bottom: 1px solid #f8fafc; text-decoration: none; transition: background 0.2s;
    }
    .quick-link:last-child { border-bottom: none; }
    .quick-link:hover { background: #f8fafc; text-decoration: none; }
    .quick-link-icon {
        width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center;
        justify-content: center; font-size: 1rem; flex-shrink: 0;
    }
    .quick-link-label { font-weight: 600; color: #1e293b; font-size: 0.88rem; }
    .quick-link-sub { font-size: 0.75rem; color: #94a3b8; }
    .quick-link-arrow { margin-left: auto; color: #cbd5e1; font-size: 0.8rem; }

    /* Responsive */
    @media (max-width: 767px) {
        .sa-hero { padding: 22px 20px; border-radius: 18px; flex-wrap: wrap; gap: 14px; }
        .sa-hero-title { font-size: 1.35rem; }
        .sa-stat-val { font-size: 1.65rem; }
        .sa-hero-time { display: none; }
    }
</style>
@endpush

@section('content')
<div class="sa-page">

    {{-- ===== HERO HEADER ===== --}}
    <div class="sa-hero d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3" style="position:relative;z-index:1;">
            <div class="sa-hero-avatar">{{ strtoupper(substr($superadmin->name, 0, 1)) }}</div>
            <div>
                <div class="sa-hero-badge"><i class="fas fa-shield-alt"></i> Superadmin</div>
                <h1 class="sa-hero-title">Halo, {{ $superadmin->name }}! 👋</h1>
                <p class="sa-hero-sub">Selamat datang di panel kendali sistem RT Digital</p>
            </div>
        </div>
        <div class="sa-hero-time">
            <i class="fas fa-calendar-alt"></i>
            <span id="sa-date">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="row">
        <div class="col-6 col-xl-3">
            <div class="sa-stat sa-stat-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sa-stat-label">Total RT</div>
                        <div class="sa-stat-val">{{ $totalRt }}</div>
                        <div class="sa-stat-sub">RT terdaftar di sistem</div>
                    </div>
                    <div class="sa-stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sa-stat sa-stat-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sa-stat-label">Total Anggota</div>
                        <div class="sa-stat-val">{{ $totalAnggota }}</div>
                        <div class="sa-stat-sub">Warga aktif di semua RT</div>
                    </div>
                    <div class="sa-stat-icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sa-stat sa-stat-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sa-stat-label">Admin RT</div>
                        <div class="sa-stat-val">{{ $totalAdmin }}</div>
                        <div class="sa-stat-sub">Pengelola aktif</div>
                    </div>
                    <div class="sa-stat-icon"><i class="fas fa-user-shield"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sa-stat sa-stat-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="sa-stat-label">Total Pengguna</div>
                        <div class="sa-stat-val">{{ $totalUser }}</div>
                        <div class="sa-stat-sub">Semua akun di sistem</div>
                    </div>
                    <div class="sa-stat-icon"><i class="fas fa-user-cog"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CONTENT ROW ===== --}}
    <div class="row">
        {{-- Daftar RT --}}
        <div class="col-12 col-lg-7">
            <div class="sa-card">
                <div class="sa-card-header">
                    <h6 class="sa-card-title">
                        <span class="sa-card-dot sa-card-dot-blue"></span>
                        Daftar RT Terdaftar
                    </h6>
                    <a href="{{ route('superadmin.rt.index') }}" style="font-size:0.78rem; color:#3b82f6; font-weight:600; text-decoration:none;">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @forelse($rtList as $rt)
                <div class="rt-item">
                    <div class="rt-badge">RT</div>
                    <div class="flex-grow-1" style="min-width:0;">
                        <div class="rt-name">{{ $rt->nama_rt }}</div>
                        <div class="rt-rw">RW {{ $rt->rw ?? '—' }}</div>
                    </div>
                    <span class="rt-anggota-badge">
                        <i class="fas fa-users mr-1"></i>{{ $rt->users_count }} anggota
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-map-marker-alt fa-2x mb-2 d-block" style="color:#e2e8f0;"></i>
                    Belum ada RT terdaftar
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="col-12 col-lg-5">
            <div class="sa-card">
                <div class="sa-card-header">
                    <h6 class="sa-card-title">
                        <span class="sa-card-dot sa-card-dot-green"></span>
                        Menu Cepat
                    </h6>
                </div>

                <a href="{{ route('superadmin.rt.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background:rgba(59,130,246,0.1); color:#2563eb;">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div>
                        <div class="quick-link-label">Kelola Data RT</div>
                        <div class="quick-link-sub">Tambah, edit, atau hapus RT</div>
                    </div>
                    <i class="fas fa-chevron-right quick-link-arrow"></i>
                </a>

                <a href="{{ route('superadmin.adminrt.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background:rgba(16,185,129,0.1); color:#059669;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="quick-link-label">Kelola Admin RT</div>
                        <div class="quick-link-sub">Manajemen akun admin per RT</div>
                    </div>
                    <i class="fas fa-chevron-right quick-link-arrow"></i>
                </a>

                <a href="{{ route('superadmin.profile.index') }}" class="quick-link">
                    <div class="quick-link-icon" style="background:rgba(139,92,246,0.1); color:#6d28d9;">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <div class="quick-link-label">Profil Saya</div>
                        <div class="quick-link-sub">Edit data & password akun</div>
                    </div>
                    <i class="fas fa-chevron-right quick-link-arrow"></i>
                </a>
            </div>

            {{-- Info Card --}}
            <div class="sa-card" style="background: linear-gradient(135deg, #1e293b, #1e40af); border:none;">
                <div style="padding: 22px 24px; position: relative; overflow: hidden;">
                    <div style="position:absolute; top:-30px; right:-30px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.06);"></div>
                    <div style="color:rgba(255,255,255,0.7); font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                        <i class="fas fa-info-circle mr-1"></i> Info Sistem
                    </div>
                    <div style="color:#fff; font-size:0.9rem; font-weight:600; margin-bottom:6px;">
                        Anda login sebagai <span style="color:#93c5fd;">Superadmin</span>
                    </div>
                    <div style="color:rgba(255,255,255,0.55); font-size:0.78rem; line-height:1.6;">
                        Anda memiliki akses penuh untuk mengelola seluruh data RT, akun admin, dan konfigurasi sistem.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
