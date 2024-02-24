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
        Schema::create('player_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('ext_id');
            $table->string('playerid')->unique();
            $table->string('agentcode');
            $table->string('password');
            $table->string('before_balance');
            $table->string('balance');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_accounts');
    }
};
