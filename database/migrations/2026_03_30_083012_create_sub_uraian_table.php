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
       Schema::create('sub_uraian', function (Blueprint $table) {
    $table->id();
    $table->foreignId('uraian_id')->constrained('uraian')->cascadeOnDelete();
    $table->string('nama_sub_uraian');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_uraian');
    }
};
