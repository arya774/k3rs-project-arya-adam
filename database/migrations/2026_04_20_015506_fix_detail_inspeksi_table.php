<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_inspeksi', function (Blueprint $table) {

            // 🔥 HAPUS uraian_id (kalau ada)
            if (Schema::hasColumn('detail_inspeksi', 'uraian_id')) {
                $table->dropForeign(['uraian_id']);
                $table->dropColumn('uraian_id');
            }

            // 🔥 TAMBAH sub_uraian_id
            if (!Schema::hasColumn('detail_inspeksi', 'sub_uraian_id')) {
                $table->foreignId('sub_uraian_id')
                      ->after('inspeksi_id')
                      ->constrained('sub_uraian')
                      ->cascadeOnDelete();
            }

            // 🔥 TAMBAH catatan
            if (!Schema::hasColumn('detail_inspeksi', 'catatan')) {
                $table->text('catatan')->nullable()->after('nilai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_inspeksi', function (Blueprint $table) {

            if (Schema::hasColumn('detail_inspeksi', 'sub_uraian_id')) {
                $table->dropForeign(['sub_uraian_id']);
                $table->dropColumn('sub_uraian_id');
            }

            if (Schema::hasColumn('detail_inspeksi', 'catatan')) {
                $table->dropColumn('catatan');
            }

            // balikin lagi uraian_id kalau rollback
            if (!Schema::hasColumn('detail_inspeksi', 'uraian_id')) {
                $table->foreignId('uraian_id')->constrained('uraian')->cascadeOnDelete();
            }
        });
    }
};