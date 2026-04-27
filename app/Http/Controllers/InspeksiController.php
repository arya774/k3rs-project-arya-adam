<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspeksi;
use App\Models\DetailInspeksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\InspeksiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Kategori;
use App\Models\Uraian;
use App\Models\SubUraian;


class InspeksiController extends Controller
{
    // ============================
    // DASHBOARD (FIX LIST + STAT)
    // ============================
    // taruh di atas file controller

public function dashboard()
{
    $inspeksi = Inspeksi::with('detailInspeksi')->latest()->first();

    $kategoris = Kategori::with(['uraian.subUraian'])->get();

    $total = 0;
    $ya = 0;
    $tidak = 0;
    $persentase = 0;

    if ($inspeksi) {

        $detail = $inspeksi->detailInspeksi;

        $total = $detail->count();
        $ya = $detail->where('nilai', 'ya')->count();
        $tidak = $detail->where('nilai', 'tidak')->count();

        $persentase = $total > 0 ? round(($ya / $total) * 100, 2) : 0;
    }

    return view('inspeksi.dashboard', compact(
        'inspeksi',
        'total',
        'ya',
        'tidak',
        'persentase',
        'kategoris'
    ));
}

    // ============================
    // WIZARD
    // ============================

public function wizard()
{
    $kategori = Kategori::with('uraian.subUraian')->get();
    $subUraian = SubUraian::all(); // 🔥 INI YANG KURANG

    return view('inspeksi.wizard', compact('kategori', 'subUraian'));
}



