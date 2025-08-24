<?php

namespace App\Http\Controllers;

use App\Models\TipeTransaksi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipeTransaksiRequest;
use App\Http\Requests\UpdateTipeTransaksiRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipeTransaksiController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Tipe Transaksi - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['types'] = TipeTransaksi::where('status', 'active')->get();

            return view('tipe.index', $data);
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
                'nama'  => 'required|string|max:255|unique:tipe_transaksis,name',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'string'   => 'Kolom :attribute harus berupa teks.',
                'max'      => [
                    'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
                ],
                'unique'   => 'Kolom :attribute sudah digunakan.',
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            TipeTransaksi::create([
                'name'     => $request->nama,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'User berhasil ditambahkan.',
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

            $tipeTransaksi = TipeTransaksi::where('id', $request->id)->first();
            if ($tipeTransaksi) {
                return response()->json([
                    'status'  => true,
                    'data' => $tipeTransaksi
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
                'id'    => 'required|exists:tipe_transaksis,id',
                'name'  => 'required|string|max:255|unique:tipe_transaksis,name,' . $request->id . ',id',
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

            $tipeTransaksi = TipeTransaksi::find($request->id);

            if (!$tipeTransaksi) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            $tipeTransaksi->name  = $request->name;
            $tipeTransaksi->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data pengguna berhasil diperbarui.',
                'data'    => $tipeTransaksi
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
                'id'    => 'required|exists:tipe_transaksis,id',
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

            $tipeTransaksi = TipeTransaksi::where('id', $request->id)->first();
            if ($tipeTransaksi) {
                $tipeTransaksi->status = 'inactive';
                $tipeTransaksi->save();
                
                return response()->json([
                    'status'  => true,
                    'data' => $tipeTransaksi
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
