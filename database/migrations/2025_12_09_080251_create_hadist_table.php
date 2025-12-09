<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hadist', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('jenisId')->unsigned()->index();
            $table->integer('no'); // nomor hadist

            $table->string('judul', 191);
            $table->text('arab');
            $table->text('indo');

            $table->dateTime('createdAt', 3)
                  ->default(DB::raw('CURRENT_TIMESTAMP(3)'));

            $table->foreign('jenisId')
                  ->references('id')
                  ->on('jenishadist')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hadist');
    }
};
