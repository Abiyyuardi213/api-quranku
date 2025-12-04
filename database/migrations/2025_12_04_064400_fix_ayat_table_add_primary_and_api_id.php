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
        Schema::table('ayat', function (Blueprint $table) {

            // Jadikan kolom id sebagai primary key
            $table->primary('id');

            // Tambah kolom ayat_id_api
            $table->bigInteger('ayat_id_api')->unique()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ayat', function (Blueprint $table) {

            // Hapus primary key
            $table->dropPrimary(['id']);

            // Hapus kolom ayat_id_api
            $table->dropColumn('ayat_id_api');
        });
    }
};
