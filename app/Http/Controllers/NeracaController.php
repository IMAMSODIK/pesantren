<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AsetTetap;
use App\Models\JurnalDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Neraca";

        // ========== ASET LANCAR (Kode 1xx dari jurnal) ==========
        $asetList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, 
                SUM(CASE WHEN jurnal_details.posisi = "debit" 
                         THEN jurnal_details.nominal 
                         ELSE -jurnal_details.nominal END) as saldo')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '1%')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalAsetLancar = $asetList->sum('saldo');

        // ========== ASET TETAP (dari tabel aset_tetaps) ==========
        $asetTetapList = DB::table('aset_tetaps')
            ->select('nama', 'nilai_perolehan', 'nilai_sisa', 'umur_ekonomis', 'tanggal_perolehan')
            ->whereYear('tanggal_perolehan', '<=', $tahun)
            ->get()
            ->map(function ($aset) use ($bulan, $tahun) {
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

        $totalAsetTetap = $asetTetapList->sum('nilai_buku');

        // ========== TOTAL ASET ==========
        $totalAset = $totalAsetLancar + $totalAsetTetap;

        // ========== LIABILITAS ==========
        $liabilitasList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, 
                SUM(CASE WHEN jurnal_details.posisi = "kredit" 
                         THEN jurnal_details.nominal 
                         ELSE -jurnal_details.nominal END) as saldo')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '2%')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalLiabilitas = $liabilitasList->sum('saldo');

        // ========== EKUITAS ==========
        $ekuitasList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, 
                SUM(CASE WHEN jurnal_details.posisi = "kredit" 
                         THEN jurnal_details.nominal 
                         ELSE -jurnal_details.nominal END) as saldo')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '3%')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalEkuitas = $ekuitasList->sum('saldo');

        // ========== TOTAL PASSIVA ==========
        $totalPassiva = $totalLiabilitas + $totalEkuitas;

        return view('neraca.index', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'asetList',
            'asetTetapList',
            'liabilitasList',
            'ekuitasList',
            'totalAsetLancar',
            'totalAsetTetap',
            'totalAset',
            'totalLiabilitas',
            'totalEkuitas',
            'totalPassiva'
        ));
    }

    public function exportPdf()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $pageTitle = "Laporan Neraca {$bulan}/{$tahun}";

        // ========== ASET LANCAR ==========
        $asetList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, 
                SUM(CASE WHEN jurnal_details.posisi = "debit" 
                         THEN jurnal_details.nominal 
                         ELSE -jurnal_details.nominal END) as saldo')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '1%')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalAsetLancar = $asetList->sum('saldo');

        // ========== ASET TETAP ==========
        $asetTetapList = AsetTetap::where('status', 1)
            ->whereYear('tanggal_perolehan', '<=', $tahun)
            ->get()
            ->map(function ($aset) use ($bulan, $tahun) {
                $umur = max(1, $aset->umur_ekonomis);
                $penyusutanPerBulan = ($aset->nilai_perolehan - $aset->nilai_sisa) / $umur;

                $bulanPerolehan = Carbon::parse($aset->tanggal_perolehan)->format('m');
                $tahunPerolehan = Carbon::parse($aset->tanggal_perolehan)->format('Y');

                $lamaPemakaian = (($tahun - $tahunPerolehan) * 12) + ($bulan - $bulanPerolehan);
                if ($lamaPemakaian < 0) $lamaPemakaian = 0;

                $akumulasi = min($aset->nilai_perolehan - $aset->nilai_sisa, $lamaPemakaian * $penyusutanPerBulan);

                $aset->akumulasi_penyusutan = $akumulasi;
                $aset->nilai_buku = $aset->nilai_perolehan - $akumulasi;

                return $aset;
            });

        $totalAsetTetap = $asetTetapList->sum('nilai_buku');
        $totalAset = $totalAsetLancar + $totalAsetTetap;

        // ========== LIABILITAS ==========
        $liabilitasList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, 
                SUM(CASE WHEN jurnal_details.posisi = "kredit" 
                         THEN jurnal_details.nominal 
                         ELSE -jurnal_details.nominal END) as saldo')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '2%')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalLiabilitas = $liabilitasList->sum('saldo');

        // ========== EKUITAS ==========
        $ekuitasList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, 
                SUM(CASE WHEN jurnal_details.posisi = "kredit" 
                         THEN jurnal_details.nominal 
                         ELSE -jurnal_details.nominal END) as saldo')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '3%')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalEkuitas = $ekuitasList->sum('saldo');
        $totalPassiva = $totalLiabilitas + $totalEkuitas;

        // ========== Generate PDF ==========
        $pdf = Pdf::loadView('neraca.pdf', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'asetList',
            'asetTetapList',
            'liabilitasList',
            'ekuitasList',
            'totalAsetLancar',
            'totalAsetTetap',
            'totalAset',
            'totalLiabilitas',
            'totalEkuitas',
            'totalPassiva'
        ));

        return $pdf->download("Neraca_{$bulan}_{$tahun}.pdf");
    }
}
