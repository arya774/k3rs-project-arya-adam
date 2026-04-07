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
        Schema::create('detail_inspeksi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inspeksi_id')->constrained('inspeksi')->cascadeOnDelete();
    $table->foreignId('uraian_id')->constrained('uraian')->cascadeOnDelete();
    $table->enum('nilai', ['ya','tidak']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_inspeksis');
    }
};
