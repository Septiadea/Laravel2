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
        Schema::create('videos', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('url', 512);
            $table->string('duration', 20);
            $table->string('category', 50);
            $table->string('thumbnail_url', 512)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('views')->default(0);
            $table->integer('kader_id')->nullable();
            $table->dateTime('saved_at')->nullable();

            // Jika ingin menambahkan foreign key (opsional)
            // $table->foreign('kader_id')->references('id')->on('kader')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
