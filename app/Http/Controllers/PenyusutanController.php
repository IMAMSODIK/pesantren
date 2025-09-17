<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AsetTetap;
use App\Models\JurnalDetail;
use App\Models\KategoriTransaksi;
use App\Models\PenyusutanLog;
use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenyusutanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', now()->format('Y-m')); // YYYY-MM
        [$start, $end] = $this->periodeBounds($periode);

        $akunBeban = KategoriTransaksi::where('kode', '615')->first();
        $akunAkumulasi = KategoriTransaksi::where('kode', '299')->first();


        $aset = AsetTetap::query()->get();

        $rows = [];
        $total = 0;

        foreach ($aset as $a) {
            // Lewati aset yang belum diperoleh pada periode ini
            if (Carbon::parse($a->tanggal_perolehan)->startOfMonth()->gt($end)) {
                $rows[] = $this->row($a, $periode, 0, 'Belum efektif (aset baru)');
                continue;
            }

            // Hitung beban bulanan (garis lurus)
            $beban = $this->bebanBulanan($a);

            // Jika umur ekonomis sudah habis → 0
            if ($this->sisaBulan($a, $end) <= 0) {
                $rows[] = $this->row($a, $periode, 0, 'Umur habis');
                continue;
            }

            // Cek sudah diposting?
            $sudah = PenyusutanLog::where('aset_tetap_id', $a->id)->where('periode', $periode)->exists();
            $status = $sudah ? 'Sudah diposting' : 'Belum diposting';

            $rows[] = $this->row($a, $periode, $beban, $status, !$sudah);
            if (!$sudah) $total += $beban;
        }

        return view('penyusutan.index', [
            'pageTitle' => 'Penyusutan',
            'periode' => $periode,
            'rows'    => $rows,
            'total'   => $total,
            'akunBeban' => $akunBeban,
            'akunAkumulasi' => $akunAkumulasi,
        ]);
    }

    // POST /penyusutan/proses → commit jurnal periode (bisa subset aset)
    public function proses(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'aset_ids' => 'nullable|array',
            'aset_ids.*' => 'integer',
        ]);

        $periode = $request->input('periode');
        [$start, $end] = $this->periodeBounds($periode);

        $akunBeban = KategoriTransaksi::where('kode', '615')->first();
        $akunAkumulasi = KategoriTransaksi::where('kode', '299')->first();
        if (!$akunBeban || !$akunAkumulasi) {
            return response()->json(['status'=>false,'message'=>'Akun beban/akumulasi penyusutan belum diset.']);
        }

        $asetQuery = AsetTetap::query();
        if ($request->filled('aset_ids')) {
            $asetQuery->whereIn('id', $request->aset_ids);
        }
        $asetList = $asetQuery->get();

        $posted = 0;
        $skipped = 0;
        $items = [];

        DB::beginTransaction();
        try {
            foreach ($asetList as $a) {
                // Skip jika periode belum efektif atau umur habis
                if (Carbon::parse($a->tanggal_perolehan)->startOfMonth()->gt($end) || $this->sisaBulan($a, $end) <= 0) {
                    $skipped++; continue;
                }

                // Idempoten
                if (PenyusutanLog::where('aset_tetap_id', $a->id)->where('periode', $periode)->exists()) {
                    $skipped++; continue;
                }

                $beban = $this->bebanBulanan($a);

                // Buat transaksi (tipe penyesuaian)
                $trans = Transaksi::create([
                    'tanggal'   => $end, // akhir bulan
                    'deskripsi' => "Penyusutan {$a->nama} bulan " . Carbon::parse($periode.'-01')->translatedFormat('F Y'),
                    'nominal'   => $beban,
                    'kategori_transaksi_id' => $a->kategori_transaksi_id, // referensi aset
                    'tipe'      => 'penyesuaian',
                    'created_by'=> Auth::id() ?? 1,
                    'updated_by'=> Auth::id() ?? 1,
                ]);

                // Jurnal: Dr Beban, Cr Akumulasi
                JurnalDetail::create([
                    'transaksi_id' => $trans->id,
                    'kategori_transaksi_id' => $akunBeban->id,
                    'posisi' => 'debit',
                    'nominal' => $beban,
                ]);
                JurnalDetail::create([
                    'transaksi_id' => $trans->id,
                    'kategori_transaksi_id' => $akunAkumulasi->id,
                    'posisi' => 'kredit',
                    'nominal' => $beban,
                ]);

                // Log
                PenyusutanLog::create([
                    'aset_tetap_id' => $a->id,
                    'periode' => $periode,
                    'transaksi_id' => $trans->id,
                    'nominal' => $beban,
                    'posted_at' => now(),
                ]);

                $posted++;
                $items[] = [
                    'aset_id' => $a->id,
                    'nama'    => $a->nama,
                    'nominal' => number_format($beban, 2, '.', ''),
                ];
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "Penyusutan periode {$periode} diposting. Posted: {$posted}, Skip: {$skipped}.",
                'items'   => $items
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal posting penyusutan: '.$e->getMessage(),
            ], 500);
        }
    }

    // ===== Helpers =====

    private function periodeBounds(string $periode): array
    {
        $start = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $end   = (clone $start)->endOfMonth();
        return [$start, $end];
    }

    private function bebanBulanan($aset): float
    {
        $nilai = (float)$aset->nilai_perolehan - (float)$aset->nilai_sisa;
        $umur  = max(1, (int)$aset->umur_ekonomis); // guard
        // pembulatan 2 desimal; bulan terakhir bisa kamu sesuaikan jika ingin *true-up*
        return round($nilai / $umur, 2);
    }

    private function sisaBulan($aset, Carbon $perEnd): int
    {
        // berapa bulan dari perolehan s.d. akhir periode (inklusif)
        $start = Carbon::parse($aset->tanggal_perolehan)->startOfMonth();
        $monthsUsed = max(0, ($start->diffInMonths($perEnd)) + 1);
        $sisa = (int)$aset->umur_ekonomis - $monthsUsed;
        return $sisa;
    }

    private function row($a, $periode, $beban, $status, $selectable = false): array
    {
        return [
            'id' => $a->id,
            'nama' => $a->nama,
            'nilai_perolehan' => number_format($a->nilai_perolehan, 2, '.', ','),
            'umur' => $a->umur_ekonomis,
            'periode' => $periode,
            'beban' => number_format($beban, 2, '.', ','),
            'status' => $status,
            'selectable' => $selectable,
        ];
    }
}
