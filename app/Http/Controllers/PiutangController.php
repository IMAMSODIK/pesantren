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

class PiutangController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Piutang - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            // Ambil transaksi yang berhubungan dengan piutang
            $data['transaksis'] = Transaksi::with(['kategoriTransaksi', 'createdBy'])
                ->where('status', 'active')
                ->where('tipe', 'piutang')
                ->get();

            // Ambil daftar akun piutang
            $data['kategoris'] = KategoriTransaksi::whereIn('kode', [
                '103', // Piutang SPP
                '104', // Piutang Uang Pembangunan
                '106', // Piutang Guru dan Karyawan
                '107', // Piutang Lainnya
            ])->get();

            return view('piutang.index', $data);
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
                'tipe'      => 'piutang',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            // Cek akun pendapatan (contoh default kode 601)
            $akunPendapatan = KategoriTransaksi::where('kode', '601')->first();
            if (!$akunPendapatan) {
                throw new \Exception("Akun Pendapatan belum tersedia, silakan setup akun terlebih dahulu.");
            }

            // Debit Piutang (akun sesuai kategori yang dipilih user)
            JurnalDetail::create([
                'transaksi_id' => $transaksi->id,
                'kategori_transaksi_id' => $request->kategori,
                'posisi'       => 'debit',
                'nominal'      => $request->nominal,
            ]);

            // Kredit Pendapatan
            JurnalDetail::create([
                'transaksi_id' => $transaksi->id,
                'kategori_transaksi_id' => $akunPendapatan->id,
                'posisi'       => 'kredit',
                'nominal'      => $request->nominal,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi piutang berhasil disimpan!',
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
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'exists'   => 'Data :attribute tidak ditemukan.',
                'numeric'  => 'Kolom :attribute harus berupa angka.',
                'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
                'file'     => 'Kolom :attribute harus berupa file.',
                'mimes'    => 'File harus berupa jpg, jpeg, png, atau pdf.',
                'max'      => 'Ukuran file maksimal 5MB.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => implode(' ', $validator->errors()->all()),
                ], 422);
            }

            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($request->id);

            $transaksi->tanggal   = $request->tanggal;
            $transaksi->kategori_transaksi_id = $request->kategori;
            $transaksi->nominal   = $request->nominal;
            $transaksi->deskripsi = $request->deskripsi;
            $transaksi->updated_by = Auth::id();

            // handle bukti baru
            if ($request->hasFile('bukti')) {
                if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
                    Storage::disk('public')->delete($transaksi->bukti);
                }
                $transaksi->bukti = $request->file('bukti')->store('bukti-transaksi', 'public');
            }

            $transaksi->save();

            // --- Perbaiki jurnal ---
            // Hapus jurnal lama, kemudian buat ulang (lebih aman)
            JurnalDetail::where('transaksi_id', $transaksi->id)->delete();

            $akunKas = KategoriTransaksi::where('kode', '101')->first();
            if (!$akunKas) {
                throw new \Exception("Akun Kas (101) belum tersedia.");
            }

            // Debit piutang / kategori yang dipilih
            JurnalDetail::create([
                'transaksi_id'            => $transaksi->id,
                'kategori_transaksi_id'   => $request->kategori,
                'posisi'                  => 'debit',
                'nominal'                 => $request->nominal,
            ]);

            // Kredit kas
            JurnalDetail::create([
                'transaksi_id'            => $transaksi->id,
                'kategori_transaksi_id'   => $akunKas->id,
                'posisi'                  => 'kredit',
                'nominal'                 => $request->nominal,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi & jurnal berhasil diperbarui',
                'data'    => $transaksi
            ], 200);
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
                'id' => 'required|exists:transaksis,id',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'exists'   => 'Data :attribute tidak ditemukan.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => implode(' ', $validator->errors()->all()),
                ], 422);
            }

            DB::beginTransaction();

            $transaksi = Transaksi::find($request->id);

            if (!$transaksi) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data transaksi tidak ditemukan.'
                ], 404);
            }

            // Soft delete transaksi
            $transaksi->status = 'inactive';
            $transaksi->updated_by = Auth::id();
            $transaksi->save();

            // Soft delete jurnal detail terkait
            JurnalDetail::where('transaksi_id', $transaksi->id)
                ->update([
                    'status'     => 'inactive',
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi & jurnal berhasil dinonaktifkan',
                'data'    => $transaksi
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Kesalahan database: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function search(Request $request)
    {
        try {
            // Query hanya untuk transaksi piutang
            $query = Transaksi::with(['createdBy', 'kategoriTransaksi'])
                ->whereHas('kategoriTransaksi', function ($q) {
                    $q->where('tipe', 'piutang');
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

            $html = view('transaksi.partials.list', compact('transaksis'))->render();

            return response()->json([
                'status' => true,
                'html'   => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
