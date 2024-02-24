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
        Schema::create('apis_seamles', function (Blueprint $table) {
            $table->id();
            $table->string('apikey')->nullable();
            $table->string('secretkey')->nullable();
            $table->string('agentcode')->nullable();
            $table->string('apiurl')->nullable();
            $table->string('addons_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis_seamles');
    }
};
