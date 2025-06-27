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
        Schema::dropIfExists('apis');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('apis', function (Blueprint $table) {
            $table->string('name')->primary();
            $table->string('url');
            $table->string('user');
            $table->string('password');
            $table->integer('agency');
        });
    }
};
