@extends('superadmin.layouts.master')

@section('content')

<style>
    /* Scoped Styles for Admin RT Management */
    .adminrt-management-wrapper {
        padding: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        padding: 30px 20px;
    }

    .adminrt-container {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .adminrt-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .adminrt-title {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .adminrt-btn-add {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .adminrt-btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    .adminrt-search-container {
        position: relative;
        margin-bottom: 30px;
    }

    .adminrt-search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #94a3b8;
    }

    .adminrt-search-input {
        width: 100%;
        padding: 16px 20px 16px 55px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .adminrt-search-input:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }

    .adminrt-table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .adminrt-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .adminrt-table thead {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
    }

    .adminrt-table thead th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 14px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .adminrt-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .adminrt-table tbody tr:hover {
        background: #fef3c7;
        transform: scale(1.01);
    }

    .adminrt-table tbody td {
        padding: 18px 20px;
        color: #475569;
        font-size: 15px;
    }

    .adminrt-table tbody td:first-child {
        font-weight: 600;
        color: #f59e0b;
    }

    .adminrt-action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .adminrt-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .adminrt-btn-delete {
        background: #ef4444;
        color: white;
    }

    .adminrt-btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .adminrt-empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .adminrt-empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .adminrt-empty-state p {
        font-size: 16px;
        margin: 0;
    }

    /* Badge Styles */
    .adminrt-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .adminrt-badge-rt {
        background: #dbeafe;
        color: #1e40af;
    }

    .adminrt-badge-rw {
        background: #dcfce7;
        color: #166534;
    }

    .adminrt-user-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .adminrt-user-name {
        font-weight: 600;
        color: #1e293b;
    }

    .adminrt-user-email {
        font-size: 13px;
        color: #64748b;
    }

    /* Modal Styles */
    .adminrt-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        animation: fadeIn 0.3s ease;
    }

    .adminrt-modal.active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .adminrt-modal-content {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    .adminrt-modal-header {
        padding: 25px 30px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        border-radius: 20px 20px 0 0;
    }

    .adminrt-modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: white;
    }

    .adminrt-modal-close {
        background: white;
        color: #f59e0b;
        border: none;
        font-size: 28px;
        cursor: pointer;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        line-height: 1;
    }

    .adminrt-modal-close:hover {
        background: #fef3c7;
        transform: rotate(90deg);
    }

    .adminrt-modal-body {
        padding: 30px;
    }

    .adminrt-form-group {
        margin-bottom: 25px;
    }

    .adminrt-form-label {
        display: block;
        font-weight: 600;
        color: #334155;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .adminrt-form-label .required {
        color: #ef4444;
        margin-left: 4px;
    }

    .adminrt-form-input,
    .adminrt-form-select {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .adminrt-form-input:focus,
    .adminrt-form-select:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }

    .adminrt-form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 45px;
    }

    .adminrt-password-wrapper {
        position: relative;
    }

    .adminrt-password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: #64748b;
        transition: color 0.3s ease;
    }

    .adminrt-password-toggle:hover {
        color: #f59e0b;
    }

    .adminrt-modal-footer {
        padding: 20px 30px;
        border-top: 2px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #f8fafc;
        border-radius: 0 0 20px 20px;
    }

    .adminrt-btn-modal {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .adminrt-btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .adminrt-btn-cancel:hover {
        background: #cbd5e1;
    }

    .adminrt-btn-save {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .adminrt-btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    .adminrt-alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .adminrt-alert-info {
        background: #dbeafe;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .adminrt-container {
            padding: 25px 20px;
        }

        .adminrt-title {
            font-size: 24px;
        }

        .adminrt-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .adminrt-btn-add {
            width: 100%;
            justify-content: center;
        }

        .adminrt-modal-content {
            width: 95%;
            margin: 10px;
        }

        .adminrt-modal-body {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .adminrt-management-wrapper {
            padding: 15px 10px;
        }

        .adminrt-container {
            padding: 20px 15px;
            border-radius: 15px;
        }

        .adminrt-title {
            font-size: 20px;
        }

        .adminrt-modal-header h2 {
            font-size: 20px;
        }

        /* Mobile Card View - NO SCROLL */
        .adminrt-table-container {
            overflow: visible;
            box-shadow: none;
        }

        .adminrt-table thead {
            display: none;
        }

        .adminrt-table,
        .adminrt-table tbody,
        .adminrt-table tr,
        .adminrt-table td {
            display: block;
            width: 100%;
        }

        .adminrt-table tbody tr {
            margin-bottom: 15px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 15px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .adminrt-table tbody tr:hover {
            transform: none;
            background: white;
        }

        .adminrt-table tbody td {
            padding: 10px 0;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .adminrt-table tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 80px;
        }

        .adminrt-table tbody td:first-child {
            font-size: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 10px;
        }

        .adminrt-table tbody td:first-child:before {
            content: "📋 ";
        }

        .adminrt-table tbody td:last-child {
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            margin-top: 10px;
            display: block;
        }

        .adminrt-table tbody td:last-child:before {
            content: "";
            display: none;
        }

        .adminrt-action-buttons {
            width: 100%;
            flex-direction: column;
            gap: 10px;
        }

        .adminrt-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="adminrt-management-wrapper">
    <div class="adminrt-container">
        <!-- Header -->
        <div class="adminrt-header">
            <h1 class="adminrt-title">
                <span>👤</span>
                <span>Data Admin RT</span>
            </h1>
            <button class="adminrt-btn-add" id="adminrtBtnAdd">
                <span>➕</span>
                <span>Tambah Admin</span>
            </button>
        </div>

        <!-- Search -->
        <div class="adminrt-search-container">
            <span class="adminrt-search-icon">🔍</span>
            <input type="text"
                   class="adminrt-search-input"
                   id="adminrtSearchInput"
                   placeholder="Cari nama, email, atau RT...">
        </div>

        <!-- Table -->
        <div class="adminrt-table-container">
            <table class="adminrt-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Admin</th>
                        <th>Email</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="adminrtTableBody">
                    @forelse($users as $index => $user)
                        <tr data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . ($user->rt->nama_rt ?? '') . ' ' . ($user->rt->rw ?? '')) }}">
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="Admin">
                                <div class="adminrt-user-info">
                                    <span class="adminrt-user-name">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td data-label="Email">
                                <span class="adminrt-user-email">{{ $user->email }}</span>
                            </td>
                            <td data-label="RT">
                                @if($user->rt)
                                    <span class="adminrt-badge adminrt-badge-rt">{{ $user->rt->nama_rt }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td data-label="RW">
                                @if($user->rt)
                                    <span class="adminrt-badge adminrt-badge-rw">{{ $user->rt->rw }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td data-label="Aksi">
                                <div class="adminrt-action-buttons">
                                    <form action="{{ route('superadmin.adminrt.destroy', $user->id) }}"
                                          method="POST"
                                          style="display:inline; width: 100%;"
                                          class="adminrt-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="adminrt-btn adminrt-btn-delete">
                                            <span>🗑️</span>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="adminrt-empty-state">
                                    <i class="fas fa-user-shield"></i>
                                    <p>Belum ada admin RT terdaftar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Admin -->
    <div id="adminrtAddModal" class="adminrt-modal">
        <div class="adminrt-modal-content">
            <div class="adminrt-modal-header">
                <h2>Tambah Admin RT Baru</h2>
                <button class="adminrt-modal-close" id="adminrtCloseAdd">&times;</button>
            </div>
            <div class="adminrt-modal-body">
                @if($rt->count() == 0)
                <div class="adminrt-alert adminrt-alert-info">
                    <span>ℹ️</span>
                    <span>Semua RT sudah memiliki admin. Silakan tambah RT baru terlebih dahulu.</span>
                </div>
                @endif

                <form action="{{ route('superadmin.adminrt.store') }}" method="POST" id="adminrtAddForm">
                    @csrf
                    <div class="adminrt-form-group">
                        <label class="adminrt-form-label">
                            Nama Admin
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="adminrt-form-input"
                               placeholder="Contoh: Budi Santoso"
                               required>
                    </div>

                    <div class="adminrt-form-group">
                        <label class="adminrt-form-label">
                            Email
                            <span class="required">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               class="adminrt-form-input"
                               placeholder="contoh@email.com"
                               required>
                    </div>

                    <div class="adminrt-form-group">
                        <label class="adminrt-form-label">
                            Password
                            <span class="required">*</span>
                        </label>
                        <div class="adminrt-password-wrapper">
                            <input type="password"
                                   name="password"
                                   id="adminrtPassword"
                                   class="adminrt-form-input"
                                   placeholder="Minimal 6 karakter"
                                   required>
                            <button type="button"
                                    class="adminrt-password-toggle"
                                    data-target="adminrtPassword">
                                👁️
                            </button>
                        </div>
                    </div>

                    <div class="adminrt-form-group">
                        <label class="adminrt-form-label">
                            Konfirmasi Password
                            <span class="required">*</span>
                        </label>
                        <div class="adminrt-password-wrapper">
                            <input type="password"
                                   name="password_confirmation"
                                   id="adminrtPasswordConfirm"
                                   class="adminrt-form-input"
                                   placeholder="Ketik ulang password"
                                   required>
                            <button type="button"
                                    class="adminrt-password-toggle"
                                    data-target="adminrtPasswordConfirm">
                                👁️
                            </button>
                        </div>
                    </div>

                    <div class="adminrt-form-group">
                        <label class="adminrt-form-label">
                            Pilih RT
                            <span class="required">*</span>
                        </label>
                        <select name="rt_id" class="adminrt-form-select" required>
                            <option value="">-- Pilih RT --</option>
                            @forelse ($rt as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_rt }} ({{ $r->rw }})</option>
                            @empty
                                <option disabled>Tidak ada RT tersedia</option>
                            @endforelse
                        </select>
                    </div>
                </form>
            </div>
            <div class="adminrt-modal-footer">
                <button class="adminrt-btn-modal adminrt-btn-cancel" id="adminrtCancelAdd">Batal</button>
                <button type="submit" form="adminrtAddForm" class="adminrt-btn-modal adminrt-btn-save" {{ $rt->count() == 0 ? 'disabled' : '' }}>
                    💾 Simpan
                </button>
            </div>
        </div>
    </div>
</div>



@endsection
