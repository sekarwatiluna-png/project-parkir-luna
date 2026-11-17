<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tiketparkir', 'member_id')) {

            Schema::table('tiketparkir', function (Blueprint $table) {
                $table->unsignedBigInteger('member_id')
                    ->nullable()
                    ->after('token');

                $table->index('member_id');
            });
        }
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tiketparkir', 'member_id')) {

            Schema::table('tiketparkir', function (Blueprint $table) {
                $table->dropIndex(['member_id']);
                $table->dropColumn('member_id');
            });
        }
    }
};