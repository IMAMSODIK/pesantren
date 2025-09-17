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
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $data = $this->getDataNeracaSaldo($bulan, $tahun);

        return view('neraca_saldo.partials.table', compact('data', 'bulan', 'tahun'))->render();
    }

    public function exportNeracaSaldoPDF(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $data = $this->getDataNeracaSaldo($bulan, $tahun);

        $pdf = Pdf::loadView('neraca_saldo.pdf', [
            'data'  => $data,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->setPaper('a4', 'portrait');

        return $pdf->download("neraca_saldo_{$bulan}_{$tahun}.pdf");
    }

    private function getDataNeracaSaldo($bulan, $tahun)
    {
        $akunList = KategoriTransaksi::all();
        $data = [];

        foreach ($akunList as $akun) {
            $debit = JurnalDetail::where('kategori_transaksi_id', $akun->id)
                ->whereHas('transaksi', function ($q) use ($tahun, $bulan) {
                    $q->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan);
                })
                ->where('posisi', 'debit')
                ->sum('nominal');

            $kredit = JurnalDetail::where('kategori_transaksi_id', $akun->id)
                ->whereHas('transaksi', function ($q) use ($tahun, $bulan) {
                    $q->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan);
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
}
