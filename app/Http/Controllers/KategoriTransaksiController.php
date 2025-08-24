<?php

namespace App\Http\Controllers;

use App\Models\KategoriTransaksi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKategoriTransaksiRequest;
use App\Http\Requests\UpdateKategoriTransaksiRequest;
use App\Models\TipeTransaksi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriTransaksiController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Tipe Transaksi - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['types'] = TipeTransaksi::where('status', 'active')->get();
            $data['kategories'] = KategoriTransaksi::with('tipeTransaksi')->where('status', 'active')->get();

            return view('kategori.index', $data);
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
                'tipe'    => 'required|exists:tipe_transaksis,id',
                'kategori'  => 'required|string|max:255|unique:kategori_transaksis,name',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'string'   => 'Kolom :attribute harus berupa teks.',
                'max'      => [
                    'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
                ],
                'unique'   => 'Kolom :attribute sudah digunakan.',
                'exists'   => 'Data dengan :attribute tidak ditemukan.',
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            KategoriTransaksi::create([
                'name'     => $request->kategori,
                'tipe_transaksi_id'     => $request->tipe,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Kategori Transaksi berhasil ditambahkan.',
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

    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'  => 'required|exists:kategori_transaksis,id',
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

            $kategoriTransaksi = KategoriTransaksi::where('id', $request->id)->first();
            if ($kategoriTransaksi) {
                return response()->json([
                    'status'  => true,
                    'data' => $kategoriTransaksi
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
                'tipe'    => 'required|exists:tipe_transaksis,id',
                'kategori'  => 'required|string|max:255|unique:kategori_transaksis,name,' . $request->id . ',id',
                'id'    => 'required|exists:kategori_transaksis,id',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'string'   => 'Kolom :attribute harus berupa teks.',
                'max'      => [
                    'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
                ],
                'unique'   => 'Kolom :attribute sudah digunakan.',
                'exists'   => 'Data dengan :attribute tidak ditemukan.',
            ]);

            if ($validator->fails()) {
                $errors = implode(', ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ], 422);
            }

            $kategoriTransaksi = KategoriTransaksi::find($request->id);

            if (!$kategoriTransaksi) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            $kategoriTransaksi->name  = $request->kategori;
            $kategoriTransaksi->tipe_transaksi_id = $request->tipe;
            $kategoriTransaksi->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data pengguna berhasil diperbarui.',
                'data'    => $kategoriTransaksi
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
                'id'    => 'required|exists:kategori_transaksis,id',
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

            $kategoriTransaksi = KategoriTransaksi::where('id', $request->id)->first();
            if ($kategoriTransaksi) {
                $kategoriTransaksi->status = 'inactive';
                $kategoriTransaksi->save();
                
                return response()->json([
                    'status'  => true,
                    'data' => $kategoriTransaksi
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
