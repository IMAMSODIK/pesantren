<?php

namespace App\Http\Controllers;

use App\Models\AsetTetap;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAsetTetapRequest;
use App\Http\Requests\UpdateAsetTetapRequest;
use App\Models\JurnalDetail;
use App\Models\KategoriTransaksi;
use App\Models\Transaksi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsetTetapController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Aset - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['asets'] = AsetTetap::where('status', '1')->get();
            $data['kategories'] = KategoriTransaksi::with('tipeTransaksi')->where('status', 'active')->get();

            return view('aset.index', $data);
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama'              => 'required|string|max:255',
                'nilai'             => 'required|numeric|min:0',
                'umur_ekonomis'     => 'required|integer|min:1',
                'tanggal_perolehan' => 'required|date',
                'kategori'          => 'required|integer|exists:kategori_transaksis,id', // akun aset
                'akun_kredit'       => 'required|string', // kode kas/bank
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => implode(' ', $validator->errors()->all()),
                ]);
            }

            DB::beginTransaction();

            // 1. Simpan aset tetap
            $aset = AsetTetap::create([
                'nama'                  => $request->nama,
                'nilai_perolehan'       => $request->nilai,
                'nilai_sisa'            => $request->nilai,
                'umur_ekonomis'         => $request->umur_ekonomis,
                'tanggal_perolehan'     => $request->tanggal_perolehan,
                'kategori_transaksi_id' => $request->kategori,
                'status'                => 1,
            ]);

            // 2. Buat transaksi header
            $transaksi = Transaksi::create([
                'tanggal'                => $request->tanggal_perolehan,
                'deskripsi'              => "Pembelian aset tetap: {$request->nama}",
                'nominal'                => $request->nilai,
                'kategori_transaksi_id'  => $request->kategori, // default akun aset
                'bukti'                  => null,
                'created_by'             => Auth::id(),
                'updated_by'             => Auth::id(),
                'status'                 => 'active',
                'tipe'                   => 'pengeluaran',
            ]);

            // Ambil akun kredit (kas/bank)
            $akunKredit = KategoriTransaksi::where('kode', $request->akun_kredit)->firstOrFail();

            // 3. Jurnal detail (DEBIT - aset tetap)
            JurnalDetail::create([
                'transaksi_id'           => $transaksi->id,
                'kategori_transaksi_id'  => $request->kategori, // akun aset tetap
                'posisi'                 => 'debit',
                'nominal'                => $request->nilai,
            ]);

            // 4. Jurnal detail (KREDIT - kas/bank)
            JurnalDetail::create([
                'transaksi_id'           => $transaksi->id,
                'kategori_transaksi_id'  => $akunKredit->id,
                'posisi'                 => 'kredit',
                'nominal'                => $request->nilai,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Aset tetap dan jurnal berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ]);
        }
    }


    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'  => 'required',
            ], [
                'required' => 'Kolom :attribute wajib diisi.'
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            $data = AsetTetap::where('id', $request->id)->first();
            if ($data) {
                return response()->json([
                    'status'  => true,
                    'data' => $data
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data tidak ditemukan'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada database: ' . $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'                => 'required|exists:aset_tetaps,id',
                'nama'              => 'required|string|max:255',
                'nilai'             => 'required|numeric|min:0',
                'umur_ekonomis'     => 'required|integer|min:1',
                'tanggal_perolehan' => 'required|date',
                'kategori'          => 'required|exists:kategori_transaksis,id',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'string'   => 'Kolom :attribute harus berupa teks.',
                'numeric'  => 'Kolom :attribute harus berupa angka.',
                'integer'  => 'Kolom :attribute harus berupa bilangan bulat.',
                'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
                'exists'   => 'Data :attribute tidak ditemukan.',
                'min'      => 'Kolom :attribute tidak boleh kurang dari :min.',
            ]);

            if ($validator->fails()) {
                $errors = implode(', ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ], 422);
            }

            $aset = AsetTetap::findOrFail($request->id);

            $aset->nama                = $request->nama;
            $aset->nilai_perolehan     = $request->nilai;
            $aset->umur_ekonomis       = $request->umur_ekonomis;
            $aset->tanggal_perolehan   = $request->tanggal_perolehan;
            $aset->kategori_transaksi_id = $request->kategori;

            if ($aset->wasRecentlyCreated || $aset->nilai_sisa == 0) {
                $aset->nilai_sisa = $request->nilai;
            }

            $aset->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data aset berhasil diperbarui.',
                'data'    => $aset
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada database: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'    => 'required|exists:aset_tetaps,id',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'exists'   => 'Data dengan :attribute tidak ditemukan.',
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            $data = AsetTetap::where('id', $request->id)->first();
            if ($data) {
                $data->status = 0;
                $data->save();

                return response()->json([
                    'status'  => true,
                    'data' => $data
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data tidak ditemukan'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada database: ' . $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ]);
        }
    }
}
