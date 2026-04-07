<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string']);
        Kategori::create($request->only('nama_kategori'));
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }
}