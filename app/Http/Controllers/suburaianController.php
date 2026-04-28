<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubUraian;
use App\Models\Uraian;

class SubUraianController extends Controller
{
    public function index()
    {
        $suburaians = SubUraian::with('uraian.kategori')->latest()->get();
        $uraians = Uraian::with('kategori')->get();

        return view('suburaianindex', compact('suburaians', 'uraians'));
    }

    // ✅ SIMPAN (FIX: TIDAK PAKAI ARRAY LAGI)
    public function store(Request $request)
    {
        $request->validate([
            'uraian_id' => 'required|exists:uraian,id',
            'nama_sub_uraian' => 'required|string|max:255'
        ]);

        SubUraian::create([
            'uraian_id' => $request->uraian_id,
            'nama_sub_uraian' => trim($request->nama_sub_uraian)
        ]);

        return redirect()->back()->with('success', 'Sub uraian berhasil ditambahkan');
    }

    // ✅ EDIT
    public function edit($id)
    {
        $suburaian = SubUraian::findOrFail($id);
        $uraians = Uraian::all();

        return view('suburaian.edit', compact('suburaian', 'uraians'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian_id' => 'required|exists:uraian,id',
            'nama_sub_uraian' => 'required|string|max:255'
        ]);

        $suburaian = SubUraian::findOrFail($id);
        $suburaian->update([
            'uraian_id' => $request->uraian_id,
            'nama_sub_uraian' => trim($request->nama_sub_uraian)
        ]);

        return redirect()->route('suburaian.index')->with('success', 'Sub-uraian berhasil diupdate!');
    }

    // ✅ HAPUS
    public function destroy($id)
    {
        $suburaian = SubUraian::findOrFail($id);
        $suburaian->delete();

        return redirect()->back()->with('success', 'Sub-uraian berhasil dihapus!');
    }
}