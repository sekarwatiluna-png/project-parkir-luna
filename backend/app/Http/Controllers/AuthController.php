<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * LOGIN
     * POST /api/login
     */
    public function login(Request $request)
    {
        // Validasi login menggunakan EMAIL
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan EMAIL
        $user = User::where(
            'email',
            $request->email
        )->first();

        // Email tidak ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // Password salah
        if (!Hash::check(
            $request->password,
            $user->password
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // Cek status akun
        // Menggunakan strtolower supaya "aktif" dan "Aktif"
        // sama-sama dianggap aktif.
        if (strtolower($user->status) !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Akun sedang nonaktif',
            ], 403);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken(
            'parkirku-token'
        )->plainTextToken;

        // Response login berhasil
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',

            'token' => $token,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,

                // Tetap dikirim supaya tidak merusak
                // halaman lain yang masih menggunakan username
                'username' => $user->username,

                'email' => $user->email,
                'no_telepon' => $user->no_telepon,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ], 200);
    }

    /**
     * LOGOUT
     * POST /api/logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()?->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}