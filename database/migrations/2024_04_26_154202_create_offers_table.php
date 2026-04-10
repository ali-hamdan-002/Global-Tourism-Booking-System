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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->date('trip_date');
            $table->time('start_hour');
            $table->string('source');
            $table->string('destination');
            $table->enum('status',['pending','accept','refuse'])->default('pending');
            $table->enum('evalution',['1','2','3','4','5'])->default('1');
            $table->foreignId('companie_id')->references('id')->on('companies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
