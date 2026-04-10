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
        Schema::create('trip_stations', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->time('stations_strat_houre');
            $table->time('stations_end_houre');
            $table->string('transportation')->nullable();
            $table->string('hotel')->nullable();
            $table->string('restaurant')->nullable();
      //      $table->string('image')->nullable();
            $table->foreignId('tourist_trip_id')->references('id')->on('tourist_trips')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_stations');
    }
};
