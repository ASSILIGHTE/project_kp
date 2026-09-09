@extends('layouts.app')

@section('title', 'Dashboard Petugas - STTP Cyber Crime')
@section('page-title', 'Dashboard Ringkasan Petugas')

@section('content')
<!-- Hero Welcome Banner -->
<div class="card-custom p-4 mb-4 bg-gradient-primary text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
    <div class="row align-items-center position-relative" style="z-index: 1;">
        <div class="col-md-8">
            <h4 class="fw-extrabold mb-1">Selamat Datang, {{ Auth::user()->name }} 👋</h4>
            <p class="text-white-50 mb-3" style="font-size: 0.95rem;">
                Sistem Pembuatan & Pengelolaan Surat Tanda Terima Pengaduan (STTP) Ditreskrimsus Polda Sumsel.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.petugas') }}" class="btn btn-light rounded-3 px-3 fw-bold text-primary">
                    <i class="bi bi-file-earmark-plus-fill me-1"></i> Buat STTP Baru
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-light rounded-3 px-3">
                    <i class="bi bi-clock-history me-1"></i> Lihat Semua Riwayat
                </a>
            </div>
        </div>
        <div class="col-md-4 d-none d-md-block text-end">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo Polda" style="max-height: 120px; width: auto; opacity: 0.9;" class="img-fluid">
            @elseif(file_exists(public_path('images/logo1.png')))
                <img src="{{ asset('images/logo1.png') }}" alt="Logo Polda" style="max-height: 120px; width: auto; opacity: 0.9;" class="img-fluid">
            @else
                <i class="bi bi-shield-check opacity-50" style="font-size: 7rem;"></i>
            @endif
        </div>
    </div>
</div>

<!-- Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-semibold text-uppercase">Total STTP Saya</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $totalReports ?? 0 }}</h3>
                </div>
                <div class="rounded-4 p-3 bg-primary-subtle text-primary">
                    <i class="bi bi-folder-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-semibold text-uppercase">Dibuat Hari Ini</span>
                    <h3 class="fw-bold mb-0 mt-1 text-success">{{ $reportsToday ?? 0 }}</h3>
                </div>
                <div class="rounded-4 p-3 bg-success-subtle text-success">
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-semibold text-uppercase">Dibuat Bulan Ini</span>
                    <h3 class="fw-bold mb-0 mt-1 text-info">{{ $reportsThisMonth ?? 0 }}</h3>
                </div>
                <div class="rounded-4 p-3 bg-info-subtle text-info">
                    <i class="bi bi-calendar3 fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Laporan Terbaru Table -->
<div class="card-custom">
    <div class="card-header-custom">
        <div>
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Laporan STTP Terbaru</h5>
            <small class="text-secondary">5 laporan STTP terakhir yang Anda terbitkan</small>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary btn-sm rounded-3">Lihat Semua</a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tanggal</th>
                        <th>Pelapor</th>
                        <th>Korban</th>
                        <th class="pe-4 text-end">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports ?? [] as $i => $report)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="fw-semibold">{{ $report->pelapor_nama }}</span>
                                <div class="text-secondary small">NIK: {{ $report->pelapor_nik }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $report->korban_nama }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group btn-group-sm">
                                    <a class="btn btn-primary" href="{{ route('reports.downloadWord', $report->id) }}" title="Unduh Word">
                                        <i class="bi bi-file-word-fill me-1"></i> Word
                                    </a>
                                    <a class="btn btn-danger" href="{{ route('reports.downloadPdf', $report->id) }}" title="Unduh PDF">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">
                                Belum ada laporan yang dibuat. Silakan buat laporan baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
