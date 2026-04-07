<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubUraian;

class SubUraianController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'uraian_id' => 'required|exists:uraian,id',
            'nama_sub_uraian' => 'required|string'
        ]);

        SubUraian::create($request->only('uraian_id', 'nama_sub_uraian'));
        return redirect()->back()->with('success', 'Sub-uraian berhasil ditambahkan!');
    }
}