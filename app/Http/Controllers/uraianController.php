<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Uraian;

class UraianController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_uraian' => 'required|string'
        ]);

        Uraian::create($request->only('kategori_id', 'nama_uraian'));
        return redirect()->back()->with('success', 'Uraian berhasil ditambahkan!');
    }
}