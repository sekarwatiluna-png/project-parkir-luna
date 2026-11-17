<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {

            if (!Schema::hasColumn('members', 'jumlah_bayar')) {
                $table->integer('jumlah_bayar')
                    ->default(0)
                    ->after('total_harga');
            }

            if (!Schema::hasColumn('members', 'tanggal_bayar')) {
                $table->dateTime('tanggal_bayar')
                    ->nullable()
                    ->after('jumlah_bayar');
            }

        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {

            if (Schema::hasColumn('members', 'tanggal_bayar')) {
                $table->dropColumn('tanggal_bayar');
            }

            if (Schema::hasColumn('members', 'jumlah_bayar')) {
                $table->dropColumn('jumlah_bayar');
            }

        });
    }
};