<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STEP 1
    | Cek Email
    |--------------------------------------------------------------------------
    */

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK NOMOR TELEPON
        |--------------------------------------------------------------------------
        */

        if (empty($user->no_telepon)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp belum terdaftar pada akun ini.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email ditemukan.',
            'user_id' => $user->id,
            'nama' => $user->name ?? 'User',
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 2
    | Cek Nomor WhatsApp + Kirim OTP
    |--------------------------------------------------------------------------
    */

    public function checkPhone(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'integer',
            ],

            'no_telepon' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = User::find(
            $request->user_id
        );

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI NOMOR
        |--------------------------------------------------------------------------
        */

        $inputPhone = $this->normalizePhone(
            $request->no_telepon
        );

        $databasePhone = $this->normalizePhone(
            $user->no_telepon
        );

        /*
        |--------------------------------------------------------------------------
        | CEK NOMOR
        |--------------------------------------------------------------------------
        */

        if ($inputPhone !== $databasePhone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak sesuai dengan data akun.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL KONFIGURASI FONNTE
        |--------------------------------------------------------------------------
        */

        $token = config(
            'services.fonnte.token'
        );

        $url = config(
            'services.fonnte.url',
            'https://api.fonnte.com/send'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK TOKEN
        |--------------------------------------------------------------------------
        */

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token Fonnte belum dikonfigurasi di server.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | BUAT OTP
        |--------------------------------------------------------------------------
        */

        $otp = (string) random_int(
            100000,
            999999
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN OTP
        | Berlaku 5 menit
        |--------------------------------------------------------------------------
        */

        Cache::put(
            'forgot_password_otp_' . $user->id,
            $otp,
            now()->addMinutes(5)
        );

        /*
        |--------------------------------------------------------------------------
        | HAPUS STATUS VERIFIKASI LAMA
        |--------------------------------------------------------------------------
        */

        Cache::forget(
            'forgot_password_verified_' . $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | PESAN WHATSAPP
        |--------------------------------------------------------------------------
        */

        $nama = $user->name ?? 'User';

        $message =
            "ParkirKu - Plaza Andalas\n\n" .
            "Halo {$nama},\n\n" .
            "Kode OTP untuk reset password kamu adalah:\n\n" .
            "{$otp}\n\n" .
            "Kode OTP berlaku selama 5 menit.\n\n" .
            "Jangan berikan kode OTP ini kepada siapa pun.";

        /*
        |--------------------------------------------------------------------------
        | KIRIM KE FONNTE
        |--------------------------------------------------------------------------
        */

        try {

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])
            ->timeout(30)
            ->post($url, [
                'target' => $inputPhone,
                'message' => $message,
            ]);

        } catch (\Throwable $e) {

            Cache::forget(
                'forgot_password_otp_' . $user->id
            );

            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server Fonnte.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK HTTP RESPONSE
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {

            Cache::forget(
                'forgot_password_otp_' . $user->id
            );

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP ke WhatsApp.',
                'fonnte_response' => $response->json(),
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK RESPONSE FONNTE
        |--------------------------------------------------------------------------
        */

        $result = $response->json();

        if (
            isset($result['status']) &&
            $result['status'] === false
        ) {

            Cache::forget(
                'forgot_password_otp_' . $user->id
            );

            return response()->json([
                'success' => false,
                'message' => $result['reason']
                    ?? 'Fonnte gagal mengirim OTP.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil dikirim ke WhatsApp.',
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 3
    | Verifikasi OTP
    |--------------------------------------------------------------------------
    */

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'integer',
            ],

            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL OTP DARI CACHE
        |--------------------------------------------------------------------------
        */

        $savedOtp = Cache::get(
            'forgot_password_otp_' . $request->user_id
        );

        if (!$savedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah kedaluwarsa. Silakan kirim OTP baru.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK OTP
        |--------------------------------------------------------------------------
        */

        if ((string) $savedOtp !== (string) $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATUS OTP SUDAH DIVERIFIKASI
        | Berlaku 10 menit
        |--------------------------------------------------------------------------
        */

        Cache::put(
            'forgot_password_verified_' . $request->user_id,
            true,
            now()->addMinutes(10)
        );

        /*
        |--------------------------------------------------------------------------
        | OTP TIDAK BISA DIPAKAI LAGI
        |--------------------------------------------------------------------------
        */

        Cache::forget(
            'forgot_password_otp_' . $request->user_id
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil diverifikasi.',
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 4
    | Reset Password
    |--------------------------------------------------------------------------
    */

    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'integer',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH OTP SUDAH DIVERIFIKASI
        |--------------------------------------------------------------------------
        */

        $verified = Cache::get(
            'forgot_password_verified_' . $request->user_id
        );

        if (!$verified) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan verifikasi OTP terlebih dahulu.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = User::find(
            $request->user_id
        );

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PASSWORD BARU
        |--------------------------------------------------------------------------
        */

        $user->password = Hash::make(
            $request->password
        );

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | HAPUS CACHE
        |--------------------------------------------------------------------------
        */

        Cache::forget(
            'forgot_password_verified_' . $user->id
        );

        Cache::forget(
            'forgot_password_otp_' . $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ], 200);
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALISASI NOMOR TELEPON
    |--------------------------------------------------------------------------
    */

    private function normalizePhone($phone)
    {
        $phone = preg_replace(
            '/[^0-9]/',
            '',
            (string) $phone
        );

        /*
        | 08123456789
        | menjadi
        | 628123456789
        */

        if (str_starts_with($phone, '08')) {
            return '62' . substr(
                $phone,
                1
            );
        }

        /*
        | 8123456789
        | menjadi
        | 628123456789
        */

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        /*
        | Sudah 62
        */

        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        return $phone;
    }
}