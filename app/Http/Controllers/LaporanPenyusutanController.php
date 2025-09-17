<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AsetTetap;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPenyusutanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Penyusutan Aset Tetap";

        // Ambil semua aset tetap
        $asetList = AsetTetap::where('status', 1)->get()->map(function ($aset) use ($bulan, $tahun) {
            $umur = max(1, $aset->umur_ekonomis);

            // Penyusutan per bulan
            $penyusutanPerBulan = ($aset->nilai_perolehan - ($aset->nilai_sisa ?? 0)) / $umur;

            $bulanPerolehan = Carbon::parse($aset->tanggal_perolehan)->format('m');
            $tahunPerolehan = Carbon::parse($aset->tanggal_perolehan)->format('Y');

            // Lama penggunaan dalam bulan
            $lamaPemakaian = (($tahun - $tahunPerolehan) * 12) + ($bulan - $bulanPerolehan);
            if ($lamaPemakaian < 0) $lamaPemakaian = 0;

            $akumulasi = min(
                $aset->nilai_perolehan - ($aset->nilai_sisa ?? 0),
                $lamaPemakaian * $penyusutanPerBulan
            );

            $aset->akumulasi_penyusutan = $akumulasi;
            $aset->nilai_buku = $aset->nilai_perolehan - $akumulasi;

            return $aset;
        });

        return view('laporan_penyusutan.index', compact('asetList', 'bulan', 'tahun', 'pageTitle'));
    }

    public function pdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Penyusutan Aset Tetap";

        $asetList = AsetTetap::where('status', 1)->get()->map(function ($aset) use ($bulan, $tahun) {
            $umur = max(1, $aset->umur_ekonomis);
            $penyusutanPerBulan = ($aset->nilai_perolehan - ($aset->nilai_sisa ?? 0)) / $umur;
            $bulanPerolehan = Carbon::parse($aset->tanggal_perolehan)->format('m');
            $tahunPerolehan = Carbon::parse($aset->tanggal_perolehan)->format('Y');
            $lamaPemakaian = (($tahun - $tahunPerolehan) * 12) + ($bulan - $bulanPerolehan);
            if ($lamaPemakaian < 0) $lamaPemakaian = 0;

            $akumulasi = min(
                $aset->nilai_perolehan - ($aset->nilai_sisa ?? 0),
                $lamaPemakaian * $penyusutanPerBulan
            );

            $aset->akumulasi_penyusutan = $akumulasi;
            $aset->nilai_buku = $aset->nilai_perolehan - $akumulasi;

            return $aset;
        });

        $pdf = Pdf::loadView('laporan_penyusutan.pdf', compact('asetList', 'bulan', 'tahun', 'pageTitle'));
        return $pdf->download('laporan_penyusutan.pdf');
    }
}
