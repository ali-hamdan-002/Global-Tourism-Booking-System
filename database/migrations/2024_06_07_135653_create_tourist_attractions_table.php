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
        Schema::create('tourist_attractions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->enum('category',['aa','qq']);
            $table->string('location');
            $table->time('open_hours');
            $table->time('finish_hours');
            $table->string('active')->nullable();
            $table->string('additonal_info')->nullable();
            $table->foreignId('companie_id')->references('id')->on('companies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_attractions');
    }
};