    public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required',
        'ruangan' => 'required',
        'nama_petugas_k3rs' => 'required',
        'nama_petugas_ruangan' => 'required',
    ]);

    $inspeksi = Inspeksi::create([
        'tanggal' => $request->tanggal,
        'ruangan' => $request->ruangan,
        'nama_petugas_k3rs' => $request->nama_petugas_k3rs,
        'nama_petugas_ruangan' => $request->nama_petugas_ruangan,
    ]);

    if ($request->has('nilai')) {
        foreach ($request->nilai as $subId => $nilai) {

            DetailInspeksi::create([
                'inspeksi_id' => $inspeksi->id,
                'sub_uraian_id' => $subId,
                'nilai' => $nilai ?? null,
                'catatan' => $request->catatan_multi[$subId] ?? null
            ]);
        }
    }

    return redirect()->route('inspeksi.hasil', $inspeksi->id);
}
    // ============================
    // EDIT INSPEKSI (BARU)
    // ============================
    public function edit($id)
    {
        $inspeksi = Inspeksi::findOrFail($id);

        return view('inspeksi.edit', compact('inspeksi'));
    }

    // ============================
    // UPDATE INSPEKSI (BARU)
    // ============================
    public function update(Request $request, $id)
    {
        $inspeksi = Inspeksi::findOrFail($id);

        $inspeksi->update([
            'tanggal' => $request->tanggal,
            'ruangan' => $request->ruangan,
            'nama_petugas_k3rs' => $request->nama_petugas_k3rs,
            'nama_petugas_ruangan' => $request->nama_petugas_ruangan,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Data berhasil diupdate');
    }

    // ============================
    // DELETE INSPEKSI (BARU)
    // ============================
    public function destroy($id)
    {
        $inspeksi = Inspeksi::findOrFail($id);
        $inspeksi->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Data berhasil dihapus');
    }

    // ============================
    // DELETE KATEGORI
    // ============================
    public function deleteKategori($id)
    {
        $kategoris = Kategori::with('uraian.subUraian')->find($id);

        if (!$kategoris) {
            return response()->json(['message' => 'Not found'], 404);
        }

        foreach ($kategoris->uraian as $u) {
            $u->subUraian()->delete();
        }

        $kategoris->uraian()->delete();
        $kategoris->delete();

        return response()->json(['success' => true]);
    }

    // ============================
    // DELETE URAIAN
    // ============================
    public function deleteUraian($id)
    {
        $data = Uraian::find($id);

        if ($data) {
            $data->subUraian()->delete();
            $data->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    // ============================
    // DELETE SUB URAIAN
    // ============================
    public function deleteSubUraian($id)
    {
        try {
            $sub = SubUraian::findOrFail($id);
            $sub->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sub uraian berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal hapus'
            ], 500);
        }
    }

    // ============================
    // MASTER DATA
    // ============================
    public function storeMasterData(Request $request, $type)
    {
        try {

            if ($type === 'kategori') {

                $request->validate([
                    'nama_kategori' => 'required|string|unique:kategori,nama_kategori'
                ]);

                Kategori::create([
                    'nama_kategori' => $request->nama_kategori
                ]);

            } elseif ($type === 'uraian') {

                $request->validate([
                    'kategori_id' => 'required|exists:kategori,id',
                    'nama_uraian' => 'required|string'
                ]);

                Uraian::create([
                    'kategori_id' => $request->kategori_id,
                    'nama_uraian' => $request->nama_uraian
                ]);

            } elseif ($type === 'suburaian') {

                $request->validate([
                    'uraian_id' => 'required|exists:uraian,id',
                    'nama_sub_uraian' => 'required'
                ]);

                $subUraians = $request->nama_sub_uraian;

                if (!is_array($subUraians)) {
                    $subUraians = [$subUraians];
                }

                SubUraian::where('uraian_id', $request->uraian_id)->delete();

                foreach ($subUraians as $sub) {

                    $sub = trim($sub);

                    if ($sub !== '') {

                        $exists = SubUraian::where('uraian_id', $request->uraian_id)
                            ->where('nama_sub_uraian', $sub)
                            ->exists();

                        if (!$exists) {
                            SubUraian::create([
                                'uraian_id' => $request->uraian_id,
                                'nama_sub_uraian' => $sub
                            ]);
                        }
                    }
                }

            } else {
                return response()->json(['error' => 'Tipe tidak valid'], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ============================
    // STORE INSPEKSI
    // ============================
   public function storeInspeksi(Request $request)
{
    $request->validate([
        'tanggal' => 'required',
        'ruangan' => 'required',
        'nama_petugas_k3rs' => 'required',
        'nama_petugas_ruangan' => 'required',
    ]);

    $inspeksi = Inspeksi::create([
        'tanggal' => $request->tanggal,
        'ruangan' => $request->ruangan,
        'nama_petugas_k3rs' => $request->nama_petugas_k3rs,
        'nama_petugas_ruangan' => $request->nama_petugas_ruangan,
        'paraf_petugas_k3rs' => $request->paraf_petugas_k3rs,
        'paraf_petugas_ruangan' => $request->paraf_petugas_ruangan,
    ]);

    if ($request->nilai) {
        foreach ($request->nilai as $subId => $nilai) {

            DetailInspeksi::create([
                'inspeksi_id' => $inspeksi->id,
                'sub_uraian_id' => $subId,
                'nilai' => $nilai,
                'catatan' => $request->catatan_multi[$subId] ?? null
            ]);
        }
    }

    return redirect()->route('inspeksi.hasil', $inspeksi->id)
        ->with('success', 'Inspeksi berhasil disimpan');
}

    // ============================
    // HASIL
    // ============================
    public function hasil($id)
    {
        $inspeksi = Inspeksi::with(['detailInspeksi.subUraian.uraian.kategori'])
            ->findOrFail($id);

        $detail = $inspeksi->detailInspeksi ?? collect();

        $ya = $detail->where('nilai', 'ya')->count();
        $tidak = $detail->where('nilai', '!=', 'ya')->count();
        $total = $ya + $tidak;
        $persentase = $total ? ($ya / $total) * 100 : 0;

        return view('inspeksi.hasil', compact(
            'inspeksi',
            'detail',
            'ya',
            'tidak',
            'total',
            'persentase'
        ));
    }

    // ============================
    // CETAK PDF
    // ============================
    public function cetak($id)
    {
        $inspeksi = Inspeksi::with(['detailInspeksi.subUraian.uraian.kategori'])
            ->findOrFail($id);

        $detail = $inspeksi->detailInspeksi ?? collect();

        $ya = $detail->where('nilai', 'ya')->count();
        $tidak = $detail->where('nilai', '!=', 'ya')->count();
        $total = $ya + $tidak;
        $persentase = $total ? ($ya / $total) * 100 : 0;

        $pdf = Pdf::loadView('inspeksi.pdf', compact(
            'inspeksi',
            'detail',
            'ya',
            'tidak',
            'total',
            'persentase'
        ))->setPaper('A4', 'portrait');

        return $pdf->download('inspeksi-'.$id.'.pdf');
    }

    // ============================
    // EXPORT EXCEL
    // ============================
    public function exportExcel()
    {
        return Excel::download(new InspeksiExport, 'inspeksi.xlsx');
    }
}
