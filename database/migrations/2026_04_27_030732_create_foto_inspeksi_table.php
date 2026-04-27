<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspeksi', function (Blueprint $table) {

            // hapus dulu kolom lama
            $table->dropColumn('paraf_petugas_k3rs');
            $table->dropColumn('paraf_petugas_ruangan');
        });

        Schema::table('inspeksi', function (Blueprint $table) {

            // buat ulang dengan LONGTEXT
            $table->longText('paraf_petugas_k3rs')->nullable();
            $table->longText('paraf_petugas_ruangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('inspeksi', function (Blueprint $table) {
            $table->dropColumn('paraf_petugas_k3rs');
            $table->dropColumn('paraf_petugas_ruangan');
        });
    }
};