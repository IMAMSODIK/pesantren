<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(){
        $data = [
            'pageTitle' => 'Profile - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        try {
            $data['users'] = User::where('id', Auth::id())->first();

            return view('profile.index', $data);
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

    public function updateProfile(Request $r){
        try{
            $user = User::where("id", Auth::id())->first();

            if($user){
                $user->name = $r->name;
                $user->email = $r->email;
                $user->save();

                return response()->json([
                    'status' => true
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => "Data tidak ditemukan"
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'password_lama'    => 'required|string',
                'password_baru'    => 'required|string|min:6',
                'confirm_password' => 'required|string|same:password_baru',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'min' => 'Kolom :attribute minimal :min karakter.',
                'same' => 'Kolom konfirmasi password harus sama dengan password baru.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => implode(' ', $validator->errors()->all())
                ]);
            }

            $user = Auth::user();

            if (!Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password lama tidak sesuai.'
                ]);
            }

            $user->password = Hash::make($request->password_baru);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
