@extends('superadmin.layouts.master')

@section('content')

<style>
    /* Scoped Styles for RT/RW Management */
    .rtrw-management-wrapper {
        padding: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 20px;
    }

    .rtrw-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .rtrw-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .rtrw-title {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rtrw-btn-add {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .rtrw-btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .rtrw-search-container {
        position: relative;
        margin-bottom: 30px;
    }

    .rtrw-search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #94a3b8;
    }

    .rtrw-search-input {
        width: 100%;
        padding: 16px 20px 16px 55px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .rtrw-search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .rtrw-table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .rtrw-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .rtrw-table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .rtrw-table thead th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 700;
        font-size: 14px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rtrw-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .rtrw-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }

    .rtrw-table tbody td {
        padding: 18px 20px;
        color: #475569;
        font-size: 15px;
    }

    .rtrw-table tbody td:first-child {
        font-weight: 600;
        color: #667eea;
    }

    .rtrw-action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .rtrw-btn {
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

    .rtrw-btn-edit {
        background: #3b82f6;
        color: white;
    }

    .rtrw-btn-edit:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .rtrw-btn-delete {
        background: #ef4444;
        color: white;
    }

    .rtrw-btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .rtrw-empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .rtrw-empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .rtrw-empty-state p {
        font-size: 16px;
        margin: 0;
    }

    /* Modal Styles */
    .rtrw-modal {
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

    .rtrw-modal.active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .rtrw-modal-content {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    .rtrw-modal-header {
        padding: 25px 30px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px 20px 0 0;
    }

    .rtrw-modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: white;
    }

    .rtrw-modal-close {
        background: white;
        color: #667eea;
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

    .rtrw-modal-close:hover {
        background: #f1f5f9;
        transform: rotate(90deg);
    }

    .rtrw-modal-body {
        padding: 30px;
    }

    .rtrw-form-group {
        margin-bottom: 25px;
    }

    .rtrw-form-label {
        display: block;
        font-weight: 600;
        color: #334155;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .rtrw-form-label .required {
        color: #ef4444;
        margin-left: 4px;
    }

    .rtrw-form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .rtrw-form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .rtrw-modal-footer {
        padding: 20px 30px;
        border-top: 2px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #f8fafc;
        border-radius: 0 0 20px 20px;
    }

    .rtrw-btn-modal {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .rtrw-btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .rtrw-btn-cancel:hover {
        background: #cbd5e1;
    }

    .rtrw-btn-save {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .rtrw-btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
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
        .rtrw-container {
            padding: 25px 20px;
        }

        .rtrw-title {
            font-size: 24px;
        }

        .rtrw-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .rtrw-btn-add {
            width: 100%;
            justify-content: center;
        }

        .rtrw-modal-content {
            width: 95%;
            margin: 10px;
        }

        .rtrw-modal-body {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .rtrw-management-wrapper {
            padding: 15px 10px;
        }

        .rtrw-container {
            padding: 20px 15px;
            border-radius: 15px;
        }

        .rtrw-title {
            font-size: 20px;
        }

        .rtrw-modal-header h2 {
            font-size: 20px;
        }

        /* Mobile Card View - NO SCROLL */
        .rtrw-table-container {
            overflow: visible;
            box-shadow: none;
        }

        .rtrw-table thead {
            display: none;
        }

        .rtrw-table,
        .rtrw-table tbody,
        .rtrw-table tr,
        .rtrw-table td {
            display: block;
            width: 100%;
        }

        .rtrw-table tbody tr {
            margin-bottom: 15px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 15px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .rtrw-table tbody tr:hover {
            transform: none;
            background: white;
        }

        .rtrw-table tbody td {
            padding: 10px 0;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rtrw-table tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 80px;
        }

        .rtrw-table tbody td:first-child {
            font-size: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 10px;
        }

        .rtrw-table tbody td:first-child:before {
            content: "📋 ";
        }

        .rtrw-table tbody td:last-child {
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            margin-top: 10px;
            display: block;
        }

        .rtrw-table tbody td:last-child:before {
            content: "";
            display: none;
        }

        .rtrw-action-buttons {
            width: 100%;
            flex-direction: column;
            gap: 10px;
        }

        .rtrw-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Badge for RT/RW */
    .rtrw-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .rtrw-badge-rt {
        background: #dbeafe;
        color: #1e40af;
    }

    .rtrw-badge-rw {
        background: #dcfce7;
        color: #166534;
    }
</style>

<div class="rtrw-management-wrapper">
    <div class="rtrw-container">
        <!-- Header -->
        <div class="rtrw-header">
            <h1 class="rtrw-title">
                <span>📊</span>
                <span>Data RT/RW</span>
            </h1>
            <button class="rtrw-btn-add" id="rtrwBtnAdd">
                <span>➕</span>
                <span>Tambah Data</span>
            </button>
        </div>

        <!-- Search -->
        <div class="rtrw-search-container">
            <span class="rtrw-search-icon">🔍</span>
            <input type="text"
                   class="rtrw-search-input"
                   id="rtrwSearchInput"
                   placeholder="Cari RT atau RW...">
        </div>

        <!-- Table -->
        <div class="rtrw-table-container">
            <table class="rtrw-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="rtrwTableBody">
                    @forelse($rts as $index => $rt)
                        <tr data-search="{{ strtolower($rt->nama_rt . ' ' . $rt->rw) }}">
                            <td data-label="No">{{ $index + 1 }}</td>
                            <td data-label="RT">
                                <span class="rtrw-badge rtrw-badge-rt">{{ $rt->nama_rt }}</span>
                            </td>
                            <td data-label="RW">
                                <span class="rtrw-badge rtrw-badge-rw">{{ $rt->rw }}</span>
                            </td>
                            <td data-label="Aksi">
                                <div class="rtrw-action-buttons">
                                    <button class="rtrw-btn rtrw-btn-edit"
                                            data-id="{{ $rt->id }}"
                                            data-nama-rt="{{ $rt->nama_rt }}"
                                            data-rw="{{ $rt->rw }}">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('superadmin.rt.destroy', $rt->id) }}"
                                          method="POST"
                                          style="display:inline; width: 100%;"
                                          class="rtrw-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rtrw-btn rtrw-btn-delete">
                                            <span>🗑️</span>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="rtrw-empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada data RT/RW</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div id="rtrwAddModal" class="rtrw-modal">
        <div class="rtrw-modal-content">
            <div class="rtrw-modal-header">
                <h2>Tambah Data RT/RW</h2>
                <button class="rtrw-modal-close" id="rtrwCloseAdd">&times;</button>
            </div>
            <div class="rtrw-modal-body">
                <form action="{{ route('superadmin.rt.store') }}" method="POST" id="rtrwAddForm">
                    @csrf
                    <div class="rtrw-form-group">
                        <label class="rtrw-form-label">
                            RT
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="nama_rt"
                               class="rtrw-form-input"
                               placeholder="Contoh: RT 001"
                               required>
                    </div>
                    <div class="rtrw-form-group">
                        <label class="rtrw-form-label">
                            RW
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="rw"
                               class="rtrw-form-input"
                               placeholder="Contoh: RW 003"
                               required>
                    </div>
                </form>
            </div>
            <div class="rtrw-modal-footer">
                <button class="rtrw-btn-modal rtrw-btn-cancel" id="rtrwCancelAdd">Batal</button>
                <button type="submit" form="rtrwAddForm" class="rtrw-btn-modal rtrw-btn-save">
                    💾 Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div id="rtrwEditModal" class="rtrw-modal">
        <div class="rtrw-modal-content">
            <div class="rtrw-modal-header">
                <h2>Edit Data RT/RW</h2>
                <button class="rtrw-modal-close" id="rtrwCloseEdit">&times;</button>
            </div>
            <div class="rtrw-modal-body">
                <form method="POST" id="rtrwEditForm" action="">
                    @csrf
                    @method('PUT')
                    <div class="rtrw-form-group">
                        <label class="rtrw-form-label">
                            RT
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="nama_rt"
                               id="rtrwEditNamaRT"
                               class="rtrw-form-input"
                               required>
                    </div>
                    <div class="rtrw-form-group">
                        <label class="rtrw-form-label">
                            RW
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                               name="rw"
                               id="rtrwEditRW"
                               class="rtrw-form-input"
                               required>
                    </div>
                </form>
            </div>
            <div class="rtrw-modal-footer">
                <button class="rtrw-btn-modal rtrw-btn-cancel" id="rtrwCancelEdit">Batal</button>
                <button type="submit" form="rtrwEditForm" class="rtrw-btn-modal rtrw-btn-save">
                    ✅ Update
                </button>
            </div>
        </div>
    </div>
</div>



@endsection
