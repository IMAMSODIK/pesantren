<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AsetTetap;
use App\Models\JurnalDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Arus Kas";

        // ARUS KAS OPERASI (pendapatan - beban)
        $pendapatan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan) // hanya bulan yang dipilih
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->sum('jurnal_details.nominal');

        $beban = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan) // hanya bulan yang dipilih
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->sum('jurnal_details.nominal');

        $kasDariOperasi = $pendapatan - $beban;

        // ARUS KAS INVESTASI (pembelian aset tetap)
        $pembelianAset = AsetTetap::whereYear('tanggal_perolehan', $tahun)
            ->whereMonth('tanggal_perolehan', $bulan) // hanya bulan yang dipilih
            ->sum('nilai_perolehan');

        $kasDariInvestasi = -$pembelianAset;

        // ARUS KAS PENDANAAN (kode akun 2xx)
        $kasDariPendanaan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan) // hanya bulan yang dipilih
            ->where('kategori_transaksis.kode', 'like', '2%')
            ->sum('jurnal_details.nominal');

        $totalKasBersih = $kasDariOperasi + $kasDariInvestasi + $kasDariPendanaan;

        if ($request->ajax()) {
            return view('laporan_arus_kas.partials.table', compact(
                'kasDariOperasi',
                'kasDariInvestasi',
                'kasDariPendanaan',
                'totalKasBersih'
            ))->render();
        }

        return view('laporan_arus_kas.index', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'kasDariOperasi',
            'kasDariInvestasi',
            'kasDariPendanaan',
            'totalKasBersih'
        ));
    }


    public function pdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = "Laporan Arus Kas";

        // ARUS KAS OPERASI
        $pendapatan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->sum('jurnal_details.nominal');

        $beban = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->sum('jurnal_details.nominal');

        $kasDariOperasi = $pendapatan - $beban;

        // ARUS KAS INVESTASI
        $pembelianAset = AsetTetap::whereYear('tanggal_perolehan', $tahun)
            ->whereMonth('tanggal_perolehan', $bulan)
            ->sum('nilai_perolehan');

        $kasDariInvestasi = -$pembelianAset;

        // ARUS KAS PENDANAAN
        $kasDariPendanaan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', $bulan)
            ->where('kategori_transaksis.kode', 'like', '2%')
            ->sum('jurnal_details.nominal');

        $totalKasBersih = $kasDariOperasi + $kasDariInvestasi + $kasDariPendanaan;

        $pdf = Pdf::loadView('laporan_arus_kas.pdf', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'kasDariOperasi',
            'kasDariInvestasi',
            'kasDariPendanaan',
            'totalKasBersih'
        ));

        return $pdf->download("laporan_arus_kas_{$bulan}_{$tahun}.pdf");
    }
}
