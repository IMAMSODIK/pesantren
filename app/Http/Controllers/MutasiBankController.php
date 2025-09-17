<?php

namespace App\Http\Controllers;

use App\Models\MutasiBank;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMutasiBankRequest;
use App\Http\Requests\UpdateMutasiBankRequest;
use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MutasiBankController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Rekonsiliasi Bank - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            return view('rekonsiliasi.index', $data);
        } catch (QueryException $e) {
            return response()->view('errors.500', [
                'error' => 'Kesalahan database: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            return response()->view('errors.500', [
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $rowIndex = 0;
        $inserted = 0;
        $resultData = [];

        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($rowIndex === 0) {
                    $rowIndex++;
                    continue;
                }

                if (count($data) < 4) {
                    $rowIndex++;
                    continue;
                }

                $mutasi = MutasiBank::create([
                    'tanggal'       => !empty($data[0]) ? Carbon::parse($data[0]) : null,
                    'deskripsi'     => $data[1] ?? null,
                    'nominal'       => is_numeric($data[2]) ? $data[2] : 0,
                    'tipe'          => strtolower(trim($data[3])) === 'debit' ? 'debit' : 'kredit',
                    'saldo_setelah' => $data[4] ?? null,
                ]);

                // cek transaksi untuk rekonsiliasi
                $transaksi = Transaksi::where('nominal', $mutasi->nominal)
                    ->whereDate('tanggal', '>=', Carbon::parse($mutasi->tanggal)->subDays(3))
                    ->whereDate('tanggal', '<=', Carbon::parse($mutasi->tanggal)->addDays(3))
                    ->first();

                if ($transaksi) {
                    $mutasi->update([
                        'status_rekon' => 'matched',
                        'transaksi_id' => $transaksi->id
                    ]);
                } else {
                    $mutasi->update([
                        'status_rekon' => 'unmatched'
                    ]);
                }

                // format data untuk frontend
                $resultData[] = [
                    'tanggal' => $mutasi->tanggal?->format('Y-m-d'),
                    'deskripsi' => $mutasi->deskripsi,
                    'debit' => $mutasi->tipe == 'debit' ? $mutasi->nominal : '-',
                    'kredit' => $mutasi->tipe == 'kredit' ? $mutasi->nominal : '-',
                    'status' => $mutasi->status_rekon == 'matched' ? 'Matched' : 'Unmatched',
                ];

                $inserted++;
                $rowIndex++;
            }
            fclose($handle);
        }

        return response()->json([
            'status'  => true,
            'message' => "Berhasil import {$inserted} data dari CSV!",
            'data'    => $resultData
        ]);
    }


    // public function reconcile()
    // {
    //     $mutasiList = MutasiBank::where('status_rekon', 'pending')->get();
    //     $matched = 0;
    //     $unmatched = 0;

    //     foreach ($mutasiList as $mutasi) {
    //         $transaksi = Transaksi::where('nominal', $mutasi->nominal)
    //             ->whereDate('tanggal', '>=', Carbon::parse($mutasi->tanggal)->subDays(3))
    //             ->whereDate('tanggal', '<=', Carbon::parse($mutasi->tanggal)->addDays(3))
    //             ->first();

    //         if ($transaksi) {
    //             $mutasi->update([
    //                 'status_rekon' => 'matched',
    //                 'transaksi_id' => $transaksi->id
    //             ]);
    //             $matched++;
    //         } else {
    //             $mutasi->update([
    //                 'status_rekon' => 'unmatched'
    //             ]);
    //             $unmatched++;
    //         }
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => "Rekonsiliasi selesai. Matched: {$matched}, Unmatched: {$unmatched}"
    //     ]);
    // }

    public function laporan()
    {
        $saldoSistem = Transaksi::sum('nominal');
        $saldoBank   = MutasiBank::latest()->first()->saldo_setelah ?? 0;
        $selisih     = $saldoSistem - $saldoBank;

        $matched   = MutasiBank::where('status_rekon', 'matched')->count();
        $unmatched = MutasiBank::where('status_rekon', 'unmatched')->count();

        return view('rekonsiliasi.laporan', compact(
            'saldoSistem',
            'saldoBank',
            'selisih',
            'matched',
            'unmatched'
        ));
    }
}
