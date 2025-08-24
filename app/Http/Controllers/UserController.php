<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'User Management - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['users'] = \App\Models\User::all();

            return view('user.index', $data);
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
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'string'   => 'Kolom :attribute harus berupa teks.',
                'max'      => [
                    'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
                ],
                'email'    => 'Kolom :attribute harus berupa alamat email yang valid.',
                'unique'   => 'Kolom :attribute sudah digunakan.',
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());

                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->email),
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

            $user = User::where('id', $request->id)->first();
            if ($user) {
                return response()->json([
                    'status'  => true,
                    'data' => $user
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
                'id'    => 'required|exists:users,id',
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $request->id . ',id',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'string'   => 'Kolom :attribute harus berupa teks.',
                'max'      => [
                    'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
                ],
                'email'    => 'Kolom :attribute harus berupa alamat email yang valid.',
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

            $user = User::find($request->id);

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            $user->name  = $request->name;
            $user->email = $request->email;
            $user->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data pengguna berhasil diperbarui.',
                'data'    => $user
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

            $user = User::where('id', $request->id)->first();
            if ($user) {
                $user->status = 'inactive';
                $user->save();
                
                return response()->json([
                    'status'  => true,
                    'data' => $user
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
