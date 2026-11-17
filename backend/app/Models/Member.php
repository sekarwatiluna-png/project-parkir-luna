<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    /**
     * =========================================================
     * TABLE
     * =========================================================
     */

    protected $table = 'members';


    /**
     * =========================================================
     * FILLABLE
     * =========================================================
     */

    protected $fillable = [

        'kode_member',

        'token',

        'nama_member',

        'nama_perusahaan',

        'total_harga',

        'jumlah_bayar',

        'kembalian',

        'status',

        'tanggal_bayar',

        'tanggal_mulai',

        'tanggal_expired',

        'sedang_parkir',

    ];


    /**
     * =========================================================
     * CAST
     * =========================================================
     */

    protected $casts = [

        'total_harga' => 'float',

        'jumlah_bayar' => 'float',

        'kembalian' => 'float',

        'tanggal_bayar' => 'datetime',

        'tanggal_mulai' => 'date',

        'tanggal_expired' => 'date',

        'sedang_parkir' => 'boolean',

    ];


    /**
     * =========================================================
     * RIWAYAT PEMBAYARAN BULANAN
     *
     * SATU MEMBER BISA MEMPUNYAI BANYAK PEMBAYARAN.
     *
     * Contoh:
     *
     * member_id 1
     * 2026-09 = lunas
     * 2026-10 = belum lunas
     * 2026-11 = lunas
     *
     * =========================================================
     */

    public function payments(): HasMany
    {
        return $this->hasMany(
            MemberPayment::class
        );
    }
}