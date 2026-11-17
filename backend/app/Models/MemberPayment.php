<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberPayment extends Model
{
    /**
     * =========================================================
     * TABLE
     * =========================================================
     */

    protected $table =
        'member_payments';


    /**
     * =========================================================
     * FILLABLE
     * =========================================================
     */

    protected $fillable = [

        'member_id',

        'periode',

        'jumlah_bayar',

        'total_harga',

        'kembalian',

        'status',

        'tanggal_bayar',

    ];


    /**
     * =========================================================
     * CAST
     * =========================================================
     */

    protected $casts = [

        'jumlah_bayar' => 'float',

        'total_harga' => 'float',

        'kembalian' => 'float',

        'tanggal_bayar' => 'datetime',

    ];


    /**
     * =========================================================
     * RELATIONSHIP
     * =========================================================
     */

    public function member(): BelongsTo
    {
        return $this->belongsTo(
            Member::class
        );
    }
}