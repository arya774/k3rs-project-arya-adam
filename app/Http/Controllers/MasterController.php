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
            'uraian' => Uraian::with('kategori')->get(),
            'suburaian' => SubUraian::with('uraian')->get(),
        ]);
    }

    public function create($type)
    {
        return view('master.form', compact('type'));
    }

    // =========================
    // STORE (FIX RELASI TOTAL)
    // =========================
    public function store(Request $request, $type)
    {
        switch ($type) {

            case 'kategori':
                $request->validate([
                    'nama_kategori' => 'required|string|max:255'
                ]);

                Kategori::create([
                    'nama_kategori' => $request->nama_kategori
                ]);
                break;

            case 'uraian':
                $request->validate([
                    'kategori_id' => 'required|exists:kategori,id',
                    'nama_uraian' => 'required|string|max:255'
                ]);

                Uraian::create([
                    'kategori_id' => $request->kategori_id,
                    'nama_uraian' => $request->nama_uraian
                ]);
                break;

            case 'suburaian':
                $request->validate([
                    'uraian_id' => 'required|exists:uraian,id',
                    'nama_sub_uraian' => 'required|string|max:255'
                ]);

                SubUraian::create([
                    'uraian_id' => $request->uraian_id,
                    'nama_sub_uraian' => $request->nama_sub_uraian
                ]);
                break;

            default:
                abort(404);
        }

        return redirect()->route('master.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($type, $id)
    {
        $data = match ($type) {
            'kategori' => Kategori::findOrFail($id),
            'uraian' => Uraian::findOrFail($id),
            'suburaian' => SubUraian::findOrFail($id),
            default => abort(404),
        };

        return view('master.form', compact('type', 'data'));
    }

    // =========================
    // UPDATE (FIX RELASI)
    // =========================
    public function update(Request $request, $type, $id)
    {
        switch ($type) {

            case 'kategori':
                $request->validate([
                    'nama_kategori' => 'required|string|max:255'
                ]);

                Kategori::findOrFail($id)->update([
                    'nama_kategori' => $request->nama_kategori
                ]);
                break;

            case 'uraian':
                $request->validate([
                    'kategori_id' => 'required|exists:kategori,id',
                    'nama_uraian' => 'required|string|max:255'
                ]);

                Uraian::findOrFail($id)->update([
                    'kategori_id' => $request->kategori_id,
                    'nama_uraian' => $request->nama_uraian
                ]);
                break;

            case 'suburaian':
                $request->validate([
                    'uraian_id' => 'required|exists:uraian,id',
                    'nama_sub_uraian' => 'required|string|max:255'
                ]);

                SubUraian::findOrFail($id)->update([
                    'uraian_id' => $request->uraian_id,
                    'nama_sub_uraian' => $request->nama_sub_uraian
                ]);
                break;

            default:
                abort(404);
        }

        return redirect()->route('master.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($type, $id)
    {
        $model = match ($type) {
            'kategori' => Kategori::findOrFail($id),
            'uraian' => Uraian::findOrFail($id),
            'suburaian' => SubUraian::findOrFail($id),
            default => abort(404),
        };

        $model->delete();

        return redirect()->route('master.index')
            ->with('success', 'Data berhasil dihapus');
    }
}