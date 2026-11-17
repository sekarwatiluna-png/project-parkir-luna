<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tiketparkir')) {

            Schema::create('tiketparkir', function (Blueprint $table) {
                $table->id();

                $table->string('kode_tiket')->unique();

                $table->uuid('token')->unique();

                $table->string('plat_nomor', 20);

                $table->string('jenis_kendaraan', 50);

                $table->dateTime('waktu_masuk');

                $table->dateTime('waktu_keluar')->nullable();

                $table->decimal('biaya', 12, 2)->default(0);

                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tiketparkir');
    }
};