<?php

namespace App\Http\Controllers;

use App\Models\RekeningKas;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRekeningKasRequest;
use App\Http\Requests\UpdateRekeningKasRequest;
use App\Models\TipeTransaksi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekeningKasController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Rekening Kas - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['reks'] = RekeningKas::where('status', 'active')->get();

            return view('rekening.index', $data);
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
                'nama'  => 'required|string|max:255|unique:rekening_kas,name',
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

            RekeningKas::create([
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
                'id'    => 'required|exists:rekening_kas,id',
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

            $rekeningKas = RekeningKas::where('id', $request->id)->first();
            if ($rekeningKas) {
                return response()->json([
                    'status'  => true,
                    'data' => $rekeningKas
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
                'id'    => 'required|exists:rekening_kas,id',
                'name'  => 'required|string|max:255|unique:rekening_kas,name,' . $request->id . ',id',
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

            $rekeningKas = RekeningKas::find($request->id);

            if (!$rekeningKas) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            $rekeningKas->name  = $request->name;
            $rekeningKas->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data pengguna berhasil diperbarui.',
                'data'    => $rekeningKas
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
                'id'    => 'required|exists:rekening_kas,id',
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

            $rekeningKas = RekeningKas::where('id', $request->id)->first();
            if ($rekeningKas) {
                $rekeningKas->status = 'inactive';
                $rekeningKas->save();
                
                return response()->json([
                    'status'  => true,
                    'data' => $rekeningKas
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
