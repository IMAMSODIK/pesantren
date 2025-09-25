<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JurnalDetail;
use App\Models\KategoriTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NeracaSaldoController extends Controller
{
    public function neracaSaldo(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $pageTitle = 'Neraca Saldo';

        $akunList = KategoriTransaksi::all();

        $data = [];
        foreach ($akunList as $akun) {
            $debit = JurnalDetail::where('kategori_transaksi_id', $akun->id)
                ->whereHas('transaksi', function ($q) use ($tahun, $bulan) {
                    $q->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', '<=', $bulan);
                })
                ->where('posisi', 'debit')
                ->sum('nominal');

            $kredit = JurnalDetail::where('kategori_transaksi_id', $akun->id)
                ->whereHas('transaksi', function ($q) use ($tahun, $bulan) {
                    $q->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', '<=', $bulan);
                })
                ->where('posisi', 'kredit')
                ->sum('nominal');

            $saldo = $debit - $kredit;

            if ($saldo != 0) {
                $data[] = [
                    'kode'   => $akun->kode,
                    'nama'   => $akun->name,
                    'debit'  => $saldo > 0 ? $saldo : 0,
                    'kredit' => $saldo < 0 ? abs($saldo) : 0,
                ];
            }
        }

        return view('neraca_saldo.index', compact('data', 'bulan', 'tahun', 'pageTitle'));
    }

    public function filterNeracaSaldo(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $data = $this->getDataNeracaSaldo($startDate, $endDate);

        return view('neraca_saldo.partials.table', compact('data', 'startDate', 'endDate'))->render();
    }

    public function exportNeracaSaldoPDF(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $data = $this->getDataNeracaSaldo($startDate, $endDate);

        $pdf = Pdf::loadView('neraca_saldo.pdf', [
            'data'      => $data,
            'startDate' => $startDate,
            'endDate'   => $endDate
        ])->setPaper('a4', 'portrait');

        return $pdf->download("neraca_saldo_{$startDate}_sampai_{$endDate}.pdf");
    }

    private function getDataNeracaSaldo($startDate, $endDate)
    {
        $akunList = KategoriTransaksi::all();
        $data = [];

        foreach ($akunList as $akun) {
            $debit = JurnalDetail::where('kategori_transaksi_id', $akun->id)
                ->whereHas('transaksi', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                })
                ->where('posisi', 'debit')
                ->sum('nominal');

            $kredit = JurnalDetail::where('kategori_transaksi_id', $akun->id)
                ->whereHas('transaksi', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                })
                ->where('posisi', 'kredit')
                ->sum('nominal');

            $saldo = $debit - $kredit;

            if ($saldo != 0) {
                $data[] = [
                    'kode'   => $akun->kode,
                    'nama'   => $akun->name,
                    'debit'  => $saldo > 0 ? $saldo : 0,
                    'kredit' => $saldo < 0 ? abs($saldo) : 0,
                ];
            }
        }

        return $data;
    }

    public function exportNeracaSaldoCSV(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;
        $data  = $this->getDataNeracaSaldo($start, $end);

        $filename = "neraca_saldo_{$start}_{$end}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Akun', 'Nama Akun', 'Debit', 'Kredit']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row['kode'],
                    $row['nama'],
                    $row['debit'],
                    $row['kredit']
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
