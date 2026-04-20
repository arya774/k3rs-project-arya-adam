<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Uraian;
use App\Models\SubUraian;

class K3rsSeeder extends Seeder
{
    public function run(): void
    {
        // ======================
        // KATEGORI APD
        // ======================
        $apd = Kategori::create([
            'nama_kategori' => 'APD'
        ]);

        // Uraian Helm
        $helm = Uraian::create([
            'kategori_id' => $apd->id,
            'nama_uraian' => 'Helm'
        ]);

        SubUraian::create([
            'uraian_id' => $helm->id,
            'nama_sub_uraian' => 'Helm Safety'
        ]);

        SubUraian::create([
            'uraian_id' => $helm->id,
            'nama_sub_uraian' => 'Helm Tidak Retak'
        ]);

        // Uraian Masker
        $masker = Uraian::create([
            'kategori_id' => $apd->id,
            'nama_uraian' => 'Masker'
        ]);

        SubUraian::create([
            'uraian_id' => $masker->id,
            'nama_sub_uraian' => 'Masker Medis'
        ]);

        SubUraian::create([
            'uraian_id' => $masker->id,
            'nama_sub_uraian' => 'Masker Dipakai'
        ]);

        // ======================
        // KATEGORI KEAMANAN
        // ======================
        $keamanan = Kategori::create([
            'nama_kategori' => 'Keamanan'
        ]);

        $lantai = Uraian::create([
            'kategori_id' => $keamanan->id,
            'nama_uraian' => 'Lantai'
        ]);

        SubUraian::create([
            'uraian_id' => $lantai->id,
            'nama_sub_uraian' => 'Tidak Licin'
        ]);

        SubUraian::create([
            'uraian_id' => $lantai->id,
            'nama_sub_uraian' => 'Tidak Ada Air'
        ]);
    }
}