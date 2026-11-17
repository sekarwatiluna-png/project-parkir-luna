<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * =========================================================
     * CREATE TABLE MEMBER PAYMENTS
     * =========================================================
     */
    public function up(): void
    {
        Schema::create('member_payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnDelete();

            /*
             * CONTOH:
             * 2026-09
             * 2026-10
             * 2026-11
             */
            $table->string('periode', 7);

            /*
             * JUMLAH PEMBAYARAN BULAN TERSEBUT
             */
            $table->decimal(
                'jumlah_bayar',
                12,
                2
            )->default(0);

            /*
             * TOTAL TAGIHAN
             */
            $table->decimal(
                'total_harga',
                12,
                2
            )->default(150000);

            /*
             * KEMBALIAN
             */
            $table->decimal(
                'kembalian',
                12,
                2
            )->default(0);

            /*
             * STATUS:
             * lunas
             * belum lunas
             */
            $table->string('status')
                ->default('belum lunas');

            /*
             * TANGGAL PEMBAYARAN
             */
            $table->dateTime(
                'tanggal_bayar'
            )->nullable();

            $table->timestamps();

            /*
             * SATU MEMBER HANYA BOLEH
             * MEMILIKI SATU DATA UNTUK
             * SATU BULAN
             */
            $table->unique([
                'member_id',
                'periode'
            ]);

        });
    }


    /**
     * =========================================================
     * DROP TABLE
     * =========================================================
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'member_payments'
        );
    }
};