<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ CEK DULU sebelum tambah kolom
        if (!Schema::hasColumn('detail_inspeksi', 'catatan')) {
            Schema::table('detail_inspeksi', function (Blueprint $table) {
                $table->text('catatan')->nullable()->after('nilai');
            });
        }
    }

    public function down(): void
    {
        // ✅ CEK DULU sebelum hapus kolom
        if (Schema::hasColumn('detail_inspeksi', 'catatan')) {
            Schema::table('detail_inspeksi', function (Blueprint $table) {
                $table->dropColumn('catatan');
            });
        }
    }
};