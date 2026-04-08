<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Uraian;
use App\Models\SubUraian;

class MasterController extends Controller
{
    public function index()
    {
        return view('master.index', [
            'kategori' => Kategori::all(),
            'uraian' => Uraian::all(),
            'suburaian' => SubUraian::all(),
        ]);
    }

    public function create($type)
    {
        return view('master.form', compact('type'));
    }

    public function store(Request $request, $type)
    {
        $request->validate(['nama'=>'required|string|max:255']);

        switch($type) {
            case 'kategori': Kategori::create($request->only('nama')); break;
            case 'uraian': Uraian::create($request->only('nama')); break;
            case 'suburaian': SubUraian::create($request->only('nama')); break;
            default: abort(404);
        }

        return redirect()->route('master.index')->with('success','Data berhasil ditambah');
    }

    public function edit($type, $id)
    {
        $data = match($type){
            'kategori'=> Kategori::findOrFail($id),
            'uraian'=> Uraian::findOrFail($id),
            'suburaian'=> SubUraian::findOrFail($id),
            default=> abort(404),
        };

        return view('master.form', compact('type','data'));
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate(['nama'=>'required|string|max:255']);

        $model = match($type){
            'kategori'=> Kategori::findOrFail($id),
            'uraian'=> Uraian::findOrFail($id),
            'suburaian'=> SubUraian::findOrFail($id),
            default=> abort(404),
        };

        $model->update($request->only('nama'));

        return redirect()->route('master.index')->with('success','Data berhasil diupdate');
    }

    public function destroy($type, $id)
    {
        $model = match($type){
            'kategori'=> Kategori::findOrFail($id),
            'uraian'=> Uraian::findOrFail($id),
            'suburaian'=> SubUraian::findOrFail($id),
            default=> abort(404),
        };

        $model->delete();

        return redirect()->route('master.index')->with('success','Data berhasil dihapus');
    }
}