<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JurnalDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UtangPiutangController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Utang & Piutang";

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($bulan, $tahun);

        return view('utang_piutang.index', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'piutang',
            'utang',
            'totalPiutang',
            'totalUtang'
        ));
    }

    public function filter(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($bulan, $tahun);

        return view('utang_piutang.partials.table', compact(
            'piutang',
            'utang',
            'totalPiutang',
            'totalUtang'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($bulan, $tahun);

        $pdf = Pdf::loadView('utang_piutang.pdf', compact(
            'bulan',
            'tahun',
            'piutang',
            'utang',
            'totalPiutang',
            'totalUtang'
        ));

        $fileName = "laporan-utang-piutang-{$bulan}-{$tahun}.pdf";

        return $pdf->download($fileName);
    }


    private function getData($bulan, $tahun)
    {
        // ==== PIUTANG ====
        $piutang = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->whereIn('kategori_transaksis.kode', ['103', '104', '106', '107'])
            ->selectRaw('kategori_transaksis.name as nama_akun, 
                         SUM(CASE WHEN jurnal_details.posisi = "debit" 
                                  THEN jurnal_details.nominal 
                                  ELSE -jurnal_details.nominal END) as saldo')
            ->groupBy('kategori_transaksis.name')
            ->get();

        // ==== UTANG ====
        $utang = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->whereIn('kategori_transaksis.kode', ['301', '302', '303', '304', '305'])
            ->selectRaw('kategori_transaksis.name as nama_akun, 
                         SUM(CASE WHEN jurnal_details.posisi = "kredit" 
                                  THEN jurnal_details.nominal 
                                  ELSE -jurnal_details.nominal END) as saldo')
            ->groupBy('kategori_transaksis.name')
            ->get();

        $totalPiutang = $piutang->sum('saldo');
        $totalUtang   = $utang->sum('saldo');

        return [$piutang, $utang, $totalPiutang, $totalUtang];
    }
}
