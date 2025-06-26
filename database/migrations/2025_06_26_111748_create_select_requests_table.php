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
        Schema::create('select_requests', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->integer('context_id');
            $table->json('errors');
            $table->json('request');
            $table->json('response');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('select_requests');
    }
};
