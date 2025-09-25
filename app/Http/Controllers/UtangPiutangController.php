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
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-t');
        $pageTitle = "Laporan Utang & Piutang";

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($startDate, $endDate);

        return view('utang_piutang.index', compact(
            'startDate',
            'endDate',
            'pageTitle',
            'piutang',
            'utang',
            'totalPiutang',
            'totalUtang'
        ));
    }

    public function filter(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($startDate, $endDate);

        return view('utang_piutang.partials.table', compact(
            'piutang',
            'utang',
            'totalPiutang',
            'totalUtang'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-t');

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($startDate, $endDate);

        $pdf = Pdf::loadView('utang_piutang.pdf', compact(
            'startDate',
            'endDate',
            'piutang',
            'utang',
            'totalPiutang',
            'totalUtang'
        ));

        $fileName = "laporan-utang-piutang-{$startDate}-sampai-{$endDate}.pdf";
        return $pdf->download($fileName);
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-t');
        $fileName  = "laporan-utang-piutang-{$startDate}-sampai-{$endDate}.csv";

        [$piutang, $utang, $totalPiutang, $totalUtang] = $this->getData($startDate, $endDate);

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($piutang, $utang, $totalPiutang, $totalUtang) {
            $file = fopen('php://output', 'w');

            // === Bagian PIUTANG ===
            fputcsv($file, ["LAPORAN PIUTANG"]);
            fputcsv($file, ["Nama Akun", "Saldo (Rp)"]);
            foreach ($piutang as $p) {
                fputcsv($file, [$p->nama_akun, $p->saldo]);
            }
            fputcsv($file, ["Total Piutang", $totalPiutang]);

            fputcsv($file, []); // baris kosong sebagai pemisah

            // === Bagian UTANG ===
            fputcsv($file, ["LAPORAN UTANG"]);
            fputcsv($file, ["Nama Akun", "Saldo (Rp)"]);
            foreach ($utang as $u) {
                fputcsv($file, [$u->nama_akun, $u->saldo]);
            }
            fputcsv($file, ["Total Utang", $totalUtang]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    private function getData($startDate, $endDate)
    {
        // PIUTANG
        $piutang = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->whereIn('kategori_transaksis.kode', ['103', '104', '106', '107'])
            ->selectRaw('kategori_transaksis.name as nama_akun,
                     SUM(CASE WHEN jurnal_details.posisi = "debit"
                              THEN jurnal_details.nominal 
                              ELSE -jurnal_details.nominal END) as saldo')
            ->groupBy('kategori_transaksis.name')
            ->get();

        // UTANG
        $utang = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
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
