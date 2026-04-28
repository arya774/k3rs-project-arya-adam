<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_inspeksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspeksi_id')
                ->constrained('inspeksi')
                ->onDelete('cascade');

            $table->string('path');
            $table->string('nama_file')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable();
            $table->string('disk')->default('public');
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_inspeksi');
    }
};