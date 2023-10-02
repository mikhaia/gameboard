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
        Schema::table('users', function (Blueprint $table) {
            // TODO: check after() in MariaDB
            $table->text('avatar')->default('/data/avatar/avatar.jpg')->after('email');
            $table->string('steam_id', 50)->nullable()->after('email');
            $table->string('google_id', 50)->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('steam_id');
            $table->dropColumn('google_id');
        });
    }
};
