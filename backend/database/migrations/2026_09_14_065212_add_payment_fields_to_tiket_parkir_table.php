<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_parkir', function (Blueprint $table) {

            if (!Schema::hasColumn('tiket_parkir', 'plat_nomor')) {
                $table->string('plat_nomor', 20)->nullable();
            }

            if (!Schema::hasColumn('tiket_parkir', 'biaya')) {
                $table->decimal('biaya', 12, 2)->nullable()->default(0);
            }

            if (!Schema::hasColumn('tiket_parkir', 'status_bayar')) {
                $table->enum('status_bayar', [
                    'belum_bayar',
                    'sudah_bayar'
                ])->default('belum_bayar');
            }

            if (!Schema::hasColumn('tiket_parkir', 'waktu_bayar')) {
                $table->dateTime('waktu_bayar')->nullable();
            }

            if (!Schema::hasColumn('tiket_parkir', 'jumlah_bayar')) {
                $table->decimal('jumlah_bayar', 12, 2)->nullable()->default(0);
            }

            if (!Schema::hasColumn('tiket_parkir', 'kembalian')) {
                $table->decimal('kembalian', 12, 2)->nullable()->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('tiket_parkir', function (Blueprint $table) {

            $columns = [
                'plat_nomor',
                'biaya',
                'status_bayar',
                'waktu_bayar',
                'jumlah_bayar',
                'kembalian'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tiket_parkir', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};