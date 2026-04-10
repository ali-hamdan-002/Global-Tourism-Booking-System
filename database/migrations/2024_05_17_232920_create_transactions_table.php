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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['desposit','withdrawal','transfer','putchase']);
            $table->decimal('amount',10,2);
            $table->string('description')->nullable();
            $table->enum('stautes',['pending','completed','failed'])->default('pending');
            $table->integer('reference_number')->unique()->nullable();
            $table->foreignId('wallet_id')->references('id')->on('wallets')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
