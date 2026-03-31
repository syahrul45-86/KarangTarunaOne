@extends('admin.layouts.master')

@section('content')

<style>
/* ===== ANGGOTA RT STYLES - TEMA HIJAU ===== */
.anggota-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header Section */
.anggota-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.anggota-header h2 {
    color: white;
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    flex: 1;
    min-width: 250px;
}

.anggota-btn-add {
    background: white;
    color: #059669;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.anggota-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    background: #f0fdf4;
}

/* Search Container */
.anggota-search-container {
    position: relative;
    margin-bottom: 2rem;
    max-width: 500px;
}

.anggota-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.25rem;
    color: #6b7280;
}

.anggota-search-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #d1fae5;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.anggota-search-input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
}

/* Table Container */
.anggota-table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.anggota-table-wrapper {
    overflow-x: auto;
}

.anggota-table {
    width: 100%;
    border-collapse: collapse;
}

.anggota-table thead {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.anggota-table thead th {
    padding: 1.25rem 1rem;
    text-align: left;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.anggota-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.2s ease;
}

.anggota-table tbody tr:hover {
    background: #f0fdf4;
    transform: scale(1.01);
}

.anggota-table tbody td {
    padding: 1.25rem 1rem;
    color: #374151;
    font-size: 0.95rem;
}

/* Badge Styles */
.anggota-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.anggota-badge-anggota {
    background: #dbeafe;
    color: #1e40af;
}

.anggota-badge-sekretaris {
    background: #fef3c7;
    color: #92400e;
}

.anggota-badge-bendahara {
    background: #fce7f3;
    color: #9f1239;
}

.anggota-badge-admin {
    background: #f3e8ff;
    color: #6b21a8;
}

/* Action Buttons */
.anggota-btn-idcard {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.anggota-btn-idcard:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.anggota-btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
    width: 100%;
}

.anggota-btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.no-idcard {
    color: #9ca3af;
    font-size: 0.875rem;
    font-style: italic;
    padding: 0.5rem 1rem;
    background: #f9fafb;
    border-radius: 8px;
    display: inline-block;
}

/* Empty State */
.anggota-empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.anggota-empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.anggota-empty-state-text {
    color: #6b7280;
    font-size: 1.125rem;
}

/* Modal Styles */
.anggota-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.anggota-modal-content {
    background: white;
    margin: 3% auto;
    max-width: 600px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.anggota-modal-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 1.5rem 2rem;
    border-radius: 20px 20px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.anggota-modal-header h2 {
    color: white;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.anggota-modal-close {
    color: white;
    font-size: 2rem;
    font-weight: 300;
    cursor: pointer;
    transition: all 0.2s ease;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.anggota-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.anggota-modal-body {
    padding: 2rem;
}

.anggota-form-group {
    margin-bottom: 1.5rem;
}

.anggota-form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 600;
    font-size: 0.95rem;
}

.anggota-form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #d1fae5;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.anggota-form-control:focus {
    outline: none;
    border-color: #10b981;
    background: white;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
}

.anggota-form-control option:disabled {
    color: #9ca3af;
}

.anggota-modal-footer {
    padding: 1.5rem 2rem;
    background: #f9fafb;
    border-radius: 0 0 20px 20px;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.anggota-btn-modal {
    padding: 0.75rem 2rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.anggota-btn-cancel {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}

.anggota-btn-cancel:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.anggota-btn-save {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.anggota-btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .anggota-container {
        padding: 1rem;
    }

    .anggota-header {
        flex-direction: column;
        text-align: center;
    }

    .anggota-header h2 {
        font-size: 1.25rem;
    }

    .anggota-table thead {
        display: none;
    }

    .anggota-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 2px solid #d1fae5;
        border-radius: 12px;
        padding: 1rem;
    }

    .anggota-table tbody td {
        display: block;
        text-align: right;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .anggota-table tbody td:last-child {
        border-bottom: none;
    }

    .anggota-table tbody td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #059669;
    }

    .anggota-modal-content {
        margin: 10% 1rem;
        max-width: 100%;
    }

    .anggota-modal-footer {
        flex-direction: column;
    }

    .anggota-btn-modal {
        width: 100%;
    }
}
</style>

<div class="anggota-container">
    <div class="anggota-header">
        <h2>📋 Daftar Anggota RT {{ Auth::user()->rt->nama_rt }} (RW {{ Auth::user()->rt->rw }})</h2>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button class="anggota-btn-add" data-anggota-action="open-modal">
                <span>➕</span>
                <span>Tambah Data</span>
            </button>
            <a href="{{ route('admin.idcard.form') }}" class="anggota-btn-add">
                🎴 Buat ID Card
            </a>
        </div>
    </div>

    <div class="anggota-search-container">
        <span class="anggota-search-icon">🔍</span>
        <input type="text" id="anggotaSearchInput" class="anggota-search-input" placeholder="Cari nama, email, RT, atau RW...">
    </div>

    <div class="anggota-table-container">
        <div class="anggota-table-wrapper">
            <table class="anggota-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nama RT</th>
                        <th>Nama RW</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="anggotaTableBody">
                    @forelse($anggotaSemua as $index => $a)
                        <tr>
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="Nama">{{ $a->name }}</td>
                            <td data-label="Email">{{ $a->email }}</td>
                            <td data-label="Nama RT">{{ $a->rt->nama_rt ?? '-' }}</td>
                            <td data-label="Nama RW">{{ $a->rt->rw ?? '-' }}</td>
                            <td data-label="Role">
                                <span class="anggota-badge anggota-badge-{{ $a->role }}">
                                    {{ $a->role }}
                                </span>
                            </td>
                            <td data-label="Aksi">
                                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                    @if($a->id_card_path && Storage::disk('public')->exists($a->id_card_path))
                                        <a href="{{ asset('storage/' . $a->id_card_path) }}"
                                           target="_blank"
                                           class="anggota-btn-idcard">
                                            🎫 ID Card
                                        </a>
                                    @else
                                        <span class="no-idcard">
                                            Belum ada
                                        </span>
                                    @endif
                                </div>
                                <form action="{{ route('admin.AnggotaRT.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="anggota-btn-delete">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="anggota-empty-state">
                                    <div class="anggota-empty-state-icon">📭</div>
                                    <div class="anggota-empty-state-text">Belum ada data anggota</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div id="anggotaAddModal" class="anggota-modal">
    <div class="anggota-modal-content">
        <div class="anggota-modal-header">
            <h2>➕ Tambah Anggota RT</h2>
            <span class="anggota-modal-close" data-anggota-action="close-modal" data-modal-id="anggotaAddModal">&times;</span>
        </div>
        <div class="anggota-modal-body">
            <form action="{{ route('admin.AnggotaRT.store') }}" method="POST" id="anggotaAddForm">
                @csrf

                <div class="anggota-form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="name" class="anggota-form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="anggota-form-group">
                    <label>Email *</label>
                    <input type="email" name="email" class="anggota-form-control" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                </div>

                <div class="anggota-form-group">
                    <label>Password *</label>
                    <input type="password" name="password" class="anggota-form-control" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="anggota-form-group">
                    <label>Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="anggota-form-control" placeholder="Masukkan ulang password" required>
                </div>

                <div class="anggota-form-group">
                    <label>Role *</label>
                    <select name="role" class="anggota-form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="anggota" {{ old('role') == 'anggota' ? 'selected' : '' }}>👤 Anggota</option>
                        <option value="sekretaris" {{ old('role') == 'sekretaris' ? 'selected' : '' }}
                                @if($sekretaris) disabled @endif>
                            📝 Sekretaris @if($sekretaris)(Sudah Ada)@endif
                        </option>
                        <option value="bendahara" {{ old('role') == 'bendahara' ? 'selected' : '' }}
                                @if($bendahara) disabled @endif>
                            💰 Bendahara @if($bendahara)(Sudah Ada)@endif
                        </option>
                    </select>
                </div>
            </form>
        </div>
        <div class="anggota-modal-footer">
            <button class="anggota-btn-modal anggota-btn-cancel" data-anggota-action="close-modal" data-modal-id="anggotaAddModal">Batal</button>
            <button type="submit" form="anggotaAddForm" class="anggota-btn-modal anggota-btn-save">💾 Simpan</button>
        </div>
    </div>
</div>

<!-- Data untuk JavaScript -->
<script>
    window.anggotaRTData = {
        hasErrors: {{ $errors->any() ? 'true' : 'false' }}
    };
</script>

@endsection

@push('scripts')

@endpush
