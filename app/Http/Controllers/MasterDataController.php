<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Uraian;
use App\Models\SubUraian;

class MasterDataController extends Controller
{
    // =========================
    // HALAMAN UTAMA
    // =========================
    public function index()
    {
        $kategori = Kategori::all();
        $uraian = Uraian::all();
        $subUraian = SubUraian::all();

        return view('master-data', compact('kategori', 'uraian', 'subUraian'));
    }

    // =========================
    // AJAX: GET URAIAN BY KATEGORI
    // =========================
    public function getUraian($id)
    {
        $uraian = Uraian::where('kategori_id', $id)
                        ->orderBy('nama')
                        ->get();

        return response()->json($uraian);
    }

    // =========================
    // SIMPAN KATEGORI
    // =========================
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        Kategori::create([
            'nama' => $request->nama
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    // =========================
    // SIMPAN URAIAN
    // =========================
    public function storeUraian(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255'
        ]);

        Uraian::create([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama
        ]);

        return back()->with('success', 'Uraian berhasil ditambahkan');
    }

    // =========================
    // SIMPAN SUB URAIAN
    // =========================
    public function storeSubUraian(Request $request)
    {
        $request->validate([
            'uraian_id' => 'required|exists:uraians,id',
            'nama' => 'required|string|max:255'
        ]);

        SubUraian::create([
            'uraian_id' => $request->uraian_id,
            'nama' => $request->nama
        ]);

        return back()->with('success', 'Sub uraian berhasil ditambahkan');
    }
}
