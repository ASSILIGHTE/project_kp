<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /* --------------------------------------------------------------------------
     *  DASHBOARD RINGKASAN PETUGAS
     * ------------------------------------------------------------------------*/
    public function dashboard()
    {
        $userId = Auth::id();

        $totalReports = Report::where('user_id', $userId)->count();
        $reportsToday = Report::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $reportsThisMonth = Report::where('user_id', $userId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $recentReports = Report::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard_petugas_home', compact(
            'totalReports',
            'reportsToday',
            'reportsThisMonth',
            'recentReports'
        ));
    }

    /* --------------------------------------------------------------------------
     *  HELPER : KONVERSI ANGKA KE ROMAWI
     * ------------------------------------------------------------------------*/
    private function intToRoman($num)
    {
        $map = [
            'M'  => 1000, 'CM' => 900,
            'D'  => 500,  'CD' => 400,
            'C'  => 100,  'XC' => 90,
            'L'  => 50,   'XL' => 40,
            'X'  => 10,   'IX' => 9,
            'V'  => 5,    'IV' => 4,
            'I'  => 1
        ];

        $returnValue = '';
        foreach ($map as $roman => $value) {
            while ($num >= $value) {
                $returnValue .= $roman;
                $num -= $value;
            }
        }
        return $returnValue;
    }

    /* --------------------------------------------------------------------------
     *  GENERATE NOMOR STTP
     * ------------------------------------------------------------------------*/
    private function generateNomor()
    {
        $year = date('Y');
        $count = Report::whereYear('created_at', $year)->count() + 1;
        $roman = $this->intToRoman($count);

        return "STTP/{$roman}/V/RES.2.5./{$year}/Ditreskrimsus";
    }

    private function safe($value)
    {
        return htmlspecialchars((string)$value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    /* --------------------------------------------------------------------------
     *  CREATE FORM LAPORAN
     * ------------------------------------------------------------------------*/
    public function create()
    {
        return view('dashboard_petugas');
    }

    /* --------------------------------------------------------------------------
     *  STORE LAPORAN BARU
     * ------------------------------------------------------------------------*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'petugas_nama' => 'required|string',
            'petugas_pangkat' => 'required|string',
            'petugas_nrp' => 'required|string',
            'petugas_jabatan' => 'required|string',
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required|string',
            'pelapor_nama' => 'required|string',
            'pelapor_nik' => 'required|string',
            'pelapor_ttl' => 'required|string',
            'pelapor_agama' => 'required|string',
            'pelapor_kewarganegaraan' => 'required|string',
            'pelapor_alamat' => 'required|string',
            'pelapor_telp' => 'required|string',
            'korban_nama' => 'required|string',
            'korban_nik' => 'required|string',
            'korban_ttl' => 'required|string',
            'korban_agama' => 'required|string',
            'korban_kewarganegaraan' => 'required|string',
            'korban_alamat' => 'required|string',
            'korban_telp' => 'required|string',
            'deskripsi' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $validated['user_id'] = Auth::id();

        // Upload foto bukti jika ada
        if ($request->hasFile('bukti')) {
            $paths = [];
            foreach ($request->file('bukti') as $file) {
                $paths[] = $file->store('bukti', 'public');
            }
            $validated['bukti'] = json_encode($paths);
        }

        $report = Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Laporan STTP berhasil disimpan!');
    }

    /* --------------------------------------------------------------------------
     *  INDEX RIWAYAT LAPORAN
     * ------------------------------------------------------------------------*/
    public function index(Request $request)
    {
        $query = Report::query();

        // Jika user adalah petugas, hanya tampilkan laporan miliknya
        if (Auth::user()->role === 'petugas') {
            $query->where('user_id', Auth::id());
        }

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pelapor_nama', 'like', "%{$search}%")
                  ->orWhere('pelapor_nik', 'like', "%{$search}%")
                  ->orWhere('korban_nama', 'like', "%{$search}%")
                  ->orWhere('petugas_nama', 'like', "%{$search}%");
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $reports = $query->latest()->get();

        return view('riwayat', compact('reports'));
    }

    /* --------------------------------------------------------------------------
     *  SHOW DETAIL LAPORAN (API JSON / MODAL)
     * ------------------------------------------------------------------------*/
    public function show($id)
    {
        $report = Report::findOrFail($id);
        
        // Authorization check if petugas
        if (Auth::user()->role === 'petugas' && $report->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $report->formatted_tanggal = Carbon::parse($report->tanggal)->translatedFormat('d F Y');
        $report->nomor_sttp = $this->generateNomor();
        $report->bukti_urls = [];

        if ($report->bukti) {
            $images = json_decode($report->bukti, true) ?: [];
            foreach ($images as $img) {
                $report->bukti_urls[] = asset('storage/' . $img);
            }
        }

        return response()->json($report);
    }

    /* --------------------------------------------------------------------------
     *  EDIT FORM LAPORAN
     * ------------------------------------------------------------------------*/
    public function edit($id)
    {
        $report = Report::findOrFail($id);

        if (Auth::user()->role === 'petugas' && $report->user_id !== Auth::id()) {
            return redirect()->route('reports.index')->with('error', 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        return view('dashboard_petugas', compact('report'));
    }

    /* --------------------------------------------------------------------------
     *  UPDATE LAPORAN
     * ------------------------------------------------------------------------*/
    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if (Auth::user()->role === 'petugas' && $report->user_id !== Auth::id()) {
            return redirect()->route('reports.index')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'petugas_nama' => 'required|string',
            'petugas_pangkat' => 'required|string',
            'petugas_nrp' => 'required|string',
            'petugas_jabatan' => 'required|string',
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required|string',
            'pelapor_nama' => 'required|string',
            'pelapor_nik' => 'required|string',
            'pelapor_ttl' => 'required|string',
            'pelapor_agama' => 'required|string',
            'pelapor_kewarganegaraan' => 'required|string',
            'pelapor_alamat' => 'required|string',
            'pelapor_telp' => 'required|string',
            'korban_nama' => 'required|string',
            'korban_nik' => 'required|string',
            'korban_ttl' => 'required|string',
            'korban_agama' => 'required|string',
            'korban_kewarganegaraan' => 'required|string',
            'korban_alamat' => 'required|string',
            'korban_telp' => 'required|string',
            'deskripsi' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Handling foto bukti tambahan/baru
        if ($request->hasFile('bukti')) {
            $existing = json_decode($report->bukti, true) ?: [];
            foreach ($request->file('bukti') as $file) {
                $existing[] = $file->store('bukti', 'public');
            }
            $validated['bukti'] = json_encode($existing);
        }

        $report->update($validated);

        return redirect()->route('reports.index')->with('success', 'Laporan STTP berhasil diperbarui!');
    }

    /* --------------------------------------------------------------------------
     *  DOWNLOAD WORD
     * ------------------------------------------------------------------------*/
    public function downloadWord($id)
    {
        $report = Report::findOrFail($id);
        $nomor = $this->generateNomor();

        $formattedDate = Carbon::parse($report->tanggal)->translatedFormat('d F Y');
        $tanggalOnly = $formattedDate;

        $templatePath = storage_path('app/templates/sttp_template (2).docx');
        if (!file_exists($templatePath)) {
            // Check fallback template
            $templatePath = storage_path('app/templates/sttp_template.docx');
        }

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template Word (sttp_template.docx) tidak ditemukan di storage/app/templates.');
        }

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tpl = new TemplateProcessor($templatePath);

        // Fill template fields
        $tpl->setValue('nomor', $this->safe($nomor));
        $tpl->setValue('tanggal_surat', $this->safe($tanggalOnly));
        $tpl->setValue('lokasi_surat', 'Palembang');

        $tpl->setValue('hari', $this->safe(strtoupper($report->hari)));
        $tpl->setValue('tanggal', $this->safe($formattedDate));
        $tpl->setValue('jam', $this->safe($report->jam));

        // Petugas
        $tpl->setValue('petugas_nama', $this->safe(strtoupper($report->petugas_nama)));
        $tpl->setValue('petugas_pangkat', $this->safe($report->petugas_pangkat));
        $tpl->setValue('petugas_nrp', $this->safe($report->petugas_nrp));
        $tpl->setValue('petugas_jabatan', $this->safe($report->petugas_jabatan));

        // Pelapor
        $tpl->setValue('pelapor_nama', $this->safe(strtoupper($report->pelapor_nama)));
        $tpl->setValue('pelapor_nik', $this->safe($report->pelapor_nik));
        $tpl->setValue('pelapor_ttl', $this->safe(strtoupper($report->pelapor_ttl)));
        $tpl->setValue('pelapor_agama', $this->safe(strtoupper($report->pelapor_agama)));
        $tpl->setValue('pelapor_kewarganegaraan', $this->safe(strtoupper($report->pelapor_kewarganegaraan)));
        $tpl->setValue('pelapor_alamat', $this->safe(strtoupper($report->pelapor_alamat)));
        $tpl->setValue('pelapor_telp', $this->safe($report->pelapor_telp));

        // Korban
        $tpl->setValue('korban_nama', $this->safe(strtoupper($report->korban_nama)));
        $tpl->setValue('korban_nik', $this->safe($report->korban_nik));
        $tpl->setValue('korban_ttl', $this->safe(strtoupper($report->korban_ttl)));
        $tpl->setValue('korban_agama', $this->safe(strtoupper($report->korban_agama)));
        $tpl->setValue('korban_kewarganegaraan', $this->safe(strtoupper($report->korban_kewarganegaraan)));
        $tpl->setValue('korban_alamat', $this->safe(strtoupper($report->korban_alamat)));
        $tpl->setValue('korban_telp', $this->safe($report->korban_telp));

        $tpl->setValue('deskripsi', $this->safe($report->deskripsi));

        // Handle Images
        if ($report->bukti) {
            $images = json_decode($report->bukti, true) ?: [];

            foreach ($images as $index => $imgPath) {
                $placeholder = $index == 0 ? 'bukti_image' : 'bukti_image' . ($index + 1);
                $full = storage_path('app/public/' . $imgPath);

                if (file_exists($full)) {
                    $tpl->setImageValue($placeholder, [
                        'path' => $full,
                        'width' => 380,
                        'height' => 280,
                        'ratio' => true
                    ]);
                }
            }

            $totalPlaceholder = 6;
            for ($i = count($images) + 1; $i <= $totalPlaceholder; $i++) {
                $placeholder = $i == 1 ? 'bukti_image' : 'bukti_image' . $i;
                $tpl->setValue($placeholder, '');
            }
        }

        $output = storage_path("app/temp/STTP-$id.docx");
        $tpl->saveAs($output);

        return response()->download($output)->deleteFileAfterSend(true);
    }

    /* --------------------------------------------------------------------------
     *  DOWNLOAD PDF
     * ------------------------------------------------------------------------*/
    public function downloadPdf($id)
    {
        $report = Report::findOrFail($id);
        $nomor = $this->generateNomor();

        $formattedDate = Carbon::parse($report->tanggal)->translatedFormat('d F Y');

        $data = [
            'report' => $report,
            'nomor' => $nomor,
            'tanggalSurat' => $formattedDate,
        ];

        $pdf = Pdf::loadView('sttp', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download('STTP_' . $id . '.pdf');
    }

    /* --------------------------------------------------------------------------
     *  DELETE LAPORAN
     * ------------------------------------------------------------------------*/
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if (Auth::user()->role === 'petugas' && $report->user_id !== Auth::id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($report->bukti) {
            $images = json_decode($report->bukti, true) ?: [];
            foreach ($images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $report->delete();

        return back()->with('success', 'Laporan STTP berhasil dihapus.');
    }
}