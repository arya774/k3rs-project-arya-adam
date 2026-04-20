<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_inspeksi', function (Blueprint $table) {

            if (Schema::hasColumn('detail_inspeksi', 'uraian_id')) {
                $table->dropForeign(['uraian_id']);
                $table->dropColumn('uraian_id');
            }

            $table->foreignId('sub_uraian_id')
                  ->constrained('sub_uraian')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_inspeksi', function (Blueprint $table) {

            $table->dropForeign(['sub_uraian_id']);
            $table->dropColumn('sub_uraian_id');

            $table->foreignId('uraian_id')
                  ->constrained('uraian')
                  ->cascadeOnDelete();
        });
    }
};