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
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('column_id');
            $table->string('cover')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('position')->default(0);
            $table->tinyInteger('progress')->default(0);
            $table->tinyInteger('calculation')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
