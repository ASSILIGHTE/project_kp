<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>STTP {{ $report->id ?? '' }}</title>
    <style>
        @page { 
            size: A4; 
            margin: 1.5cm 1.5cm 1.5cm 1.5cm; 
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-title {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
        }
        .kop-sub {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
        }
        .nomor-surat {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 3px;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
        }
        .indent { margin-left: 15px; }
        .bold { font-weight: bold; }

        .table-field {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            margin-bottom: 6px;
        }
        .table-field td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .signature-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .bukti-title {
            text-align: center;
            font-weight: bold;
            margin: 30px 0 15px 0;
            text-decoration: underline;
            page-break-before: auto;
        }
        .bukti-image {
            max-width: 450px;
            max-height: 300px;
            margin: 10px auto;
            display: block;
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <!-- Kop Surat (Sesuai template sttp_template (2)) -->
    <table class="kop-table">
        <tr>
            <td style="width: 75px; text-align: center; vertical-align: middle;">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" style="width: 65px; height: auto;">
                @elseif(file_exists(public_path('images/logo1.png')))
                    <img src="{{ public_path('images/logo1.png') }}" style="width: 65px; height: auto;">
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <div class="kop-title">KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
                <div class="kop-sub">DAERAH SUMATERA SELATAN</div>
                <div class="kop-sub">DIREKTORAT RESERSE KRIMINAL KHUSUS</div>
            </td>
        </tr>
    </table>

    <!-- Judul & Nomor Surat -->
    <div class="nomor-surat">
        SURAT TANDA TERIMA PENGADUAN
    </div>
    <div style="text-align: center; font-size: 11pt; margin-bottom: 15px;">
        Nomor : {{ $nomor ?? 'STTP/V/RES.2.5./2025/Ditreskrimsus' }}
    </div>

    <!-- Identitas Petugas -->
    <div>Yang bertanda tangan dibawah ini saya:</div>
    <div class="indent">
        <table class="table-field">
            <tr>
                <td style="width: 130px;">Nama</td>
                <td style="width: 10px;">:</td>
                <td>{{ strtoupper($report->petugas_nama ?? '') }}</td>
            </tr>
            <tr>
                <td>Pangkat / NRP</td>
                <td>:</td>
                <td>{{ $report->petugas_pangkat ?? '' }} Nrp {{ $report->petugas_nrp ?? '' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $report->petugas_jabatan ?? '' }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 8px; text-align: justify;">
        pada kantor tersebut diatas menerangkan dengan sebenarnya bahwa pada hari {{ $report->hari ?? '' }} tanggal {{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }} Jam {{ $report->jam ?? '' }} WIB waktu setempat, telah datang ke Subdit V Siber seseorang mengaku:
    </div>

    <!-- Selaku Penasehat Hukum / Pelapor -->
    <div class="bold" style="margin-top: 10px;">Selaku Penasehat Hukum / Pelapor</div>
    <div class="indent">
        <table class="table-field">
            <tr><td style="width: 170px;">NAMA</td><td style="width: 10px;">:</td><td>{{ strtoupper($report->pelapor_nama ?? '') }}</td></tr>
            <tr><td>NIK</td><td>:</td><td>{{ $report->pelapor_nik ?? '' }}</td></tr>
            <tr><td>TEMPAT, TGL LAHIR</td><td>:</td><td>{{ strtoupper($report->pelapor_ttl ?? '') }}</td></tr>
            <tr><td>AGAMA</td><td>:</td><td>{{ strtoupper($report->pelapor_agama ?? '') }}</td></tr>
            <tr><td>KEWARGANEGARAAN</td><td>:</td><td>{{ strtoupper($report->pelapor_kewarganegaraan ?? '') }}</td></tr>
            <tr><td>ALAMAT</td><td>:</td><td>{{ strtoupper($report->pelapor_alamat ?? '') }}</td></tr>
            <tr><td>TELP / FAX / EMAIL</td><td>:</td><td>{{ $report->pelapor_telp ?? '' }}</td></tr>
        </table>
    </div>

    <!-- Selaku Korban/Pelapor -->
    <div class="bold" style="margin-top: 10px;">Selaku Korban/Pelapor</div>
    <div class="indent">
        <table class="table-field">
            <tr><td style="width: 170px;">NAMA</td><td style="width: 10px;">:</td><td>{{ strtoupper($report->korban_nama ?? '') }}</td></tr>
            <tr><td>NIK</td><td>:</td><td>{{ $report->korban_nik ?? '' }}</td></tr>
            <tr><td>TEMPAT, TGL LAHIR</td><td>:</td><td>{{ strtoupper($report->korban_ttl ?? '') }}</td></tr>
            <tr><td>AGAMA</td><td>:</td><td>{{ strtoupper($report->korban_agama ?? '') }}</td></tr>
            <tr><td>KEWARGANEGARAAN</td><td>:</td><td>{{ strtoupper($report->korban_kewarganegaraan ?? '') }}</td></tr>
            <tr><td>ALAMAT</td><td>:</td><td>{{ strtoupper($report->korban_alamat ?? '') }}</td></tr>
            <tr><td>TELP / FAX / EMAIL</td><td>:</td><td>{{ $report->korban_telp ?? '' }}</td></tr>
        </table>
    </div>

    <!-- Uraian Kejadian -->
    <div style="margin-top: 15px; text-align: justify;">
        --------- {{ $report->deskripsi ?? '' }}
    </div>

    <!-- Penutup -->
    <div style="margin-top: 15px; text-align: justify;">
        Demikian Surat Tanda Penerimaan Pengaduan ini dibuat untuk dapat dipergunakan seperlunya.
    </div>

    <!-- Tanda Tangan Table (Sesuai sttp_template (2)) -->
    <table class="signature-table">
        <tr>
            <td>
                Penasehat Hukum / Pelapor
                <br><br><br><br><br>
                ( {{ strtoupper($report->pelapor_nama ?? '-') }} )
            </td>
            <td>
                Palembang, {{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }}<br>
                Penerima Pengaduan
                <br><br><br><br>
                <strong><u>{{ strtoupper($report->petugas_nama ?? '-') }}</u></strong><br>
                NRP {{ $report->petugas_nrp ?? '' }}
            </td>
        </tr>
    </table>

    <!-- Bukti Pendukung Lampiran -->
    @if($report->bukti)
        <div class="bukti-title">BUKTI PENDUKUNG LAPORAN</div>
        @foreach(json_decode($report->bukti, true) ?: [] as $path)
            @if(file_exists(storage_path('app/public/' . $path)))
                <div style="text-align: center; margin-bottom: 15px;">
                    <img src="{{ storage_path('app/public/' . $path) }}" class="bukti-image">
                </div>
            @endif
        @endforeach
    @endif
</body>
</html>