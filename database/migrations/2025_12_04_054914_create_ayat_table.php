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
        Schema::create('ayat', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('surah_id');
            $table->foreign('surah_id')->references('id')->on('surah')->onDelete('cascade');
            $table->integer('nomor');
            $table->text('ar');
            $table->text('tr');
            $table->text('idn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayat');
    }
};
