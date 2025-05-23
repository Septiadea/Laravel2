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
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id(); // id utama, int(11) auto increment
            $table->integer('kader_id'); // int(11)
            $table->string('warga_nik', 20); // varchar(20)
            $table->string('nama_warga', 100); // varchar(100)
            $table->date('tanggal'); // date
            $table->string('keterangan', 100); // varchar(100)
            $table->text('deskripsi')->nullable(); // text, nullable
            $table->string('bukti_foto', 255)->nullable(); // varchar(255), nullable
            $table->string('status', 50)->default('Selesai'); // varchar(50), default 'Selesai'
            $table->timestamps(); // timestamp, default current timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
    }
};
