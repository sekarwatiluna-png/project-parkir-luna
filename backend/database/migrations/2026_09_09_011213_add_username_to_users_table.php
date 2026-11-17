<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat kolom username terlebih dahulu
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 100)->nullable();
            });
        }

        // 2. Isi username untuk user lama
        $users = DB::table('users')
            ->whereNull('username')
            ->get();

        foreach ($users as $user) {

            // Buat username dari email
            $username = null;

            if (!empty($user->email)) {
                $username = explode('@', $user->email)[0];
            }

            // Kalau email kosong
            if (empty($username)) {
                $username = 'user' . $user->id;
            }

            // Pastikan username tidak kosong
            if (empty(trim($username))) {
                $username = 'user' . $user->id;
            }

            // Pastikan username unik
            $originalUsername = $username;
            $counter = 1;

            while (
                DB::table('users')
                    ->where('username', $username)
                    ->exists()
            ) {
                $username = $originalUsername . $counter;
                $counter++;
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'username' => $username
                ]);
        }

        // 3. Jadikan username UNIQUE
        $database = DB::getDatabaseName();

        $indexExists = DB::select(
            "SELECT COUNT(*) as total
             FROM information_schema.statistics
             WHERE table_schema = ?
             AND table_name = 'users'
             AND index_name = 'users_username_unique'",
            [$database]
        );

        if ($indexExists[0]->total == 0) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username', 'users_username_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {

            $database = DB::getDatabaseName();

            $indexExists = DB::select(
                "SELECT COUNT(*) as total
                 FROM information_schema.statistics
                 WHERE table_schema = ?
                 AND table_name = 'users'
                 AND index_name = 'users_username_unique'",
                [$database]
            );

            if ($indexExists[0]->total > 0) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropUnique('users_username_unique');
                });
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
    }
};