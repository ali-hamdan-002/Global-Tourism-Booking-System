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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance',10,2)->default(0.00);
            $table->double('points');
          //  $table->string('level');
            $table->enum('level',['Bronze','Silver','Gold','Platinum'])->default('Bronze');
            $table->Integer('amount_spent')->default(0);
            $table->string('wallet_code',255)->nullable();
            $table->unsignedBigInteger('walletable_id');
            $table->string('walletable_type');

            $table->timestamps();
            $table->index(['walletable_id','walletable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
