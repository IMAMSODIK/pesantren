<?php

namespace App\Http\Controllers;

use App\Models\MutasiBank;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMutasiBankRequest;
use App\Http\Requests\UpdateMutasiBankRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MutasiBankController extends Controller
{
    public function index(){
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
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $import = new \App\Imports\MutasiBankImport($file->getPathname());
        $import->import();

        return redirect()->back()->with('success', 'Data mutasi bank berhasil diimport!');
    }

    // public function reconcile()
    // {
    //     $mutasiList = MutasiBank::where('status_rekon', 'pending')->get();

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
    //         } else {
    //             $mutasi->update([
    //                 'status_rekon' => 'unmatched'
    //             ]);
    //         }
    //     }

    //     return back()->with('success', 'Rekonsiliasi selesai.');
    // }

    // public function laporan()
    // {
    //     $saldoSistem = Transaksi::sum('nominal'); 
    //     $saldoBank   = MutasiBank::latest()->first()->saldo_setelah ?? 0;

    //     $selisih = $saldoSistem - $saldoBank;

    //     return view('rekonsiliasi.laporan', compact('saldoSistem', 'saldoBank', 'selisih'));
    // }
}
