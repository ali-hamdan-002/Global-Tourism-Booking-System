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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('location')->nullable();
            $table->string('annual_income')->nullable();
            $table->enum('gender' ,['male','female'])->nullable();
            $table->string('country')->nullable();
         //   $table->string('image')->nullable();
            $table->date('birth')->nullable();
            $table->enum('status',['active','banned','block'])->default('active');
          //  $table->foreignId('wallet_id')->references('id')->on('wallets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
