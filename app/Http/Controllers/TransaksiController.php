<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use App\Models\JurnalDetail;
use App\Models\KategoriTransaksi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Penerimaan Dana - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            // Ambil transaksi penerimaan
            $data['transaksis'] = Transaksi::with(['kategoriTransaksi', 'createdBy'])
                ->where('status', 'active')
                ->where('tipe', 'penerimaan')
                ->whereHas('kategoriTransaksi', function ($q) {
                    $q->whereIn('tipe_transaksi_id', [6, 7]) // pendapatan
                        ->orWhereIn('kode', ['103', '104', '106', '107']); // piutang
                })
                ->get();

            // Ambil kategori untuk dropdown: hanya piutang dan pendapatan
            $data['kategoris'] = KategoriTransaksi::where(function ($q) {
                $q->whereIn('tipe_transaksi_id', [6, 7]) // pendapatan
                    ->orWhereIn('kode', ['103', '104', '106', '107']); // piutang
            })->get();

            return view('transaksi.index', $data);
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
                'kategori'  => 'required|exists:kategori_transaksis,id',
                'nominal'   => 'required|numeric|min:1',
                'deskripsi' => 'nullable|string|max:255',
                'bukti'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'exists'   => 'Kategori transaksi tidak ditemukan.',
                'numeric'  => 'Kolom :attribute harus berupa angka.',
                'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
                'file'     => 'Kolom :attribute harus berupa file.',
                'mimes'    => 'File harus berupa jpg, jpeg, png, atau pdf.',
                'max'      => 'Ukuran file maksimal 5MB.'
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());
                return response()->json([
                    'status'  => false,
                    'message' => $errors,
                ]);
            }

            DB::beginTransaction();

            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('bukti-transaksi', 'public');
            }

            $transaksi = Transaksi::create([
                'tanggal'   => $request->tanggal,
                'kategori_transaksi_id' => $request->kategori,
                'nominal'   => $request->nominal,
                'deskripsi' => $request->deskripsi,
                'bukti'     => $buktiPath,
                'tipe'      => 'penerimaan',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            $akunKas = KategoriTransaksi::where('kode', '101')->first();
            if (!$akunKas) {
                throw new \Exception("Akun Kas belum tersedia, silakan setup akun terlebih dahulu.");
            }

            // Debit Kas
            JurnalDetail::create([
                'transaksi_id' => $transaksi->id,
                'kategori_transaksi_id' => $akunKas->id,
                'posisi'       => 'debit',
                'nominal'      => $request->nominal,
            ]);

            // Kredit Pendapatan (kategori)
            JurnalDetail::create([
                'transaksi_id' => $transaksi->id,
                'kategori_transaksi_id' => $request->kategori,
                'posisi'       => 'kredit',
                'nominal'      => $request->nominal,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi berhasil disimpan!',
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
                'bukti'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = implode(' ', $validator->errors()->all());
                return response()->json(['status' => false, 'message' => $errors]);
            }

            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($request->id);

            $transaksi->tanggal     = $request->tanggal;
            $transaksi->kategori_transaksi_id = $request->kategori;
            $transaksi->nominal     = $request->nominal;
            $transaksi->deskripsi   = $request->deskripsi;
            $transaksi->updated_by  = Auth::id();

            if ($request->hasFile('bukti')) {
                if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
                    Storage::disk('public')->delete($transaksi->bukti);
                }
                $path = $request->file('bukti')->store('bukti-transaksi', 'public');
                $transaksi->bukti = $path;
            }

            $transaksi->save();

            $akunKas = KategoriTransaksi::where('kode', '101')->first();
            if (!$akunKas) {
                throw new \Exception("Akun Kas belum tersedia.");
            }

            // Update Debit Kas
            JurnalDetail::where('transaksi_id', $transaksi->id)
                ->where('posisi', 'debit')
                ->update([
                    'kategori_transaksi_id' => $akunKas->id,
                    'nominal' => $transaksi->nominal,
                    'updated_at' => now(),
                ]);

            // Update Kredit Kategori
            JurnalDetail::where('transaksi_id', $transaksi->id)
                ->where('posisi', 'kredit')
                ->update([
                    'kategori_transaksi_id' => $transaksi->kategori_transaksi_id,
                    'nominal' => $transaksi->nominal,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi & Jurnal detail berhasil diperbarui',
                'data'    => $transaksi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
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
        Log::info('check_sum', $request->all());

        $query = Transaksi::with(['createdBy', 'kategoriTransaksi']);

        $query = Transaksi::with(['createdBy', 'kategoriTransaksi'])
            ->whereHas('kategoriTransaksi', function ($q) {
                $q->where('tipe', 'penerimaan');
            })
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

        Log::info($query->toSql());
        Log::info($query->getBindings());

        $html = view('transaksi.partials.list', compact('transaksis'))->render();

        return response()->json(['html' => $html]);
    }
}
