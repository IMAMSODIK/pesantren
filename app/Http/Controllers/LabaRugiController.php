<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JurnalDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LabaRugiController extends Controller
{
    public function labaRugi(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Laba Rugi";

        // Daftar pendapatan (5xx)
        $pendapatanList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalPendapatan = $pendapatanList->sum('nominal');

        // Daftar beban (6xx)
        $bebanList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalBeban = $bebanList->sum('nominal');

        $labaBersih = $totalPendapatan - $totalBeban;

        return view('laba_rugi.index', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'pendapatanList',
            'bebanList',
            'totalPendapatan',
            'totalBeban',
            'labaBersih'
        ));
    }

    public function exportPdfLabaRugi(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Pendapatan (5xx)
        $pendapatanList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalPendapatan = $pendapatanList->sum('nominal');

        // Beban (6xx)
        $bebanList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalBeban = $bebanList->sum('nominal');

        $labaBersih = $totalPendapatan - $totalBeban;

        $pdf = Pdf::loadView('laba_rugi.pdf', compact(
            'pendapatanList',
            'bebanList',
            'totalPendapatan',
            'totalBeban',
            'labaBersih',
            'bulan',
            'tahun'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-laba-rugi-{$bulan}-{$tahun}.pdf");
    }

    public function filterLabaRugi(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Pendapatan (5xx)
        $pendapatanList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan) // hanya bulan ini
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalPendapatan = $pendapatanList->sum('nominal');

        // Beban (6xx)
        $bebanList = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan) // hanya bulan ini
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        $totalBeban = $bebanList->sum('nominal');

        $labaBersih = $totalPendapatan - $totalBeban;

        return view('laba_rugi.partials.table', compact(
            'pendapatanList',
            'bebanList',
            'totalPendapatan',
            'totalBeban',
            'labaBersih',
            'bulan',
            'tahun'
        ));
    }
}
