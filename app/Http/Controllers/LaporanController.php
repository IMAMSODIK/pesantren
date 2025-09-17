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
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $data = JurnalDetail::with('kategoriTransaksi', 'transaksi')->whereHas('transaksi', function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
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

        return view('laporan.partials.jurnal_umum_table', compact('rows'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $data = JurnalDetail::with('kategoriTransaksi', 'transaksi')->whereHas('transaksi', function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
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

        $pdf = Pdf::loadView('laporan.pdf', compact('rows', 'bulan', 'tahun'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("jurnal-umum-{$bulan}-{$tahun}.pdf");
    }
}
