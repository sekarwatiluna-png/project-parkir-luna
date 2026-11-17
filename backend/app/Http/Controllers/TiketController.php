<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TiketController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        try {

            $tiket = Tiket::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'status' => true,
                'message' => 'Data tiket berhasil diambil',
                'data' => $tiket
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Gagal mengambil data tiket',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE KODE TIKET
    |--------------------------------------------------------------------------
    */

    private function generateKodeTiket()
    {
        do {

            $kode =
                'TKT-' .
                strtoupper(Str::random(6));

        } while (
            Tiket::where('kode_tiket', $kode)->exists()
        );

        return $kode;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE TOKEN TIKET
    |--------------------------------------------------------------------------
    */

    private function generateTokenTiket()
    {
        do {

            $token = (string) Str::uuid();

        } while (
            Tiket::where('token', $token)->exists()
        );

        return $token;
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    | POST /api/tiket
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        try {

            $request->validate([
                'plat_nomor' => 'nullable|string|max:50',
                'jenis_kendaraan' => 'nullable|string|in:Motor,Mobil',
                'tipe' => 'nullable|string',
                'kode_member' => 'nullable|string'
            ]);


            $tipe =
                strtolower(
                    trim(
                        $request->tipe ?? 'nonmember'
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | MEMBER
            |--------------------------------------------------------------------------
            */

            if (
                $tipe === 'member' ||
                $request->filled('kode_member')
            ) {

                $kodeMember =
                    trim(
                        $request->kode_member ?? ''
                    );


                if ($kodeMember === '') {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Kode member atau token member wajib diisi.'
                    ], 422);
                }


                /*
                 * CARI MEMBER
                 */

                $member =
                    Member::where(
                        'kode_member',
                        $kodeMember
                    )
                    ->orWhere(
                        'token',
                        $kodeMember
                    )
                    ->first();


                if (!$member) {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Member tidak ditemukan.'
                    ], 404);
                }


                /*
                 * CEK STATUS
                 */

                if (
                    strtolower(
                        trim(
                            $member->status ?? ''
                        )
                    ) !== 'lunas'
                ) {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Member belum lunas.'
                    ], 422);
                }


                /*
                 * CEK EXPIRED
                 */

                if (
                    $member->tanggal_expired &&
                    Carbon::now()->greaterThan(
                        Carbon::parse(
                            $member->tanggal_expired
                        )
                    )
                ) {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Masa berlaku member sudah expired.'
                    ], 422);
                }


                /*
                 * CEK MEMBER MASIH DI DALAM
                 */

                if (
                    (bool) $member->sedang_parkir === true
                ) {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Member masih berada di dalam area parkir.'
                    ], 422);
                }


                /*
                 * CEK TIKET AKTIF MEMBER
                 */

                $tiketAktif =
                    Tiket::where(
                        'plat_nomor',
                        $member->kode_member
                    )
                    ->whereNull(
                        'waktu_keluar'
                    )
                    ->latest('id')
                    ->first();


                if ($tiketAktif) {

                    $member->sedang_parkir = true;
                    $member->save();

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Member masih memiliki tiket aktif.'
                    ], 422);
                }


                /*
                 * TOKEN TIKET BARU
                 */

                $tokenTiket =
                    $this->generateTokenTiket();


                /*
                 * KODE TIKET BARU
                 */

                $kodeTiket =
                    $this->generateKodeTiket();


                /*
                 * BUAT TIKET MEMBER
                 */

                $tiket =
                    Tiket::create([

                        'kode_tiket' =>
                            $kodeTiket,

                        'token' =>
                            $tokenTiket,

                        'plat_nomor' =>
                            $member->kode_member,

                        'jenis_kendaraan' =>
                            'Member',

                        'waktu_masuk' =>
                            now(),

                        'waktu_keluar' =>
                            null,

                        'biaya' =>
                            0

                    ]);


                /*
                 * MEMBER SEKARANG SEDANG PARKIR
                 */

                $member->sedang_parkir = true;
                $member->save();


                return response()->json([
                    'success' => true,
                    'status' => true,
                    'message' => 'Member berhasil masuk ke area parkir.',
                    'data' => [

                        'tiket' => $tiket,

                        'member' => [

                            'id' =>
                                $member->id,

                            'kode_member' =>
                                $member->kode_member,

                            'token' =>
                                $member->token,

                            'nama_member' =>
                                $member->nama_member,

                            'nama_perusahaan' =>
                                $member->nama_perusahaan,

                            'status' =>
                                $member->status,

                            'sedang_parkir' =>
                                true,

                            'tanggal_expired' =>
                                $member->tanggal_expired

                        ]

                    ]
                ], 201);
            }


            /*
            |--------------------------------------------------------------------------
            | NON MEMBER
            |--------------------------------------------------------------------------
            */

            $request->validate([
                'plat_nomor' =>
                    'required|string|max:50',

                'jenis_kendaraan' =>
                    'required|string|in:Motor,Mobil'
            ]);


            $platNomor =
                strtoupper(
                    trim(
                        $request->plat_nomor
                    )
                );


            $jenisKendaraan =
                trim(
                    $request->jenis_kendaraan
                );


            /*
             * SAAT MASUK BIAYA HARUS 0
             *
             * Motor = Rp3.000/jam
             * Mobil = Rp5.000/jam
             *
             * Tarif dihitung nanti saat kendaraan keluar.
             */


            /*
             * TOKEN BARU
             */

            $tokenTiket =
                $this->generateTokenTiket();


            /*
             * KODE TIKET BARU
             */

            $kodeTiket =
                $this->generateKodeTiket();


            /*
             * BUAT TIKET NON MEMBER
             */

            $tiket =
                Tiket::create([

                    'kode_tiket' =>
                        $kodeTiket,

                    'token' =>
                        $tokenTiket,

                    'plat_nomor' =>
                        $platNomor,

                    'jenis_kendaraan' =>
                        $jenisKendaraan,

                    'waktu_masuk' =>
                        now(),

                    'waktu_keluar' =>
                        null,

                    /*
                     * BIAYA SAAT MASUK = 0
                     */
                    'biaya' =>
                        0

                ]);


            return response()->json([
                'success' => true,
                'status' => true,
                'message' => 'Tiket berhasil dibuat.',
                'data' => $tiket
            ], 201);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Gagal membuat tiket.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SCAN MASUK
    |--------------------------------------------------------------------------
    | POST /api/scan/masuk
    |--------------------------------------------------------------------------
    */

    public function scanMasuk(Request $request)
    {
        return $this->store($request);
    }


    /*
    |--------------------------------------------------------------------------
    | SCAN KELUAR
    |--------------------------------------------------------------------------
    | POST /api/scan/keluar
    |--------------------------------------------------------------------------
    */

    public function scanKeluar(Request $request)
    {
        try {

            $request->validate([
                'plat_nomor' =>
                    'nullable|string|max:50',

                'kode_tiket' =>
                    'required|string'
            ]);


            $kode =
                trim(
                    $request->kode_tiket
                );


            /*
            |--------------------------------------------------------------------------
            | CARI MEMBER
            |--------------------------------------------------------------------------
            */

            $member =
                Member::where(
                    'token',
                    $kode
                )
                ->orWhere(
                    'kode_member',
                    $kode
                )
                ->first();


            /*
            |--------------------------------------------------------------------------
            | MEMBER
            |--------------------------------------------------------------------------
            */

            if ($member) {

                if (
                    (bool) $member->sedang_parkir !== true
                ) {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Member tidak sedang berada di dalam area parkir.'
                    ], 422);
                }


                /*
                 * CARI TIKET AKTIF
                 */

                $tiket =
                    Tiket::where(
                        'plat_nomor',
                        $member->kode_member
                    )
                    ->whereNull(
                        'waktu_keluar'
                    )
                    ->latest('id')
                    ->first();


                if (!$tiket) {

                    $member->sedang_parkir = false;
                    $member->save();

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Tiket member aktif tidak ditemukan.'
                    ], 404);
                }


                /*
                 * RESPONSE HASIL SCAN
                 */

                return response()->json([
                    'success' => true,
                    'status' => true,
                    'message' => 'Member berhasil ditemukan.',
                    'data' => [

                        'kode_tiket' =>
                            $tiket->kode_tiket,

                        'token' =>
                            $tiket->token,

                        'plat_nomor' =>
                            $member->kode_member,

                        'jenis_kendaraan' =>
                            'Member',

                        'waktu_masuk' =>
                            $tiket->waktu_masuk,

                        'waktu_keluar' =>
                            null,

                        'biaya' =>
                            0,

                        'member' => [

                            'id' =>
                                $member->id,

                            'kode_member' =>
                                $member->kode_member,

                            'token' =>
                                $member->token,

                            'nama_member' =>
                                $member->nama_member,

                            'nama_perusahaan' =>
                                $member->nama_perusahaan,

                            'status' =>
                                $member->status,

                            'sedang_parkir' =>
                                true,

                            'tanggal_expired' =>
                                $member->tanggal_expired

                        ]

                    ]
                ], 200);
            }


            /*
            |--------------------------------------------------------------------------
            | NON MEMBER
            |--------------------------------------------------------------------------
            */

            $tiket =
                Tiket::where(
                    'kode_tiket',
                    $kode
                )
                ->whereNull(
                    'waktu_keluar'
                )
                ->latest('id')
                ->first();


            if (!$tiket) {

                $tiket =
                    Tiket::where(
                        'token',
                        $kode
                    )
                    ->whereNull(
                        'waktu_keluar'
                    )
                    ->latest('id')
                    ->first();
            }


            /*
             * TIDAK DITEMUKAN
             */

            if (!$tiket) {

                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => 'Tiket kendaraan tidak ditemukan.'
                ], 404);
            }


            /*
             * MEMBER TIDAK BOLEH MASUK SINI
             */

            if (
                strtolower(
                    trim(
                        $tiket->jenis_kendaraan ?? ''
                    )
                ) === 'member'
            ) {

                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => 'Data tiket member tidak valid.'
                ], 422);
            }


            return response()->json([
                'success' => true,
                'status' => true,
                'message' => 'Tiket kendaraan ditemukan.',
                'data' => $tiket
            ], 200);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Gagal mencari tiket.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN KELUAR
    |--------------------------------------------------------------------------
    | POST /api/scan/keluar/selesaikan
    |--------------------------------------------------------------------------
    */

    public function selesaikanKeluar(Request $request)
    {
        try {

            $request->validate([
                'plat_nomor' =>
                    'nullable|string|max:50',

                'kode_tiket' =>
                    'required|string'
            ]);


            $kode =
                trim(
                    $request->kode_tiket
                );


            /*
            |--------------------------------------------------------------------------
            | CARI MEMBER
            |--------------------------------------------------------------------------
            */

            $member =
                Member::where(
                    'token',
                    $kode
                )
                ->orWhere(
                    'kode_member',
                    $kode
                )
                ->first();


            /*
            |--------------------------------------------------------------------------
            | SELESAIKAN MEMBER
            |--------------------------------------------------------------------------
            */

            if ($member) {

                if (
                    (bool) $member->sedang_parkir !== true
                ) {

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Member tidak sedang berada di dalam area parkir.'
                    ], 422);
                }


                /*
                 * CARI TIKET AKTIF
                 */

                $tiket =
                    Tiket::where(
                        'plat_nomor',
                        $member->kode_member
                    )
                    ->whereNull(
                        'waktu_keluar'
                    )
                    ->latest('id')
                    ->first();


                if (!$tiket) {

                    $member->sedang_parkir = false;
                    $member->save();

                    return response()->json([
                        'success' => false,
                        'status' => false,
                        'message' => 'Tiket member aktif tidak ditemukan.'
                    ], 404);
                }


                /*
                 * TUTUP TIKET
                 */

                $tiket->waktu_keluar =
                    now();

                $tiket->biaya =
                    0;

                $tiket->save();


                /*
                 * MEMBER SUDAH KELUAR
                 */

                $member->sedang_parkir =
                    false;

                $member->save();


                return response()->json([
                    'success' => true,
                    'status' => true,
                    'message' => 'Member berhasil keluar dari area parkir.',
                    'data' => [

                        'kode_tiket' =>
                            $tiket->kode_tiket,

                        'token' =>
                            $tiket->token,

                        'plat_nomor' =>
                            $member->kode_member,

                        'jenis_kendaraan' =>
                            'Member',

                        'waktu_masuk' =>
                            $tiket->waktu_masuk,

                        'waktu_keluar' =>
                            $tiket->waktu_keluar,

                        'biaya' =>
                            0,

                        'member' => [

                            'id' =>
                                $member->id,

                            'kode_member' =>
                                $member->kode_member,

                            'token' =>
                                $member->token,

                            'nama_member' =>
                                $member->nama_member,

                            'nama_perusahaan' =>
                                $member->nama_perusahaan,

                            'status' =>
                                $member->status,

                            'sedang_parkir' =>
                                false,

                            'tanggal_expired' =>
                                $member->tanggal_expired

                        ]

                    ]
                ], 200);
            }


            /*
            |--------------------------------------------------------------------------
            | NON MEMBER
            |--------------------------------------------------------------------------
            */

            $tiket =
                Tiket::where(
                    'kode_tiket',
                    $kode
                )
                ->whereNull(
                    'waktu_keluar'
                )
                ->latest('id')
                ->first();


            if (!$tiket) {

                $tiket =
                    Tiket::where(
                        'token',
                        $kode
                    )
                    ->whereNull(
                        'waktu_keluar'
                    )
                    ->latest('id')
                    ->first();
            }


            if (!$tiket) {

                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => 'Tiket kendaraan tidak ditemukan.'
                ], 404);
            }


            /*
             * CEK JANGAN MEMBER
             */

            if (
                strtolower(
                    trim(
                        $tiket->jenis_kendaraan ?? ''
                    )
                ) === 'member'
            ) {

                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => 'Silakan gunakan kode member untuk tiket member.'
                ], 422);
            }


            /*
             * WAKTU MASUK
             */

            $waktuMasuk =
                Carbon::parse(
                    $tiket->waktu_masuk
                );


            /*
             * WAKTU KELUAR
             */

            $waktuKeluar =
                Carbon::now();


            /*
             * HITUNG DURASI
             */

            $menit =
                $waktuMasuk->diffInMinutes(
                    $waktuKeluar
                );


            /*
             * SETIAP 1 JAM
             *
             * Contoh:
             *
             * 00 - 60 menit  = 1 jam
             * 61 - 120 menit = 2 jam
             * 121 - 180 menit = 3 jam
             */

            $jam =
                max(
                    1,
                    (int) ceil(
                        $menit / 60
                    )
                );


            /*
             |--------------------------------------------------------------------------
             | TARIF PARKIR
             |--------------------------------------------------------------------------
             |
             | MOTOR = Rp3.000 / jam
             | MOBIL = Rp5.000 / jam
             |
             |--------------------------------------------------------------------------
             */

            if (
                strtolower(
                    trim(
                        $tiket->jenis_kendaraan
                    )
                ) === 'motor'
            ) {

                $tarifPerJam =
                    3000;

            } else {

                $tarifPerJam =
                    5000;
            }


            /*
             * TOTAL BIAYA
             */

            $totalBiaya =
                $jam *
                $tarifPerJam;


            /*
             * SIMPAN WAKTU KELUAR
             */

            $tiket->waktu_keluar =
                $waktuKeluar;


            /*
             * SIMPAN BIAYA AKHIR
             */

            $tiket->biaya =
                $totalBiaya;


            $tiket->save();


            /*
             * RESPONSE
             */

            return response()->json([
                'success' => true,
                'status' => true,
                'message' => 'Kendaraan berhasil keluar.',
                'data' => [

                    'kode_tiket' =>
                        $tiket->kode_tiket,

                    'token' =>
                        $tiket->token,

                    'plat_nomor' =>
                        $tiket->plat_nomor,

                    'jenis_kendaraan' =>
                        $tiket->jenis_kendaraan,

                    'waktu_masuk' =>
                        $tiket->waktu_masuk,

                    'waktu_keluar' =>
                        $tiket->waktu_keluar,

                    'biaya' =>
                        $tiket->biaya,

                    'durasi_jam' =>
                        $jam,

                    'tarif_per_jam' =>
                        $tarifPerJam

                ]
            ], 200);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Gagal memproses kendaraan keluar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}