<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Uraian;
use App\Models\Kategori; // ✅ WAJIB

class UraianController extends Controller
{
    // ✅ TAMPIL DATA
    public function index()
{
    $uraians = Uraian::with('kategori')->get();
    $kategoris = Kategori::all();

    return view('uraianindex', compact('uraians', 'kategoris'));
}
    // ✅ SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_uraian' => 'required|string|max:255'
        ]);

        Uraian::create([
            'kategori_id' => $request->kategori_id,
            'nama_uraian' => $request->nama_uraian
        ]);

        return redirect()->back()->with('success', 'Uraian berhasil ditambahkan!');
    }

    // ✅ FORM EDIT
    public function edit($id)
    {
        $uraian = Uraian::findOrFail($id);
        $kategoris = Kategori::all();

        return view('uraian.edit', compact('uraian', 'kategoris'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_uraian' => 'required|string|max:255'
        ]);

        $uraian = Uraian::findOrFail($id);

        $uraian->update([
            'kategori_id' => $request->kategori_id,
            'nama_uraian' => $request->nama_uraian
        ]);

        return redirect()->back()->with('success', 'Uraian berhasil diupdate!');
    }

    // ✅ HAPUS
    public function destroy($id)
    {
        $uraian = Uraian::findOrFail($id);

        // ❗ optional: cek apakah punya sub uraian
        if ($uraian->subUraian()->count() > 0) {
            return redirect()->back()->with('error', 'Uraian tidak bisa dihapus karena masih memiliki sub uraian!');
        }

        $uraian->delete();

        return redirect()->back()->with('success', 'Uraian berhasil dihapus!');
    }
}