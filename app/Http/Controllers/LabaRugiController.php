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
        $pageTitle = "Laporan Aktivitas (Laba Rugi)";

        // Pendapatan Tidak Terikat (tipe 6)
        $pendapatanTidakTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('tipe_transaksis.id', 6)
            ->where('jurnal_details.posisi', 'kredit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalPendapatanTidakTerikat = $pendapatanTidakTerikat->sum('nominal');

        // Pendapatan Terikat (tipe 7)
        $pendapatanTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('tipe_transaksis.id', 7)
            ->where('jurnal_details.posisi', 'kredit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalPendapatanTerikat = $pendapatanTerikat->sum('nominal');

        // Beban Tidak Terikat (tipe 8)
        $bebanTidakTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('tipe_transaksis.id', 8)
            ->where('jurnal_details.posisi', 'debit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalBebanTidakTerikat = $bebanTidakTerikat->sum('nominal');

        // Beban Terikat (tipe 9)
        $bebanTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->whereYear('transaksis.tanggal', $tahun)
            ->whereMonth('transaksis.tanggal', '<=', $bulan)
            ->where('tipe_transaksis.id', 9)
            ->where('jurnal_details.posisi', 'debit')
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalBebanTerikat = $bebanTerikat->sum('nominal');

        // Surplus/Defisit
        $surplusTidakTerikat = $totalPendapatanTidakTerikat - $totalBebanTidakTerikat;
        $surplusTerikat = $totalPendapatanTerikat - $totalBebanTerikat;
        $surplusTotal = $surplusTidakTerikat + $surplusTerikat;

        return view('laba_rugi.index', compact(
            'bulan',
            'tahun',
            'pageTitle',
            'pendapatanTidakTerikat',
            'pendapatanTerikat',
            'bebanTidakTerikat',
            'bebanTerikat',
            'totalPendapatanTidakTerikat',
            'totalPendapatanTerikat',
            'totalBebanTidakTerikat',
            'totalBebanTerikat',
            'surplusTidakTerikat',
            'surplusTerikat',
            'surplusTotal'
        ));
    }

    public function exportPdfLabaRugi(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Helper untuk filter tanggal
        $applyDateFilter = function ($query) use ($tanggalAwal, $tanggalAkhir) {
            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereBetween('transaksis.tanggal', [$tanggalAwal, $tanggalAkhir]);
            }
        };

        // Pendapatan Tidak Terikat (tipe 6)
        $pendapatanTidakTerikat = JurnalDetail::selectRaw('
            kategori_transaksis.kode,
            kategori_transaksis.name as nama,
            SUM(jurnal_details.nominal) as nominal
        ')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 6)
            ->where('jurnal_details.posisi', 'kredit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalPendapatanTidakTerikat = $pendapatanTidakTerikat->sum('nominal');

        // Pendapatan Terikat (tipe 7)
        $pendapatanTerikat = JurnalDetail::selectRaw('
            kategori_transaksis.kode,
            kategori_transaksis.name as nama,
            SUM(jurnal_details.nominal) as nominal
        ')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 7)
            ->where('jurnal_details.posisi', 'kredit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalPendapatanTerikat = $pendapatanTerikat->sum('nominal');

        // Beban Tidak Terikat (tipe 8)
        $bebanTidakTerikat = JurnalDetail::selectRaw('
            kategori_transaksis.kode,
            kategori_transaksis.name as nama,
            SUM(jurnal_details.nominal) as nominal
        ')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 8)
            ->where('jurnal_details.posisi', 'debit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalBebanTidakTerikat = $bebanTidakTerikat->sum('nominal');

        // Beban Terikat (tipe 9)
        $bebanTerikat = JurnalDetail::selectRaw('
            kategori_transaksis.kode,
            kategori_transaksis.name as nama,
            SUM(jurnal_details.nominal) as nominal
        ')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 9)
            ->where('jurnal_details.posisi', 'debit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();
        $totalBebanTerikat = $bebanTerikat->sum('nominal');

        // Surplus/Defisit
        $surplusTidakTerikat = $totalPendapatanTidakTerikat - $totalBebanTidakTerikat;
        $surplusTerikat = $totalPendapatanTerikat - $totalBebanTerikat;
        $surplusTotal = $surplusTidakTerikat + $surplusTerikat;

        $pdf = Pdf::loadView('laba_rugi.pdf', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'pendapatanTidakTerikat',
            'pendapatanTerikat',
            'bebanTidakTerikat',
            'bebanTerikat',
            'totalPendapatanTidakTerikat',
            'totalPendapatanTerikat',
            'totalBebanTidakTerikat',
            'totalBebanTerikat',
            'surplusTidakTerikat',
            'surplusTerikat',
            'surplusTotal'
        ))->setPaper('a4', 'portrait');

        $namaFile = "laporan-laba-rugi";
        if ($tanggalAwal && $tanggalAkhir) {
            $namaFile .= "-{$tanggalAwal}-sd-{$tanggalAkhir}";
        } else {
            $namaFile .= "-semua-periode";
        }

        return $pdf->download("{$namaFile}.pdf");
    }

    public function exportCsvLabaRugi(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Helper untuk filter tanggal
        $applyDateFilter = function ($query) use ($tanggalAwal, $tanggalAkhir) {
            if ($tanggalAwal && $tanggalAkhir) {
                $query->whereBetween('transaksis.tanggal', [$tanggalAwal, $tanggalAkhir]);
            }
        };

        // Pendapatan Tidak Terikat (tipe 6)
        $pendapatanTidakTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 6)
            ->where('jurnal_details.posisi', 'kredit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        // Pendapatan Terikat (tipe 7)
        $pendapatanTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 7)
            ->where('jurnal_details.posisi', 'kredit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        // Beban Tidak Terikat (tipe 8)
        $bebanTidakTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 8)
            ->where('jurnal_details.posisi', 'debit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        // Beban Terikat (tipe 9)
        $bebanTerikat = JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
            ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
            ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
            ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
            ->where('tipe_transaksis.id', 9)
            ->where('jurnal_details.posisi', 'debit')
            ->when(true, $applyDateFilter)
            ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
            ->get();

        // Surplus
        $surplusTidakTerikat = $pendapatanTidakTerikat->sum('nominal') - $bebanTidakTerikat->sum('nominal');
        $surplusTerikat = $pendapatanTerikat->sum('nominal') - $bebanTerikat->sum('nominal');
        $surplusTotal = $surplusTidakTerikat + $surplusTerikat;

        // Nama file
        $filename = "laporan-laba-rugi";
        if ($tanggalAwal && $tanggalAkhir) {
            $filename .= "-{$tanggalAwal}-sd-{$tanggalAkhir}.csv";
        } else {
            $filename .= "-semua-periode.csv";
        }

        return response()->streamDownload(function () use ($pendapatanTidakTerikat, $pendapatanTerikat, $bebanTidakTerikat, $bebanTerikat, $surplusTidakTerikat, $surplusTerikat, $surplusTotal, $tanggalAwal, $tanggalAkhir) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, ["LAPORAN LABA RUGI"]);
            fputcsv($handle, ["Periode", ($tanggalAwal && $tanggalAkhir) ? "$tanggalAwal s/d $tanggalAkhir" : "Semua Periode"]);
            fputcsv($handle, []);

            // Pendapatan Tidak Terikat
            fputcsv($handle, ["Pendapatan Tidak Terikat"]);
            foreach ($pendapatanTidakTerikat as $p) {
                fputcsv($handle, [$p->kode, $p->nama, $p->nominal]);
            }
            fputcsv($handle, ["Total Pendapatan Tidak Terikat", "", $pendapatanTidakTerikat->sum('nominal')]);
            fputcsv($handle, []);

            // Pendapatan Terikat
            fputcsv($handle, ["Pendapatan Terikat"]);
            foreach ($pendapatanTerikat as $p) {
                fputcsv($handle, [$p->kode, $p->nama, $p->nominal]);
            }
            fputcsv($handle, ["Total Pendapatan Terikat", "", $pendapatanTerikat->sum('nominal')]);
            fputcsv($handle, []);

            // Beban Tidak Terikat
            fputcsv($handle, ["Beban Tidak Terikat"]);
            foreach ($bebanTidakTerikat as $b) {
                fputcsv($handle, [$b->kode, $b->nama, $b->nominal]);
            }
            fputcsv($handle, ["Total Beban Tidak Terikat", "", $bebanTidakTerikat->sum('nominal')]);
            fputcsv($handle, []);

            // Beban Terikat
            fputcsv($handle, ["Beban Terikat"]);
            foreach ($bebanTerikat as $b) {
                fputcsv($handle, [$b->kode, $b->nama, $b->nominal]);
            }
            fputcsv($handle, ["Total Beban Terikat", "", $bebanTerikat->sum('nominal')]);
            fputcsv($handle, []);

            // Surplus
            fputcsv($handle, ["Surplus Tidak Terikat", "", $surplusTidakTerikat]);
            fputcsv($handle, ["Surplus Terikat", "", $surplusTerikat]);
            fputcsv($handle, ["Surplus Total", "", $surplusTotal]);

            fclose($handle);
        }, $filename, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}"
        ]);
    }

    public function filterLabaRugi(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        if (!$tanggalAwal || !$tanggalAkhir) {
            return response()->json(['error' => 'Tanggal awal dan akhir wajib dipilih'], 422);
        }

        // Query dasar
        $baseQuery = function ($tipeId, $posisi) use ($tanggalAwal, $tanggalAkhir) {
            return JurnalDetail::selectRaw('kategori_transaksis.kode, kategori_transaksis.name as nama, SUM(jurnal_details.nominal) as nominal')
                ->join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                ->where('tipe_transaksis.id', $tipeId)
                ->where('jurnal_details.posisi', $posisi)
                ->whereBetween('transaksis.tanggal', [$tanggalAwal, $tanggalAkhir])
                ->groupBy('kategori_transaksis.kode', 'kategori_transaksis.name')
                ->get();
        };

        // Ambil data
        $pendapatanTidakTerikat = $baseQuery(6, 'kredit');
        $pendapatanTerikat = $baseQuery(7, 'kredit');
        $bebanTidakTerikat = $baseQuery(8, 'debit');
        $bebanTerikat = $baseQuery(9, 'debit');

        // Hitung total
        $totalPendapatanTidakTerikat = $pendapatanTidakTerikat->sum('nominal');
        $totalPendapatanTerikat = $pendapatanTerikat->sum('nominal');
        $totalBebanTidakTerikat = $bebanTidakTerikat->sum('nominal');
        $totalBebanTerikat = $bebanTerikat->sum('nominal');

        // Surplus / Defisit
        $surplusTidakTerikat = $totalPendapatanTidakTerikat - $totalBebanTidakTerikat;
        $surplusTerikat = $totalPendapatanTerikat - $totalBebanTerikat;
        $surplusTotal = $surplusTidakTerikat + $surplusTerikat;

        return view('laba_rugi.partials.table', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'pendapatanTidakTerikat',
            'pendapatanTerikat',
            'bebanTidakTerikat',
            'bebanTerikat',
            'totalPendapatanTidakTerikat',
            'totalPendapatanTerikat',
            'totalBebanTidakTerikat',
            'totalBebanTerikat',
            'surplusTidakTerikat',
            'surplusTerikat',
            'surplusTotal'
        ));
    }
}
