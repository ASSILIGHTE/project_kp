@extends('layouts.app')

@section('title', 'Riwayat Laporan STTP - Cyber Crime')
@section('page-title', 'Riwayat Laporan STTP')

@section('content')
<div class="card-custom">
    <!-- Header & Search Filter Bar -->
    <div class="card-header-custom flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-0">
                <i class="bi bi-clock-history me-2 text-primary"></i>Daftar Riwayat Laporan STTP
            </h5>
            <small class="text-secondary">Kelola, cetak, dan tinjau seluruh arsip laporan STTP Ditreskrimsus</small>
        </div>

        <form action="{{ route('reports.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-transparent border-end-0 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari Pelapor / NIK..." value="{{ request('search') }}">
            </div>

            <input type="date" name="tanggal" class="form-control form-control-sm" style="max-width: 160px;" value="{{ request('tanggal') }}">

            <button type="submit" class="btn btn-primary btn-sm rounded-3">Filter</button>

            @if(request('search') || request('tanggal'))
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">Reset</a>
            @endif
        </form>
    </div>

    <!-- Reports Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tanggal & Waktu</th>
                        <th>Identitas Pelapor</th>
                        <th>Identitas Korban</th>
                        <th>Petugas Penerima</th>
                        <th class="pe-4 text-end">Aksi & Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $key => $report)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $key + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="text-secondary small">{{ $report->hari }}, {{ $report->jam }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $report->pelapor_nama }}</div>
                                <div class="text-secondary small"><i class="bi bi-card-heading me-1"></i>NIK: {{ $report->pelapor_nik }}</div>
                                <div class="text-secondary small"><i class="bi bi-telephone me-1"></i>{{ $report->pelapor_telp }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $report->korban_nama }}</div>
                                <div class="text-secondary small">NIK: {{ $report->korban_nik }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $report->petugas_nama }}</div>
                                <div class="text-secondary small">{{ $report->petugas_pangkat }} (NRP: {{ $report->petugas_nrp }})</div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group btn-group-sm">
                                    <!-- Detail Modal -->
                                    <button class="btn btn-outline-info" title="Lihat Detail" onclick="openDetailModal({{ $report->id }})">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- Edit -->
                                    <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-outline-primary" title="Edit STTP">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <!-- Download Word -->
                                    <a href="{{ route('reports.downloadWord', $report->id) }}" class="btn btn-outline-primary" title="Unduh Word (.docx)">
                                        <i class="bi bi-file-word-fill"></i> Word
                                    </a>

                                    <!-- Download PDF -->
                                    <a href="{{ route('reports.downloadPdf', $report->id) }}" class="btn btn-outline-danger" title="Unduh PDF">
                                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="d-inline" id="delete-report-form-{{ $report->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger" title="Hapus STTP" onclick="confirmDeleteReport({{ $report->id }}, '{{ $report->pelapor_nama }}')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada riwayat laporan STTP yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail STTP -->
<div class="modal fade" id="detailReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Detail Laporan STTP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalDetailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-secondary">Memuat data STTP...</div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                <a id="modalDownloadWord" href="#" class="btn btn-primary rounded-3"><i class="bi bi-file-word me-1"></i> Unduh Word</a>
                <a id="modalDownloadPdf" href="#" class="btn btn-danger rounded-3"><i class="bi bi-file-pdf me-1"></i> Unduh PDF</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDetailModal(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailReportModal'));
        const content = document.getElementById('modalDetailContent');
        const wordBtn = document.getElementById('modalDownloadWord');
        const pdfBtn = document.getElementById('modalDownloadPdf');

        wordBtn.href = `/reports/${id}/download/word`;
        pdfBtn.href = `/reports/${id}/download/pdf`;

        content.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2 text-secondary">Memuat data STTP...</div>
            </div>
        `;
        modal.show();

        fetch(`/reports/${id}`)
            .then(res => res.json())
            .then(data => {
                let imagesHtml = '';
                if (data.bukti_urls && data.bukti_urls.length > 0) {
                    imagesHtml = '<div class="row g-2 mt-2">';
                    data.bukti_urls.forEach(url => {
                        imagesHtml += `
                            <div class="col-4 col-md-3">
                                <a href="${url}" target="_blank">
                                    <img src="${url}" class="img-fluid rounded-3 border" style="height: 100px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                        `;
                    });
                    imagesHtml += '</div>';
                } else {
                    imagesHtml = '<em class="text-secondary">Tidak ada foto bukti dilampirkan.</em>';
                }

                content.innerHTML = `
                    <div class="border-bottom pb-3 mb-3 text-center">
                        <h6 class="fw-bold text-primary mb-1">SURAT TANDA TERIMA PENGADUAN (STTP)</h6>
                        <div class="badge bg-secondary-subtle text-secondary px-3 py-1 font-monospace">${data.nomor_sttp || '-'}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-body-tertiary">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person-fill me-1"></i> DATA PELAPOR</h6>
                                <table class="table table-sm table-borderless mb-0 small">
                                    <tr><td width="110" class="text-secondary">Nama</td><td class="fw-bold">: ${data.pelapor_nama}</td></tr>
                                    <tr><td class="text-secondary">NIK</td><td>: ${data.pelapor_nik}</td></tr>
                                    <tr><td class="text-secondary">TTL</td><td>: ${data.pelapor_ttl}</td></tr>
                                    <tr><td class="text-secondary">No. Telp</td><td>: ${data.pelapor_telp}</td></tr>
                                    <tr><td class="text-secondary">Alamat</td><td>: ${data.pelapor_alamat}</td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-body-tertiary">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person-badge-fill me-1"></i> DATA KORBAN</h6>
                                <table class="table table-sm table-borderless mb-0 small">
                                    <tr><td width="110" class="text-secondary">Nama</td><td class="fw-bold">: ${data.korban_nama}</td></tr>
                                    <tr><td class="text-secondary">NIK</td><td>: ${data.korban_nik}</td></tr>
                                    <tr><td class="text-secondary">TTL</td><td>: ${data.korban_ttl}</td></tr>
                                    <tr><td class="text-secondary">No. Telp</td><td>: ${data.korban_telp}</td></tr>
                                    <tr><td class="text-secondary">Alamat</td><td>: ${data.korban_alamat}</td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-body-tertiary">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-card-text me-1"></i> URAIAN KEJADIAN</h6>
                                <p class="small text-main mb-0" style="white-space: pre-line;">${data.deskripsi}</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-body-tertiary">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-images me-1"></i> FOTO BUKTI / LAMPIRAN</h6>
                                ${imagesHtml}
                            </div>
                        </div>

                        <div class="col-12 text-end text-secondary small">
                            Laporan diterima pada: <strong>${data.hari}, ${data.formatted_tanggal} jam ${data.jam}</strong> oleh <strong>${data.petugas_nama} (${data.petugas_pangkat})</strong>
                        </div>
                    </div>
                `;
            })
            .catch(err => {
                content.innerHTML = '<div class="alert alert-danger">Gagal mengambil data detail laporan.</div>';
            });
    }

    function confirmDeleteReport(id, name) {
        Swal.fire({
            title: 'Hapus STTP ' + name + '?',
            text: "Laporan beserta seluruh lampiran bukti akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-report-form-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection