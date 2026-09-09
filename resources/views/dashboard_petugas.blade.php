@extends('layouts.app')

@php
    $isEdit = isset($report);
    $pageTitle = $isEdit ? 'Edit Laporan STTP' : 'Buat Surat Tanda Terima Pengaduan (STTP)';
@endphp

@section('title', $pageTitle . ' - STTP Cyber Crime')
@section('page-title', $pageTitle)

@section('content')
<div class="card-custom mb-4">
    <div class="card-header-custom">
        <div>
            <h5 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>{{ $pageTitle }}
            </h5>
            <small class="text-secondary">Isi seluruh informasi laporan pengaduan masyarakat secara lengkap dan akurat</small>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="card-body p-4">
        <form action="{{ $isEdit ? route('reports.update', $report->id) : route('reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- 1. DATA PETUGAS & WAKTU -->
            <div class="card bg-body-tertiary border-0 rounded-4 p-3 mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-person-badge me-2"></i>1. Identitas Petugas & Waktu Penerimaan Laporan
                </h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Nama Petugas Penerima</label>
                        <input type="text" name="petugas_nama" class="form-control" value="{{ old('petugas_nama', $report->petugas_nama ?? Auth::user()->name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Pangkat</label>
                        <input type="text" name="petugas_pangkat" class="form-control" placeholder="Contoh: BRIPDA / BRIPKA" value="{{ old('petugas_pangkat', $report->petugas_pangkat ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">NRP / NIP</label>
                        <input type="text" name="petugas_nrp" class="form-control" placeholder="Contoh: 98010234" value="{{ old('petugas_nrp', $report->petugas_nrp ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Jabatan</label>
                        <input type="text" name="petugas_jabatan" class="form-control" placeholder="Contoh: BANIT SUBDIT V CYBER" value="{{ old('petugas_jabatan', $report->petugas_jabatan ?? 'BANIT SUBDIT V SIBER DITRESKRIMSUS POLDA SUMSEL') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tanggal Laporan</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $report->tanggal ?? date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Hari</label>
                        <input type="text" name="hari" id="hari" class="form-control" value="{{ old('hari', $report->hari ?? '') }}" placeholder="Contoh: SENIN" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jam Penerimaan</label>
                        <input type="text" name="jam" class="form-control" value="{{ old('jam', $report->jam ?? date('H:i') . ' WIB') }}" placeholder="Contoh: 10:00 WIB" required>
                    </div>
                </div>
            </div>

            <!-- 2. DATA PELAPOR -->
            <div class="card bg-body-tertiary border-0 rounded-4 p-3 mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-person-lines-fill me-2"></i>2. Identitas Pelapor (Pengadu)
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nama Lengkap Pelapor</label>
                        <input type="text" name="pelapor_nama" class="form-control" value="{{ old('pelapor_nama', $report->pelapor_nama ?? '') }}" placeholder="Nama sesuai KTP" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="pelapor_nik" class="form-control" value="{{ old('pelapor_nik', $report->pelapor_nik ?? '') }}" placeholder="16 Digit NIK" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Tempat, Tanggal Lahir (TTL)</label>
                        <input type="text" name="pelapor_ttl" class="form-control" value="{{ old('pelapor_ttl', $report->pelapor_ttl ?? '') }}" placeholder="PALEMBANG, 15 JANUARI 1995" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Agama</label>
                        <input type="text" name="pelapor_agama" class="form-control" value="{{ old('pelapor_agama', $report->pelapor_agama ?? 'ISLAM') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kewarganegaraan</label>
                        <input type="text" name="pelapor_kewarganegaraan" class="form-control" value="{{ old('pelapor_kewarganegaraan', $report->pelapor_kewarganegaraan ?? 'WNI') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">No. Telepon / WhatsApp</label>
                        <input type="text" name="pelapor_telp" class="form-control" value="{{ old('pelapor_telp', $report->pelapor_telp ?? '') }}" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat Lengkap Pelapor</label>
                        <textarea name="pelapor_alamat" class="form-control" rows="2" placeholder="Alamat domisili lengkap pelapor" required>{{ old('pelapor_alamat', $report->pelapor_alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 3. DATA KORBAN -->
            <div class="card bg-body-tertiary border-0 rounded-4 p-3 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="bi bi-person-badge-fill me-2"></i>3. Identitas Korban
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-3" onclick="copyPelaporToKorban()">
                        <i class="bi bi-copy me-1"></i> Sama Dengan Pelapor
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nama Lengkap Korban</label>
                        <input type="text" name="korban_nama" id="korban_nama" class="form-control" value="{{ old('korban_nama', $report->korban_nama ?? '') }}" placeholder="Nama sesuai KTP" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">NIK Korban</label>
                        <input type="text" name="korban_nik" id="korban_nik" class="form-control" value="{{ old('korban_nik', $report->korban_nik ?? '') }}" placeholder="16 Digit NIK" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">TTL Korban</label>
                        <input type="text" name="korban_ttl" id="korban_ttl" class="form-control" value="{{ old('korban_ttl', $report->korban_ttl ?? '') }}" placeholder="PALEMBANG, 15 JANUARI 1995" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Agama</label>
                        <input type="text" name="korban_agama" id="korban_agama" class="form-control" value="{{ old('korban_agama', $report->korban_agama ?? 'ISLAM') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kewarganegaraan</label>
                        <input type="text" name="korban_kewarganegaraan" id="korban_kewarganegaraan" class="form-control" value="{{ old('korban_kewarganegaraan', $report->korban_kewarganegaraan ?? 'WNI') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">No. Telepon Korban</label>
                        <input type="text" name="korban_telp" id="korban_telp" class="form-control" value="{{ old('korban_telp', $report->korban_telp ?? '') }}" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat Lengkap Korban</label>
                        <textarea name="korban_alamat" id="korban_alamat" class="form-control" rows="2" placeholder="Alamat domisili lengkap korban" required>{{ old('korban_alamat', $report->korban_alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 4. URAIAN KEJADIAN & BUKTI -->
            <div class="card bg-body-tertiary border-0 rounded-4 p-3 mb-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-card-text me-2"></i>4. Uraian Singkat Kejadian & Lampiran Bukti
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi Kejadian Tindak Pidana Cyber</label>
                        <textarea name="deskripsi" class="form-control" rows="5" placeholder="Tuliskan secara terperinci waktu, tempat kejadian (TKP), modus operandi, akun/nomor rekening terlapor, serta uraian kerugian korban..." required>{{ old('deskripsi', $report->deskripsi ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Upload Lampiran / Foto Bukti (Bisa Pilih Beberapa Gambar)</label>
                        <input type="file" name="bukti[]" id="buktiInput" class="form-control" multiple accept="image/*" onchange="previewImages()">
                        <div class="form-text">Format didukung: JPG, PNG, WEBP. Maksimal 5MB per file.</div>
                        
                        <!-- Live Image Preview Container -->
                        <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3">
                            @if($isEdit && $report->bukti)
                                @foreach(json_decode($report->bukti, true) ?: [] as $img)
                                    <div class="position-relative border rounded-3 p-1 bg-body">
                                        <img src="{{ asset('storage/' . $img) }}" style="width: 100px; height: 100px; object-fit: cover;" class="rounded-2">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('reports.index') }}" class="btn btn-secondary rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-3 px-5 fw-bold">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ $isEdit ? 'Perbarui STTP' : 'Simpan & Terbitkan STTP' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-update hari berdasarkan tanggal
    document.getElementById('tanggal').addEventListener('change', function() {
        const dateVal = this.value;
        if (dateVal) {
            const days = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
            const date = new Date(dateVal);
            document.getElementById('hari').value = days[date.getDay()];
        }
    });

    // Copy data pelapor ke data korban
    function copyPelaporToKorban() {
        document.getElementById('korban_nama').value = document.querySelector('input[name="pelapor_nama"]').value;
        document.getElementById('korban_nik').value = document.querySelector('input[name="pelapor_nik"]').value;
        document.getElementById('korban_ttl').value = document.querySelector('input[name="pelapor_ttl"]').value;
        document.getElementById('korban_agama').value = document.querySelector('input[name="pelapor_agama"]').value;
        document.getElementById('korban_kewarganegaraan').value = document.querySelector('input[name="pelapor_kewarganegaraan"]').value;
        document.getElementById('korban_telp').value = document.querySelector('input[name="pelapor_telp"]').value;
        document.getElementById('korban_alamat').value = document.querySelector('textarea[name="pelapor_alamat"]').value;
    }

    // Preview Images JS
    function previewImages() {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        const files = document.getElementById('buktiInput').files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative border rounded-3 p-1 bg-body';
                wrapper.innerHTML = `<img src="${e.target.result}" style="width: 100px; height: 100px; object-fit: cover;" class="rounded-2">`;
                container.appendChild(wrapper);
            }

            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection