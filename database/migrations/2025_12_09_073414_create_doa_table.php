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
        Schema::create('doa', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('sumber', 50)->index();
            $table->string('judul', 255);

            $table->longText('arab')->nullable();
            $table->longText('indo')->nullable();

            $table->timestamp('createdAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doa');
    }
};
