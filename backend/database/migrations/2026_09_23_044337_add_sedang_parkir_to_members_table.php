<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('members', 'sedang_parkir')) {
            Schema::table('members', function (Blueprint $table) {
                $table->boolean('sedang_parkir')
                    ->default(false)
                    ->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('members', 'sedang_parkir')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('sedang_parkir');
            });
        }
    }
};