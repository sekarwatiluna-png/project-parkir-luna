<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket_parkir', function (Blueprint $table) {
            $table->id();

            $table->string('kode_tiket', 50)->unique();

            $table->string('jenis_kendaraan', 50)->nullable();

            $table->text('qr_code')->nullable();

            $table->enum('status', ['masuk', 'keluar'])
                  ->default('masuk');

            $table->dateTime('waktu_masuk')->nullable();

            $table->dateTime('waktu_keluar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_parkir');
    }
};