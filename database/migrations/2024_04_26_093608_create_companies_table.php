<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
     $table->id();
     $table->string('name');
     $table->string('location');
     $table->text('descr');
     $table->string('phone');
     $table->enum('evaluation',['0','1','2','3','4','5'])->nullable()->default('0');
     $table->enum('service_type',['tourism_trips','flights','tourist_attraction']);
  //   $table->string('image')->nullable();
     $table->enum('status',['pending','active','refuse'])->default('pending');
     $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete()->cascadeOnUpdate();
    // $table->foreignId('wallet_id')->references('id')->on('wallets')->cascadeOnDelete()->cascadeOnUpdate();
     $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
