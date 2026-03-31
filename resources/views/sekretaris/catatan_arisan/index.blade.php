@extends('sekretaris.layouts.master')

@section('title', 'Catatan Arisan')

@section('content')


<div class="arisan-page-container">
    <!-- Header -->
    <div class="arisan-page-header">
        <div class="arisan-header-content">
            <h3>
                <span>📋</span>
                <span>Daftar Tahun Arisan</span>
            </h3>
            <a href="{{ route('sekretaris.tahun.create') }}" class="arisan-btn-add">
                <i class="fas fa-plus"></i>
                <span>Tambah Tahun</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="arisan-alert arisan-alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="arisan-alert arisan-alert-danger">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- Data Card -->
    <div class="arisan-data-card">
        <div class="arisan-card-body">
            <!-- Desktop Table View -->
            <div class="arisan-table-container">
                <table class="arisan-table">
                    <thead>
                        <tr>
                            <th style="width: 80px">No</th>
                            <th>Tahun</th>
                            <th>Jumlah Tanggal</th>
                            <th style="min-width: 280px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tahuns as $tahun)
                            <tr>
                                <td class="arisan-no-column">{{ $loop->iteration }}</td>

                                <td>
                                    <div class="arisan-tahun-column">
                                        {{ $tahun->tahun }}
                                    </div>
                                </td>

                                <td>
                                    <span class="arisan-count-badge">
                                        {{ $tahun->tanggal->count() }}
                                    </span>
                                </td>

                                <td>
                                    <div class="arisan-action-buttons">
                                        <a href="{{ route('sekretaris.tahun.show', ['tahun_id' => $tahun->id]) }}"
                                           class="arisan-btn-action arisan-btn-view">
                                            <i class="fas fa-eye"></i>
                                            <span>Lihat Detail</span>
                                        </a>

                                        <form action="{{ route('sekretaris.tanggal.delete', $tahun->id) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('⚠️ Yakin hapus tahun ini?\n\nSemua tanggal dan checklist juga akan terhapus!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="arisan-btn-action arisan-btn-delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="arisan-empty-state">
                                        <div class="arisan-empty-icon">📭</div>
                                        <div class="arisan-empty-text">
                                            Belum ada data tahun arisan.<br>
                                            <small>Klik tombol "Tambah Tahun" untuk memulai.</small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="arisan-mobile-cards">
                @forelse($tahuns as $tahun)
                    <div class="arisan-mobile-card">
                        <div class="arisan-mobile-header">
                            <div class="arisan-mobile-tahun">
                                📅 {{ $tahun->tahun }}
                            </div>
                            <div class="arisan-mobile-no">
                                {{ $loop->iteration }}
                            </div>
                        </div>

                        <div class="arisan-mobile-info">
                            <div class="arisan-mobile-info-row">
                                <span>📋</span>
                                <div style="flex: 1;">
                                    <div class="arisan-mobile-info-label">Jumlah Tanggal</div>
                                    <div class="arisan-mobile-info-value">{{ $tahun->tanggal->count() }} Tanggal</div>
                                </div>
                            </div>
                        </div>

                        <div class="arisan-mobile-actions">
                            <a href="{{ route('sekretaris.tahun.show', ['tahun_id' => $tahun->id]) }}"
                               class="arisan-btn-action arisan-btn-view">
                                <i class="fas fa-eye"></i>
                                <span>Lihat Detail</span>
                            </a>

                            <form action="{{ route('sekretaris.tanggal.delete', $tahun->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('⚠️ Yakin hapus tahun ini?\n\nSemua tanggal dan checklist juga akan terhapus!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="arisan-btn-action arisan-btn-delete">
                                    <i class="fas fa-trash"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="arisan-empty-state">
                        <div class="arisan-empty-icon">📭</div>
                        <div class="arisan-empty-text">
                            Belum ada data tahun arisan.<br>
                            <small>Klik tombol "Tambah Tahun" untuk memulai.</small>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection



