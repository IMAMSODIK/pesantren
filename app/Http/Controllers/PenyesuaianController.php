<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JurnalDetail;
use App\Models\KategoriTransaksi;
use App\Models\Transaksi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PenyesuaianController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Penyesuaian - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['transaksis'] = Transaksi::with(['kategoriTransaksi', 'createdBy'])
                ->where('status', 'active')
                ->where('tipe', 'penyesuaian')
                ->get();
            $data['kategoris'] = KategoriTransaksi::whereIn('kode', ['103', '104', '304', '305'])->get();
            $data['all_kategories'] = KategoriTransaksi::all();

            return view('penyesuaian.index', $data);
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
                'tanggal'   => 'required|date',
                'jenis'     => 'required|in:103,104,304,305',
                'nominal'   => 'required|numeric|min:1',
                'deskripsi' => 'nullable|string|max:255',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'in'       => 'Jenis penyesuaian tidak valid.',
                'numeric'  => 'Kolom :attribute harus berupa angka.',
                'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());
                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            $id = KategoriTransaksi::where('kode', '103')->first();

            DB::beginTransaction();

            $transaksi = Transaksi::create([
                'tanggal'   => $request->tanggal,
                'kategori_transaksi_id' => $id->id, 
                'nominal'   => $request->nominal,
                'deskripsi' => $request->deskripsi,
                'tipe'      => 'penyesuaian',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            $debit = null;
            $kredit = null;

            switch ($request->jenis) {
                case '103':
                    $debit  = KategoriTransaksi::where('kode', '103')->first();
                    $kredit = KategoriTransaksi::where('kode', '501')->first();
                    break;

                case '104':
                    $debit  = KategoriTransaksi::where('kode', '104')->first();
                    $kredit = KategoriTransaksi::where('kode', '502')->first();
                    break;

                case '304':
                    $debit  = KategoriTransaksi::where('kode', '602')->first();
                    $kredit = KategoriTransaksi::where('kode', '304')->first();
                    break;

                case '305':
                    $debit  = KategoriTransaksi::where('kode', '604')->first();
                    $kredit = KategoriTransaksi::where('kode', '305')->first();
                    break;
            }

            if (!$debit || !$kredit) {
                throw new \Exception("Akun penyesuaian tidak ditemukan. Silakan setup kategori transaksi.");
            }

            JurnalDetail::create([
                'transaksi_id' => $transaksi->id,
                'kategori_transaksi_id' => $debit->id,
                'posisi'       => 'debit',
                'nominal'      => $request->nominal,
            ]);

            JurnalDetail::create([
                'transaksi_id' => $transaksi->id,
                'kategori_transaksi_id' => $kredit->id,
                'posisi'       => 'kredit',
                'nominal'      => $request->nominal,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Penyesuaian berhasil dicatat!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->id;

            $transaksi = Transaksi::with(['kategoriTransaksi', 'createdBy', 'updatedBy'])
                ->find($id);

            if (!$transaksi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data transaksi tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'        => 'required|exists:transaksis,id',
                'tanggal'   => 'required|date',
                'kategori'  => 'required|exists:kategori_transaksis,id',
                'nominal'   => 'required|numeric|min:1',
                'deskripsi' => 'nullable|string|max:255',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'exists'   => 'Kategori transaksi tidak ditemukan.',
                'numeric'  => 'Kolom :attribute harus berupa angka.',
                'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
                'max'      => 'Ukuran file maksimal 5MB.'
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());
                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            $transaksi = Transaksi::findOrFail($request->id);

            $transaksi->tanggal     = $request->tanggal;
            $transaksi->kategori_transaksi_id = $request->kategori;
            $transaksi->nominal     = $request->nominal;
            $transaksi->deskripsi   = $request->deskripsi;
            $transaksi->updated_by  = Auth::id();

            $transaksi->save();

            JurnalDetail::where('transaksi_id', $transaksi->id)
                ->update([
                    'kategori_transaksi_id'   => $transaksi->kategori_transaksi_id,
                    'nominal'     => $transaksi->nominal,
                    'updated_at'  => now(),
                ]);

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi & Jurnal detail berhasil diperbarui',
                'data'    => $transaksi
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
                'id'    => 'required|exists:transaksis,id',
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

            $transaksi = Transaksi::where('id', $request->id)->first();
            if ($transaksi) {
                $transaksi->status = 'inactive';
                $transaksi->save();

                return response()->json([
                    'status'  => true,
                    'data' => $transaksi
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

    public function search(Request $request)
    {
        $query = Transaksi::with(['createdBy', 'kategoriTransaksi']);

        $query = Transaksi::with(['createdBy', 'kategoriTransaksi'])
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('deskripsi', 'LIKE', '%' . $request->name . '%');
            })
            ->when($request->filled('kategori') && $request->kategori !== 'all', function ($q) use ($request) {
                $q->where('kategori_transaksi_id', $request->kategori);
            })
            ->when($request->filled('tanggal_mulai'), function ($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->tanggal_mulai);
            })
            ->when($request->filled('tanggal_akhir'), function ($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->tanggal_akhir);
            });
        $transaksis = $query->latest()->get();

        $html = view('penyesuaian.partials.list', compact('transaksis'))->render();

        return response()->json(['html' => $html]);
    }
}
