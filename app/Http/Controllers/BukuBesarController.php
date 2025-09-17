<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JurnalDetail;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BukuBesarController extends Controller
{
    public function bukuBesar(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));

        $jurnals = JurnalDetail::with(['transaksi', 'kategoriTransaksi'])
            ->whereHas('transaksi', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            })
            ->orderBy('kategori_transaksi_id')
            ->orderBy('transaksi_id')
            ->get();

        // kelompokkan per akun
        $bukuBesar = [];
        foreach ($jurnals as $j) {
            $akunKey = $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name;

            if (!isset($bukuBesar[$akunKey])) {
                $bukuBesar[$akunKey] = [
                    'akun' => $akunKey,
                    'rows' => [],
                    'totalDebit' => 0,
                    'totalKredit' => 0,
                ];
            }

            $debit  = $j->posisi === 'debit' ? $j->nominal : 0;
            $kredit = $j->posisi === 'kredit' ? $j->nominal : 0;

            $bukuBesar[$akunKey]['rows'][] = [
                'tanggal'    => \Carbon\Carbon::parse($j->transaksi->tanggal)->format('d-m-Y'),
                'deskripsi'  => $j->transaksi->deskripsi,
                'debit'      => $debit > 0 ? 'Rp. ' . number_format($debit, 2, ',', '.') : '',
                'kredit'     => $kredit > 0 ? 'Rp. ' . number_format($kredit, 2, ',', '.') : '',
            ];

            $bukuBesar[$akunKey]['totalDebit']  += $debit;
            $bukuBesar[$akunKey]['totalKredit'] += $kredit;
        }

        return view('buku_besar.index', [
            'pageTitle' => 'Buku Besar',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bukuBesar' => $bukuBesar,
        ]);
    }

    public function filterBukuBesar(Request $request)
    {
        $bulan = intval($request->get('bulan')); // pastikan integer
        $tahun = intval($request->get('tahun'));

        $jurnals = JurnalDetail::with(['transaksi', 'kategoriTransaksi'])
            ->whereHas('transaksi', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            })
            ->orderBy('kategori_transaksi_id')
            ->orderBy('transaksi_id')
            ->get();

        $bukuBesar = [];
        foreach ($jurnals as $j) {
            $akunKey = $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name;

            if (!isset($bukuBesar[$akunKey])) {
                $bukuBesar[$akunKey] = [
                    'akun' => $akunKey,
                    'rows' => [],
                    'totalDebit' => 0,
                    'totalKredit' => 0,
                ];
            }

            $debit  = $j->posisi === 'debit' ? $j->nominal : 0;
            $kredit = $j->posisi === 'kredit' ? $j->nominal : 0;

            $bukuBesar[$akunKey]['rows'][] = [
                // jika kolom `tanggal` sudah didefinisikan cast `date` di model Transaksi
                'tanggal'    => $j->transaksi->tanggal instanceof \Carbon\Carbon
                    ? $j->transaksi->tanggal->format('d-m-Y')
                    : \Carbon\Carbon::parse($j->transaksi->tanggal)->format('d-m-Y'),
                'deskripsi'  => $j->transaksi->deskripsi,
                'debit'      => $debit > 0 ? 'Rp. ' . number_format($debit, 2, ',', '.') : '',
                'kredit'     => $kredit > 0 ? 'Rp. ' . number_format($kredit, 2, ',', '.') : '',
            ];

            $bukuBesar[$akunKey]['totalDebit']  += $debit;
            $bukuBesar[$akunKey]['totalKredit'] += $kredit;
        }

        $html = view('buku_besar.partials.table', compact('bukuBesar'))->render();

        return response()->json(['html' => $html]);
    }

    public function bukuBesarPdf(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $jurnals = JurnalDetail::with(['transaksi', 'kategoriTransaksi'])
            ->whereHas('transaksi', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            })
            ->orderBy('kategori_transaksi_id')
            ->orderBy('transaksi_id')
            ->get();

        $bukuBesar = [];
        foreach ($jurnals as $j) {
            $akunKey = $j->kategoriTransaksi->kode . ' - ' . $j->kategoriTransaksi->name;

            if (!isset($bukuBesar[$akunKey])) {
                $bukuBesar[$akunKey] = [
                    'akun' => $akunKey,
                    'rows' => [],
                    'totalDebit' => 0,
                    'totalKredit' => 0,
                ];
            }

            $debit  = $j->posisi === 'debit' ? $j->nominal : 0;
            $kredit = $j->posisi === 'kredit' ? $j->nominal : 0;

            $bukuBesar[$akunKey]['rows'][] = [
                'tanggal'    => \Carbon\Carbon::parse($j->transaksi->tanggal)->format('d-m-Y'),
                'deskripsi'  => $j->transaksi->deskripsi,
                'debit'      => $debit > 0 ? 'Rp. ' . number_format($debit, 2, ',', '.') : '',
                'kredit'     => $kredit > 0 ? 'Rp. ' . number_format($kredit, 2, ',', '.') : '',
            ];

            $bukuBesar[$akunKey]['totalDebit']  += $debit;
            $bukuBesar[$akunKey]['totalKredit'] += $kredit;
        }

        $pdf = Pdf::loadView('buku_besar.pdf', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bukuBesar' => $bukuBesar
        ])->setPaper('A4', 'portrait');

        return $pdf->download("Buku_Besar_{$bulan}_{$tahun}.pdf");
    }
}
