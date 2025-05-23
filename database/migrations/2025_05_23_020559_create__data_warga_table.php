<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataWargaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_warga', function (Blueprint $table) {
            $table->string('Wilayah', 227);
            $table->string('NIK', 16);
            $table->string('Nama', 100);
            $table->string('Status', 50)->nullable()->default(null);
            
            // Jika ingin menjadikan NIK sebagai primary key misalnya:
            // $table->primary('NIK');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_warga');
    }
}
