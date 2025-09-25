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
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-t');
        $pageTitle = "Laporan Arus Kas";

        // OPERASI
        $pendapatan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->sum('jurnal_details.nominal');

        $beban = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->sum('jurnal_details.nominal');

        $kasDariOperasi = $pendapatan - $beban;

        // INVESTASI
        $pembelianAset = AsetTetap::whereBetween('tanggal_perolehan', [$startDate, $endDate])
            ->sum('nilai_perolehan');

        $kasDariInvestasi = -$pembelianAset;

        // PENDANAAN
        $kasDariPendanaan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
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
            'startDate',
            'endDate',
            'pageTitle',
            'kasDariOperasi',
            'kasDariInvestasi',
            'kasDariPendanaan',
            'totalKasBersih'
        ));
    }


    public function pdf(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01'); // default awal bulan
        $endDate   = $request->end_date ?? date('Y-m-t');   // default akhir bulan
        $pageTitle = "Laporan Arus Kas";

        // ARUS KAS OPERASI
        $pendapatan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->sum('jurnal_details.nominal');

        $beban = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->sum('jurnal_details.nominal');

        $kasDariOperasi = $pendapatan - $beban;

        // ARUS KAS INVESTASI (pembelian aset tetap)
        $pembelianAset = AsetTetap::whereBetween('tanggal_perolehan', [$startDate, $endDate])
            ->sum('nilai_perolehan');

        $kasDariInvestasi = -$pembelianAset;

        // ARUS KAS PENDANAAN (kode akun 2xx)
        $kasDariPendanaan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '2%')
            ->sum('jurnal_details.nominal');

        $totalKasBersih = $kasDariOperasi + $kasDariInvestasi + $kasDariPendanaan;

        $pdf = Pdf::loadView('laporan_arus_kas.pdf', compact(
            'startDate',
            'endDate',
            'pageTitle',
            'kasDariOperasi',
            'kasDariInvestasi',
            'kasDariPendanaan',
            'totalKasBersih'
        ));

        return $pdf->download("laporan_arus_kas_{$startDate}_sampai_{$endDate}.pdf");
    }

    public function csv(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01'); // default awal bulan
        $endDate   = $request->end_date ?? date('Y-m-t');   // default akhir bulan
        $fileName  = "laporan_arus_kas_{$startDate}_sampai_{$endDate}.csv";

        // ARUS KAS OPERASI
        $pendapatan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '5%')
            ->where('jurnal_details.posisi', 'kredit')
            ->sum('jurnal_details.nominal');

        $beban = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '6%')
            ->where('jurnal_details.posisi', 'debit')
            ->sum('jurnal_details.nominal');

        $kasDariOperasi = $pendapatan - $beban;

        // ARUS KAS INVESTASI
        $pembelianAset = AsetTetap::whereBetween('tanggal_perolehan', [$startDate, $endDate])
            ->sum('nilai_perolehan');

        $kasDariInvestasi = -$pembelianAset;

        // ARUS KAS PENDANAAN
        $kasDariPendanaan = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->where('kategori_transaksis.kode', 'like', '2%')
            ->sum('jurnal_details.nominal');

        $totalKasBersih = $kasDariOperasi + $kasDariInvestasi + $kasDariPendanaan;

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ["Aktivitas", "Jumlah (Rp)"];

        $callback = function () use ($columns, $kasDariOperasi, $kasDariInvestasi, $kasDariPendanaan, $totalKasBersih) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            fputcsv($file, ["Arus Kas dari Aktivitas Operasi", $kasDariOperasi]);
            fputcsv($file, ["Arus Kas dari Aktivitas Investasi", $kasDariInvestasi]);
            fputcsv($file, ["Arus Kas dari Aktivitas Pendanaan", $kasDariPendanaan]);
            fputcsv($file, ["Total Kas Bersih", $totalKasBersih]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
