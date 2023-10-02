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
        Schema::create('boards', function (Blueprint $table) {
            $table->ulid('id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('background')->nullable();
            $table->tinyInteger('position')->default(0);
            $table->boolean('public')->default(false);
            $table->boolean('dark')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
