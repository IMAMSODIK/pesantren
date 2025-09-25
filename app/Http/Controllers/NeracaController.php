<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AsetTetap;
use App\Models\JurnalDetail;
use App\Models\KategoriTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    public function neraca(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date   = $request->end_date ?? date('Y-m-d');
        $pageTitle  = "Neraca (Aset Neto)";

        try {
            // --- ASET (Kas + Piutang) ---
            $akunAset = KategoriTransaksi::whereIn('kode', ['101', '103', '104', '106', '107'])->get();
            $aset = [];
            foreach ($akunAset as $kategori) {
                $debit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'debit')
                    ->sum('jurnal_details.nominal');

                $kredit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->sum('jurnal_details.nominal');

                $aset[] = [
                    'kode' => $kategori->kode,
                    'nama' => $kategori->name,
                    'saldo' => $debit - $kredit
                ];
            }

            // --- LIABILITAS (Utang) ---
            $akunLiabilitas = KategoriTransaksi::whereIn('kode', ['301', '302', '303', '304', '305'])->get();
            $liabilitas = [];
            foreach ($akunLiabilitas as $kategori) {
                $debit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'debit')
                    ->sum('jurnal_details.nominal');

                $kredit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->sum('jurnal_details.nominal');

                $liabilitas[] = [
                    'kode' => $kategori->kode,
                    'nama' => $kategori->name,
                    'saldo' => $kredit - $debit
                ];
            }

            // --- MODAL (kategori_transaksi_id = 403, kumulatif) ---
            $modal = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                ->where('jurnal_details.kategori_transaksi_id', 403)
                ->where('transaksis.status', 'active')
                ->sum(DB::raw("CASE WHEN jurnal_details.posisi='kredit' THEN jurnal_details.nominal ELSE -jurnal_details.nominal END"));

            $liabilitas[] = [
                'kode' => '403',
                'nama' => 'Modal',
                'saldo' => $modal
            ];

            // --- ASET NETO (Laba/Rugi) ---
            $asetNeto = [];
            $tipeAsetNeto = [
                ['id' => 6, 'nama' => 'Tidak Terikat'],
                ['id' => 7, 'nama' => 'Terikat']
            ];

            foreach ($tipeAsetNeto as $tipe) {
                $pendapatan = JurnalDetail::join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                    ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                    ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('tipe_transaksis.id', $tipe['id'])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->sum('jurnal_details.nominal');

                $bebanId = $tipe['id'] + 2; // Asumsi: Beban Tidak Terikat = 8, Terikat = 9
                $beban = JurnalDetail::join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                    ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                    ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('tipe_transaksis.id', $bebanId)
                    ->where('jurnal_details.posisi', 'debit')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->sum('jurnal_details.nominal');

                $asetNeto[] = [
                    'nama' => $tipe['nama'],
                    'saldo' => $pendapatan - $beban
                ];
            }

            $totalAsetNeto = array_sum(array_column($asetNeto, 'saldo'));

            return view('neraca.index', compact(
                'pageTitle',
                'start_date',
                'end_date',
                'aset',
                'liabilitas',
                'asetNeto',
                'totalAsetNeto'
            ));
        } catch (\Exception $e) {
            return response()->view('errors.500', [
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function exportPdf(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date   = $request->end_date ?? date('Y-m-d');

        try {
            // --- ASET ---
            $akunAset = KategoriTransaksi::whereIn('kode', ['101', '103', '104', '106', '107'])->get();
            $aset = [];
            foreach ($akunAset as $kategori) {
                $debit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'debit')
                    ->sum('jurnal_details.nominal');

                $kredit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->sum('jurnal_details.nominal');

                $aset[] = [
                    'kode' => $kategori->kode,
                    'nama' => $kategori->name,
                    'saldo' => $debit - $kredit
                ];
            }

            // --- LIABILITAS ---
            $akunLiabilitas = KategoriTransaksi::whereIn('kode', ['301', '302', '303', '304', '305'])->get();
            $liabilitas = [];
            foreach ($akunLiabilitas as $kategori) {
                $debit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'debit')
                    ->sum('jurnal_details.nominal');

                $kredit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->sum('jurnal_details.nominal');

                $liabilitas[] = [
                    'kode' => $kategori->kode,
                    'nama' => $kategori->name,
                    'saldo' => $kredit - $debit
                ];
            }

            // --- MODAL ---
            $modal = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                ->where('jurnal_details.kategori_transaksi_id', 403)
                ->where('transaksis.status', 'active')
                ->sum(DB::raw("CASE WHEN jurnal_details.posisi='kredit' THEN jurnal_details.nominal ELSE -jurnal_details.nominal END"));

            $liabilitas[] = [
                'kode' => '403',
                'nama' => 'Modal',
                'saldo' => $modal
            ];

            // --- ASET NETO ---
            $asetNeto = [];
            $tipeAsetNeto = [
                ['id' => 6, 'nama' => 'Tidak Terikat'],
                ['id' => 7, 'nama' => 'Terikat']
            ];

            foreach ($tipeAsetNeto as $tipe) {
                $pendapatan = JurnalDetail::join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                    ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                    ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('tipe_transaksis.id', $tipe['id'])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->sum('jurnal_details.nominal');

                $bebanId = $tipe['id'] + 2;
                $beban = JurnalDetail::join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                    ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                    ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('tipe_transaksis.id', $bebanId)
                    ->where('jurnal_details.posisi', 'debit')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->sum('jurnal_details.nominal');

                $asetNeto[] = [
                    'nama' => $tipe['nama'],
                    'saldo' => $pendapatan - $beban
                ];
            }

            $totalAsetNeto = array_sum(array_column($asetNeto, 'saldo'));

            $pdf = Pdf::loadView('neraca.pdf', compact(
                'start_date',
                'end_date',
                'aset',
                'liabilitas',
                'asetNeto',
                'totalAsetNeto'
            ));

            return $pdf->download('neraca_' . $start_date . '_sampai_' . $end_date . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date   = $request->end_date ?? date('Y-m-d');

        try {
            // --- ASET ---
            $akunAset = KategoriTransaksi::whereIn('kode', ['101', '103', '104', '106', '107'])->get();
            $aset = [];
            foreach ($akunAset as $kategori) {
                $debit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'debit')
                    ->sum('jurnal_details.nominal');

                $kredit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->sum('jurnal_details.nominal');

                $aset[] = [
                    'Kode' => $kategori->kode,
                    'Nama' => $kategori->name,
                    'Saldo' => $debit - $kredit
                ];
            }

            // --- LIABILITAS & MODAL ---
            $akunLiabilitas = KategoriTransaksi::whereIn('kode', ['301', '302', '303', '304', '305'])->get();
            $liabilitas = [];
            foreach ($akunLiabilitas as $kategori) {
                $debit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'debit')
                    ->sum('jurnal_details.nominal');

                $kredit = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('jurnal_details.kategori_transaksi_id', $kategori->id)
                    ->where('transaksis.status', 'active')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->sum('jurnal_details.nominal');

                $liabilitas[] = [
                    'Kode' => $kategori->kode,
                    'Nama' => $kategori->name,
                    'Saldo' => $kredit - $debit
                ];
            }

            // Modal
            $modal = JurnalDetail::join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                ->where('jurnal_details.kategori_transaksi_id', 403)
                ->where('transaksis.status', 'active')
                ->sum(DB::raw("CASE WHEN jurnal_details.posisi='kredit' THEN jurnal_details.nominal ELSE -jurnal_details.nominal END"));

            $liabilitas[] = [
                'Kode' => '403',
                'Nama' => 'Modal',
                'Saldo' => $modal
            ];

            // --- ASET NETO ---
            $asetNeto = [];
            $tipeAsetNeto = [
                ['id' => 6, 'nama' => 'Tidak Terikat'],
                ['id' => 7, 'nama' => 'Terikat']
            ];

            foreach ($tipeAsetNeto as $tipe) {
                $pendapatan = JurnalDetail::join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                    ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                    ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('tipe_transaksis.id', $tipe['id'])
                    ->where('jurnal_details.posisi', 'kredit')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->sum('jurnal_details.nominal');

                $bebanId = $tipe['id'] + 2;
                $beban = JurnalDetail::join('kategori_transaksis', 'jurnal_details.kategori_transaksi_id', '=', 'kategori_transaksis.id')
                    ->join('tipe_transaksis', 'kategori_transaksis.tipe_transaksi_id', '=', 'tipe_transaksis.id')
                    ->join('transaksis', 'jurnal_details.transaksi_id', '=', 'transaksis.id')
                    ->where('tipe_transaksis.id', $bebanId)
                    ->where('jurnal_details.posisi', 'debit')
                    ->whereBetween('transaksis.tanggal', [$start_date, $end_date])
                    ->sum('jurnal_details.nominal');

                $asetNeto[] = [
                    'Nama' => $tipe['nama'],
                    'Saldo' => $pendapatan - $beban
                ];
            }

            $totalAsetNeto = array_sum(array_column($asetNeto, 'Saldo'));

            // --- Buat CSV ---
            $filename = 'neraca_' . $start_date . '_sampai_' . $end_date . '.csv';
            $handle = fopen('php://memory', 'w');

            // Header CSV
            fputcsv($handle, ['NERACA (ASET NETO)']);
            fputcsv($handle, ['Periode', date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date))]);
            fputcsv($handle, []); // kosong

            // Aset
            fputcsv($handle, ['ASET']);
            fputcsv($handle, ['Kode', 'Nama', 'Saldo']);
            foreach ($aset as $row) {
                fputcsv($handle, $row);
            }
            fputcsv($handle, []); // kosong

            // Liabilitas & Modal
            fputcsv($handle, ['LIABILITAS & MODAL']);
            fputcsv($handle, ['Kode', 'Nama', 'Saldo']);
            foreach ($liabilitas as $row) {
                fputcsv($handle, $row);
            }
            fputcsv($handle, []); // kosong

            // Aset Neto
            fputcsv($handle, ['ASET NETO']);
            fputcsv($handle, ['Nama', 'Saldo']);
            foreach ($asetNeto as $row) {
                fputcsv($handle, $row);
            }
            fputcsv($handle, ['Total Aset Neto', $totalAsetNeto]);

            rewind($handle);

            return response()->streamDownload(function () use ($handle) {
                fpassthru($handle);
            }, $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
