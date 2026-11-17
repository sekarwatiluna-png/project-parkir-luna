<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * =========================================================
     * HARGA MEMBER
     * =========================================================
     */

    private const HARGA_MEMBER = 150000;


    /**
     * =========================================================
     * PERIODE BULAN AKTIF
     *
     * Format:
     *
     * September 2026 = 2026-09
     * Oktober 2026   = 2026-10
     *
     * Secara normal mengambil bulan sekarang.
     *
     * Bisa juga dites menggunakan:
     *
     * /api/members?periode=2026-09
     *
     * /api/members?periode=2026-10
     *
     * =========================================================
     */

    private function periodeSekarang(Request $request = null)
    {
        /*
         * Kalau frontend mengirim periode,
         * gunakan periode tersebut.
         *
         * Contoh:
         * ?periode=2026-09
         */

        if ($request && $request->filled('periode')) {

            $periode = $request->input('periode');

            /*
             * Pastikan format periode benar:
             * YYYY-MM
             */

            if (
                preg_match(
                    '/^\d{4}-(0[1-9]|1[0-2])$/',
                    $periode
                )
            ) {
                return $periode;
            }
        }


        /*
         * Kalau tidak ada periode dari request,
         * otomatis menggunakan bulan sekarang.
         */

        return Carbon::now()->format('Y-m');
    }


    /**
     * =========================================================
     * QR MEMBER
     * =========================================================
     */

    private function qrMember($token)
    {
        return
            'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
            . urlencode($token);
    }


    /**
     * =========================================================
     * AMBIL PEMBAYARAN BERDASARKAN PERIODE
     *
     * INI BAGIAN PALING PENTING.
     *
     * Pembayaran September:
     * 2026-09
     *
     * Pembayaran Oktober:
     * 2026-10
     *
     * Pembayaran November:
     * 2026-11
     *
     * Jadi pembayaran bulan lama TIDAK HILANG.
     * =========================================================
     */

    private function pembayaranPeriode(
        $member,
        $periode
    ) {
        return MemberPayment::where(
            'member_id',
            $member->id
        )
        ->where(
            'periode',
            $periode
        )
        ->first();
    }


    /**
     * =========================================================
     * AMBIL PEMBAYARAN BULAN SEKARANG
     * =========================================================
     */

    private function pembayaranBulanIni(
        $member,
        Request $request = null
    ) {
        $periode =
            $this->periodeSekarang(
                $request
            );

        return $this->pembayaranPeriode(
            $member,
            $periode
        );
    }


    /**
     * =========================================================
     * TERAPKAN STATUS MEMBER SESUAI PERIODE AKTIF
     *
     * PENTING:
     *
     * Method ini TIDAK mengubah database members.
     *
     * Hanya mengubah data yang dikirim ke frontend.
     *
     * Jadi:
     *
     * September lunas
     * Oktober belum lunas
     * September kembali lunas
     *
     * =========================================================
     */

    private function siapkanDataBulanIni(
        $member,
        Request $request = null
    ) {
        /*
         * PERIODE AKTIF
         */

        $periode =
            $this->periodeSekarang(
                $request
            );


        /*
         * CARI PEMBAYARAN
         * SESUAI PERIODE AKTIF
         */

        $payment =
            $this->pembayaranPeriode(
                $member,
                $periode
            );


        /*
         * TANGGAL AWAL PERIODE
         */

        $tanggalMulai =
            Carbon::createFromFormat(
                'Y-m',
                $periode
            )
            ->startOfMonth()
            ->format('Y-m-d');


        /*
         * TANGGAL AKHIR PERIODE
         */

        $tanggalExpired =
            Carbon::createFromFormat(
                'Y-m',
                $periode
            )
            ->endOfMonth()
            ->format('Y-m-d');


        /*
         * =====================================================
         * JIKA SUDAH ADA PEMBAYARAN PADA BULAN TERSEBUT
         * =====================================================
         */

        if ($payment) {

            $member->jumlah_bayar =
                (float) $payment->jumlah_bayar;

            $member->total_harga =
                (float) $payment->total_harga;

            $member->kembalian =
                (float) $payment->kembalian;

            $member->status =
                $payment->status;

            $member->tanggal_bayar =
                $payment->tanggal_bayar;

            $member->tanggal_mulai =
                $tanggalMulai;

            $member->tanggal_expired =
                $tanggalExpired;

        }


        /*
         * =====================================================
         * JIKA BELUM ADA PEMBAYARAN PADA BULAN TERSEBUT
         * =====================================================
         */

        else {

            $member->jumlah_bayar =
                0;

            $member->total_harga =
                self::HARGA_MEMBER;

            $member->kembalian =
                0;

            $member->status =
                'belum lunas';

            $member->tanggal_bayar =
                null;

            $member->tanggal_mulai =
                $tanggalMulai;

            $member->tanggal_expired =
                $tanggalExpired;

        }


        /*
         * SIMPAN PERIODE AKTIF
         * PADA RESPONSE SAJA
         */

        $member->periode =
            $periode;


        /*
         * QR MEMBER
         */

        $member->qr =
            $this->qrMember(
                $member->token
            );


        return $member;
    }


    /**
     * =========================================================
     * GET /api/members
     *
     * MENAMPILKAN SEMUA MEMBER
     * SESUAI PERIODE AKTIF
     * =========================================================
     */

    public function index(
        Request $request
    ) {
        try {

            /*
             * AMBIL SEMUA MEMBER
             */

            $members =
                Member::orderBy(
                    'id',
                    'desc'
                )->get();


            /*
             * TERAPKAN STATUS BERDASARKAN
             * PEMBAYARAN PERIODE AKTIF
             */

            $members->each(
                function ($member) use (
                    $request
                ) {

                    $this->siapkanDataBulanIni(
                        $member,
                        $request
                    );

                }
            );


            /*
             * PERIODE AKTIF
             */

            $periode =
                $this->periodeSekarang(
                    $request
                );


            return response()->json([

                'status' =>
                    true,

                'message' =>
                    'Data member berhasil diambil',

                'periode' =>
                    $periode,

                'data' =>
                    $members

            ], 200);


        } catch (\Exception $e) {

            return response()->json([

                'status' =>
                    false,

                'message' =>
                    'Gagal mengambil data member',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * =========================================================
     * POST /api/members
     *
     * TAMBAH MEMBER
     * =========================================================
     */

    public function store(
        Request $request
    ) {
        try {

            /*
             * VALIDASI
             *
             * HARUS MINIMAL RP150.000
             */

            $request->validate([

                'nama_member' =>
                    'required|string|max:255',

                'nama_perusahaan' =>
                    'nullable|string|max:255',

                'jumlah_bayar' =>
                    'required|numeric|min:150000',

            ], [

                'nama_member.required' =>
                    'Nama member wajib diisi.',

                'jumlah_bayar.required' =>
                    'Uang bayar wajib diisi.',

                'jumlah_bayar.min' =>
                    'Uang bayar minimal Rp150.000.',

            ]);


            /*
             * HARGA
             */

            $totalHarga =
                self::HARGA_MEMBER;


            /*
             * BAYAR
             */

            $jumlahBayar =
                (float) $request->jumlah_bayar;


            /*
             * TIDAK BOLEH LEBIH
             */

            if (
                $jumlahBayar > $totalHarga
            ) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Uang bayar tidak boleh lebih dari Rp150.000.'

                ], 422);
            }


            /*
             * GENERATE KODE MEMBER
             */

            do {

                $kodeMember =
                    'MBR-' .
                    strtoupper(
                        Str::random(6)
                    );

            } while (
                Member::where(
                    'kode_member',
                    $kodeMember
                )->exists()
            );


            /*
             * GENERATE TOKEN
             */

            do {

                $token =
                    'MEMBER-' .
                    strtoupper(
                        Str::random(20)
                    );

            } while (
                Member::where(
                    'token',
                    $token
                )->exists()
            );


            /*
             * PERIODE AKTIF
             */

            $periode =
                $this->periodeSekarang(
                    $request
                );


            /*
             * TANGGAL PERIODE
             */

            $tanggalMulai =
                Carbon::createFromFormat(
                    'Y-m',
                    $periode
                )
                ->startOfMonth()
                ->format('Y-m-d');


            $tanggalExpired =
                Carbon::createFromFormat(
                    'Y-m',
                    $periode
                )
                ->endOfMonth()
                ->format('Y-m-d');


            /*
             * MEMBER BARU
             * SUDAH LUNAS KARENA BAYAR >= 150000
             */

            $status =
                'lunas';


            /*
             * KEMBALIAN
             */

            $kembalian =
                max(
                    0,
                    $jumlahBayar -
                    $totalHarga
                );


            /*
             * TRANSACTION
             */

            $member =
                DB::transaction(
                    function () use (
                        $request,
                        $kodeMember,
                        $token,
                        $totalHarga,
                        $jumlahBayar,
                        $kembalian,
                        $status,
                        $tanggalMulai,
                        $tanggalExpired,
                        $periode
                    ) {

                        /*
                         * SIMPAN MEMBER
                         */

                        $member =
                            Member::create([

                                'kode_member' =>
                                    $kodeMember,

                                'token' =>
                                    $token,

                                'nama_member' =>
                                    trim(
                                        $request->nama_member
                                    ),

                                'nama_perusahaan' =>
                                    $request->nama_perusahaan
                                        ? trim(
                                            $request->nama_perusahaan
                                        )
                                        : null,

                                'total_harga' =>
                                    $totalHarga,

                                'jumlah_bayar' =>
                                    $jumlahBayar,

                                'kembalian' =>
                                    $kembalian,

                                'status' =>
                                    $status,

                                'tanggal_bayar' =>
                                    now(),

                                'tanggal_mulai' =>
                                    $tanggalMulai,

                                'tanggal_expired' =>
                                    $tanggalExpired,

                                'sedang_parkir' =>
                                    false,

                            ]);


                        /*
                         * SIMPAN RIWAYAT PEMBAYARAN
                         *
                         * SEPTEMBER:
                         * 2026-09
                         *
                         * OKTOBER:
                         * 2026-10
                         *
                         * DAN SETERUSNYA.
                         */

                        MemberPayment::updateOrCreate(

                            [

                                'member_id' =>
                                    $member->id,

                                'periode' =>
                                    $periode,

                            ],

                            [

                                'jumlah_bayar' =>
                                    $jumlahBayar,

                                'total_harga' =>
                                    $totalHarga,

                                'kembalian' =>
                                    $kembalian,

                                'status' =>
                                    $status,

                                'tanggal_bayar' =>
                                    now(),

                            ]

                        );


                        return $member;
                    }
                );


            /*
             * QR
             */

            $member->qr =
                $this->qrMember(
                    $member->token
                );


            return response()->json([

                'status' =>
                    true,

                'message' =>
                    'Member berhasil ditambahkan',

                'periode' =>
                    $periode,

                'data' =>
                    $member

            ], 201);


        } catch (
            \Illuminate\Validation\ValidationException $e
        ) {

            throw $e;


        } catch (\Exception $e) {

            return response()->json([

                'status' =>
                    false,

                'message' =>
                    'Gagal menambahkan member',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * =========================================================
     * GET /api/members/{id}
     *
     * DETAIL MEMBER
     * =========================================================
     */

    public function show(
        Request $request,
        $id
    ) {
        try {

            $member =
                Member::find($id);


            if (!$member) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Member tidak ditemukan'

                ], 404);
            }


            /*
             * STATUS SESUAI PERIODE
             */

            $member =
                $this->siapkanDataBulanIni(
                    $member,
                    $request
                );


            return response()->json([

                'status' =>
                    true,

                'message' =>
                    'Detail member berhasil diambil',

                'periode' =>
                    $member->periode,

                'data' => [

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

                    'total_harga' =>
                        (int) $member->total_harga,

                    'jumlah_bayar' =>
                        (int) $member->jumlah_bayar,

                    'kembalian' =>
                        (int) $member->kembalian,

                    'status' =>
                        $member->status,

                    'tanggal_bayar' =>
                        $member->tanggal_bayar,

                    'tanggal_mulai' =>
                        $member->tanggal_mulai,

                    'tanggal_expired' =>
                        $member->tanggal_expired,

                    'sedang_parkir' =>
                        (bool) $member->sedang_parkir,

                    'periode' =>
                        $member->periode,

                    'qr' =>
                        $member->qr,

                ]

            ], 200);


        } catch (\Exception $e) {

            return response()->json([

                'status' =>
                    false,

                'message' =>
                    'Gagal mengambil detail member',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * =========================================================
     * PUT /api/members/{id}
     *
     * UPDATE DATA MEMBER
     * =========================================================
     */

    public function update(
        Request $request,
        $id
    ) {
        try {

            $member =
                Member::find($id);


            if (!$member) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Member tidak ditemukan'

                ], 404);
            }


            /*
             * CEK PEMBAYARAN PERIODE AKTIF
             */

            $periode =
                $this->periodeSekarang(
                    $request
                );


            $payment =
                $this->pembayaranPeriode(
                    $member,
                    $periode
                );


            /*
             * YANG LUNAS PADA BULAN AKTIF
             * TIDAK BOLEH DIEDIT
             */

            if (
                $payment &&
                strtolower(
                    trim(
                        $payment->status
                    )
                ) === 'lunas'
            ) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Member yang sudah lunas pada bulan ini tidak dapat diedit.'

                ], 403);
            }


            /*
             * VALIDASI
             */

            $request->validate([

                'nama_member' =>
                    'required|string|max:255',

                'nama_perusahaan' =>
                    'nullable|string|max:255',

            ]);


            /*
             * UPDATE
             */

            $member->nama_member =
                trim(
                    $request->nama_member
                );

            $member->nama_perusahaan =
                $request->nama_perusahaan
                    ? trim(
                        $request->nama_perusahaan
                    )
                    : null;


            $member->total_harga =
                self::HARGA_MEMBER;


            $member->save();


            /*
             * QR
             */

            $member->qr =
                $this->qrMember(
                    $member->token
                );


            return response()->json([

                'status' =>
                    true,

                'message' =>
                    'Member berhasil diperbarui',

                'data' =>
                    $member

            ], 200);


        } catch (
            \Illuminate\Validation\ValidationException $e
        ) {

            throw $e;


        } catch (\Exception $e) {

            return response()->json([

                'status' =>
                    false,

                'message' =>
                    'Gagal memperbarui member',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * =========================================================
     * DELETE /api/members/{id}
     *
     * HAPUS MEMBER
     * =========================================================
     */

    public function destroy(
        Request $request,
        $id
    ) {
        try {

            $member =
                Member::find($id);


            if (!$member) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Member tidak ditemukan'

                ], 404);
            }


            /*
             * PERIODE AKTIF
             */

            $periode =
                $this->periodeSekarang(
                    $request
                );


            /*
             * CEK PEMBAYARAN
             */

            $payment =
                $this->pembayaranPeriode(
                    $member,
                    $periode
                );


            /*
             * LUNAS BULAN AKTIF
             * TIDAK BOLEH DIHAPUS
             */

            if (
                $payment &&
                strtolower(
                    trim(
                        $payment->status
                    )
                ) === 'lunas'
            ) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Member yang sudah lunas pada bulan ini tidak dapat dihapus.'

                ], 403);
            }


            /*
             * HAPUS MEMBER
             */

            $member->delete();


            return response()->json([

                'status' =>
                    true,

                'message' =>
                    'Member berhasil dihapus'

            ], 200);


        } catch (\Exception $e) {

            return response()->json([

                'status' =>
                    false,

                'message' =>
                    'Gagal menghapus member',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * =========================================================
     * PUT /api/members/{id}/pembayaran
     *
     * PEMBAYARAN MEMBER BULANAN
     * =========================================================
     */

    public function pembayaran(
        Request $request,
        $id
    ) {
        try {

            $member =
                Member::find($id);


            if (!$member) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Member tidak ditemukan'

                ], 404);
            }


            /*
             * VALIDASI
             */

            $request->validate([

                'jumlah_bayar' =>
                    'required|numeric|min:150000'

            ], [

                'jumlah_bayar.required' =>
                    'Jumlah pembayaran wajib diisi.',

                'jumlah_bayar.min' =>
                    'Pembayaran minimal Rp150.000.'

            ]);


            /*
             * HARGA
             */

            $totalHarga =
                self::HARGA_MEMBER;


            /*
             * JUMLAH BAYAR
             */

            $jumlahBayar =
                (float) $request->jumlah_bayar;


            /*
             * TIDAK BOLEH LEBIH
             */

            if (
                $jumlahBayar > $totalHarga
            ) {

                return response()->json([

                    'status' =>
                        false,

                    'message' =>
                        'Jumlah pembayaran tidak boleh lebih dari Rp150.000.'

                ], 422);
            }


            /*
             * PERIODE PEMBAYARAN
             *
             * Normal:
             * bulan sekarang.
             *
             * Bisa dites:
             * ?periode=2026-09
             */

            $periode =
                $this->periodeSekarang(
                    $request
                );


            /*
             * STATUS
             */

            $status =
                $jumlahBayar >= $totalHarga
                    ? 'lunas'
                    : 'belum lunas';


            /*
             * KEMBALIAN
             */

            $kembalian =
                max(
                    0,
                    $jumlahBayar -
                    $totalHarga
                );


            /*
             * TANGGAL PERIODE
             */

            $tanggalMulai =
                Carbon::createFromFormat(
                    'Y-m',
                    $periode
                )
                ->startOfMonth()
                ->format('Y-m-d');


            $tanggalExpired =
                Carbon::createFromFormat(
                    'Y-m',
                    $periode
                )
                ->endOfMonth()
                ->format('Y-m-d');


            /*
             * SIMPAN PEMBAYARAN
             */

            $payment =
                DB::transaction(
                    function () use (
                        $member,
                        $periode,
                        $jumlahBayar,
                        $totalHarga,
                        $kembalian,
                        $status,
                        $tanggalMulai,
                        $tanggalExpired
                    ) {

                        /*
                         * UPDATE / CREATE RIWAYAT
                         *
                         * SATU DATA UNTUK
                         * SATU MEMBER + SATU PERIODE.
                         */

                        $payment =
                            MemberPayment::updateOrCreate(

                                [

                                    'member_id' =>
                                        $member->id,

                                    'periode' =>
                                        $periode,

                                ],

                                [

                                    'jumlah_bayar' =>
                                        $jumlahBayar,

                                    'total_harga' =>
                                        $totalHarga,

                                    'kembalian' =>
                                        $kembalian,

                                    'status' =>
                                        $status,

                                    'tanggal_bayar' =>
                                        now(),

                                ]

                            );


                        /*
                         * SNAPSHOT MEMBER
                         *
                         * Ini boleh diperbarui.
                         *
                         * Status yang ditampilkan
                         * nantinya tetap membaca
                         * MemberPayment.
                         */

                        $member->jumlah_bayar =
                            $jumlahBayar;

                        $member->total_harga =
                            $totalHarga;

                        $member->kembalian =
                            $kembalian;

                        $member->status =
                            $status;

                        $member->tanggal_bayar =
                            now();

                        $member->tanggal_mulai =
                            $tanggalMulai;

                        $member->tanggal_expired =
                            $tanggalExpired;

                        $member->save();


                        return $payment;
                    }
                );


            /*
             * QR
             */

            $member->qr =
                $this->qrMember(
                    $member->token
                );


            /*
             * NAMA BULAN
             */

            $namaBulan =
                Carbon::createFromFormat(
                    'Y-m',
                    $periode
                )
                ->translatedFormat(
                    'F Y'
                );


            return response()->json([

                'status' =>
                    true,

                'message' =>
                    'Pembayaran bulan '
                    . $namaBulan
                    . ' berhasil disimpan.',

                'periode' =>
                    $periode,

                'data' => [

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

                    'total_harga' =>
                        (int) $totalHarga,

                    'jumlah_bayar' =>
                        (int) $jumlahBayar,

                    'kembalian' =>
                        (int) $kembalian,

                    'status' =>
                        $status,

                    'periode' =>
                        $periode,

                    'tanggal_bayar' =>
                        $payment->tanggal_bayar,

                    'tanggal_mulai' =>
                        $tanggalMulai,

                    'tanggal_expired' =>
                        $tanggalExpired,

                ]

            ], 200);


        } catch (
            \Illuminate\Validation\ValidationException $e
        ) {

            throw $e;


        } catch (\Exception $e) {

            return response()->json([

                'status' =>
                    false,

                'message' =>
                    'Gagal melakukan pembayaran',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * =========================================================
     * ALIAS UPDATE PEMBAYARAN
     * =========================================================
     */

    public function updatePembayaran(
        Request $request,
        $id
    ) {
        return $this->pembayaran(
            $request,
            $id
        );
    }
}