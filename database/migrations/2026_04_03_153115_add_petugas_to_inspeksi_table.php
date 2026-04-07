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
        Schema::table('inspeksi', function (Blueprint $table) {

            // PETUGAS K3RS
            $table->string('nama_petugas_k3rs')->nullable()->after('ruangan');
            $table->string('paraf_petugas_k3rs')->nullable()->after('nama_petugas_k3rs');

            // PETUGAS RUANGAN
            $table->string('nama_petugas_ruangan')->nullable()->after('paraf_petugas_k3rs');
            $table->string('paraf_petugas_ruangan')->nullable()->after('nama_petugas_ruangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi', function (Blueprint $table) {
            $table->dropColumn([
                'nama_petugas_k3rs',
                'paraf_petugas_k3rs',
                'nama_petugas_ruangan',
                'paraf_petugas_ruangan'
            ]);
        });
    }
};