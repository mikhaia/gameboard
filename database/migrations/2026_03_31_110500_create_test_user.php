<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existingUser = DB::table('users')
            ->where('email', 'test@example.com')
            ->first();

        if ($existingUser) {
            return;
        }

        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'avatar' => '',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('email', 'test@example.com')
            ->delete();
    }
};
