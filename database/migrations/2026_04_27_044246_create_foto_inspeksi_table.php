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

            $table->foreignId('sub_uraian_id')
                ->nullable()
                ->constrained('sub_uraian')
                ->onDelete('cascade');

            $table->string('path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_inspeksi');
    }
};