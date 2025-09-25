<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JurnalDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function jurnalUmum(Request $request)
    {
        $periode = $request->get('periode', now()->format('Y-m'));
        [$start, $end] = $this->periodeBounds($periode);

        $jurnals = JurnalDetail::with(['transaksi', 'kategoriTransaksi'])
            ->whereHas('transaksi', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            })
            ->orderBy('transaksi_id')
            ->orderByRaw("FIELD(posisi, 'debit','kredit')")
            ->get();

        $rows = [];
        foreach ($jurnals as $j) {
            $rows[] = [
                'tanggal'    => $j->transaksi->tanggal,
                'deskripsi'  => $j->transaksi->deskripsi,
                'akun'       => $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name,
                'debit'      => $j->posisi === 'debit' ? 'Rp. ' . number_format($j->nominal, 2, '.', ',') : '',
                'kredit'     => $j->posisi === 'kredit' ? 'Rp. ' . number_format($j->nominal, 2, '.', ',') : '',
            ];
        }

        return view('laporan.jurnal_umum', [
            'pageTitle' => "Jurnal Umum",
            'periode' => $periode,
            'rows' => $rows,
        ]);
    }

    private function periodeBounds(string $periode): array
    {
        $start = \Carbon\Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $end   = (clone $start)->endOfMonth();
        return [$start, $end];
    }

    public function filterJurnalUmum(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $data = JurnalDetail::with('kategoriTransaksi', 'transaksi')
            ->whereHas('transaksi', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal', [$start_date, $end_date]);
            })
            ->get();

        $rows = [];
        foreach ($data as $j) {
            $rows[] = [
                'tanggal'    => $j->transaksi->tanggal,
                'deskripsi'  => $j->transaksi->deskripsi,
                'akun'       => $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name,
                'debit'      => $j->posisi === 'debit' ? 'Rp. ' . number_format($j->nominal, 2, '.', ',') : '',
                'kredit'     => $j->posisi === 'kredit' ? 'Rp. ' . number_format($j->nominal, 2, '.', ',') : '',
            ];
        }

        return view('laporan.partials.jurnal_umum_table', compact('rows'));
    }


    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $data = JurnalDetail::with('kategoriTransaksi', 'transaksi')
            ->whereHas('transaksi', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            })->get();

        $rows = [];
        foreach ($data as $j) {
            $rows[] = [
                'tanggal'    => $j->transaksi->tanggal,
                'deskripsi'  => $j->transaksi->deskripsi,
                'akun'       => $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name,
                'debit'      => $j->posisi === 'debit' ? 'Rp. ' . number_format($j->nominal, 2, '.', ',') : '',
                'kredit'     => $j->posisi === 'kredit' ? 'Rp. ' . number_format($j->nominal, 2, '.', ',') : '',
            ];
        }

        $pdf = Pdf::loadView('laporan.pdf', [
            'rows'      => $rows,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ])
            ->setPaper('a4', 'portrait');

        return $pdf->download("jurnal-umum-{$startDate}-sd-{$endDate}.pdf");
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $data = JurnalDetail::with('kategoriTransaksi', 'transaksi')
            ->whereHas('transaksi', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            })->get();

        $filename = "jurnal-umum-{$startDate}-sd-{$endDate}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Deskripsi', 'Akun', 'Debit', 'Kredit'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            // Header CSV
            fputcsv($file, $columns);

            foreach ($data as $j) {
                fputcsv($file, [
                    $j->transaksi->tanggal,
                    $j->transaksi->deskripsi,
                    $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name,
                    $j->posisi === 'debit' ? $j->nominal : '',
                    $j->posisi === 'kredit' ? $j->nominal : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
