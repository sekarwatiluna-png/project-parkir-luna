<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScanController extends Controller
{
    /**
     * ================================================
     * SCAN KELUAR
     * ================================================
     */
    public function keluar(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $kode = trim($request->token);

        /*
        |--------------------------------------------------------------------------
        | 1. CEK MEMBER
        |--------------------------------------------------------------------------
        */

        $member = DB::table('members')
            ->where('token', $kode)
            ->orWhere('kode_member', $kode)
            ->first();

        if ($member) {

            return response()->json([
                'success' => true,

                'message' =>
                    'Member berhasil diverifikasi untuk keluar.',

                'data' => [
                    'id' =>
                        $member->id,

                    'kode_member' =>
                        $member->kode_member,

                    'kode' =>
                        $member->kode_member,

                    'token' =>
                        $member->token,

                    'nama_member' =>
                        $member->nama_member,

                    'nama' =>
                        $member->nama_member,

                    'nama_perusahaan' =>
                        $member->nama_perusahaan,

                    'status' =>
                        $member->status,

                    'tanggal_mulai' =>
                        $member->tanggal_mulai,

                    'tanggal_expired' =>
                        $member->tanggal_expired,

                    'is_member' =>
                        true,

                    'status_text' =>
                        'Member terdaftar'
                ]
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. CEK TIKET
        |--------------------------------------------------------------------------
        |
        | Bagian ini mencoba mencari tiket berdasarkan beberapa
        | nama kolom yang umum digunakan.
        |
        */

        $tiket = null;

        try {

            $tiket = DB::table('tiketparkir')
                ->where('token', $kode)
                ->orWhere('kode_tiket', $kode)
                ->first();

        } catch (\Throwable $e) {

            /*
             * Jika tabel tiketparkir belum ada,
             * jangan membuat Laravel error fatal.
             */

            $tiket = null;
        }


        /*
        |--------------------------------------------------------------------------
        | 3. TIKET DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if ($tiket) {

            $waktuMasuk =
                $tiket->waktu_masuk
                ?? $tiket->tanggal_masuk
                ?? null;


            $waktuKeluar =
                Carbon::now();


            /*
            |--------------------------------------------------------------------------
            | HITUNG DURASI
            |--------------------------------------------------------------------------
            */

            $durasi = null;

            if ($waktuMasuk) {

                try {

                    $masuk =
                        Carbon::parse($waktuMasuk);

                    $menit =
                        $masuk->diffInMinutes(
                            $waktuKeluar
                        );

                    $jam =
                        intdiv(
                            $menit,
                            60
                        );

                    $sisaMenit =
                        $menit % 60;

                    if ($jam > 0) {

                        $durasi =
                            $jam .
                            ' jam ' .
                            $sisaMenit .
                            ' menit';

                    } else {

                        $durasi =
                            $sisaMenit .
                            ' menit';

                    }

                } catch (\Throwable $e) {

                    $durasi = null;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | TOTAL BAYAR
            |--------------------------------------------------------------------------
            */

            $totalBayar =
                $tiket->total_bayar
                ?? $tiket->total_harga
                ?? $tiket->harga
                ?? 0;


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Tiket berhasil diproses untuk keluar.',

                'data' => [

                    'id' =>
                        $tiket->id,

                    'kode' =>
                        $tiket->kode_tiket
                        ?? $kode,

                    'kode_tiket' =>
                        $tiket->kode_tiket
                        ?? $kode,

                    'token' =>
                        $tiket->token
                        ?? $kode,

                    'nama' =>
                        $tiket->nama
                        ?? null,

                    'plat_nomor' =>
                        $tiket->plat_nomor
                        ?? $tiket->nomor_plat
                        ?? null,

                    'jenis_kendaraan' =>
                        $tiket->jenis_kendaraan
                        ?? null,

                    'waktu_masuk' =>
                        $waktuMasuk,

                    'waktu_keluar' =>
                        $waktuKeluar
                            ->format(
                                'Y-m-d H:i:s'
                            ),

                    'durasi' =>
                        $durasi,

                    'total_bayar' =>
                        $totalBayar,

                    'is_member' =>
                        false,

                    'status_text' =>
                        'Tiket berhasil ditemukan'

                ]

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | 4. TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                false,

            'message' =>
                'Kode tiket atau token tidak ditemukan.'

        ], 404);
    }
}