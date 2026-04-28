<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspeksi;
use App\Models\DetailInspeksi;
use App\Models\FotoInspeksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\InspeksiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Kategori;
use App\Models\Uraian;
use App\Models\SubUraian;

class InspeksiController extends Controller
{
    public function dashboard()
    {
        $inspeksis = Inspeksi::with('detailInspeksi')->latest()->get();
        $kategoris = Kategori::with(['uraian.subUraian'])->get();

        $total = 0;
        $ya = 0;
        $tidak = 0;

        foreach ($inspeksis as $inspeksi) {
            $detail = $inspeksi->detailInspeksi ?? collect();

            $total += $detail->count();
            $ya += $detail->where('nilai', 'ya')->count();
            $tidak += $detail->where('nilai', 'tidak')->count();
        }

        $persentase = $total > 0 ? round(($ya / $total) * 100, 2) : 0;

        return view('inspeksi.dashboard', compact(
            'inspeksis',
            'total',
            'ya',
            'tidak',
            'persentase',
            'kategoris'
        ));
    }

    public function wizard()
    {
        $kategoris = Kategori::with('uraian.subUraian')->get();
        return view('inspeksi.wizard', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'ruangan' => 'required',
            'nama_petugas_k3rs' => 'required',
            'nama_petugas_ruangan' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $inspeksi = Inspeksi::create([
                'tanggal' => $request->tanggal,
                'ruangan' => $request->ruangan,
                'nama_petugas_k3rs' => $request->nama_petugas_k3rs,
                'nama_petugas_ruangan' => $request->nama_petugas_ruangan,
                'paraf_petugas_k3rs' => $request->paraf_petugas_k3rs,
                'paraf_petugas_ruangan' => $request->paraf_petugas_ruangan,
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

            if (Schema::hasTable('foto_inspeksi') && $request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {

                    if (!$file) continue;

                    $path = $file->store('foto_inspeksi', 'public');

                    FotoInspeksi::create([
                        'inspeksi_id' => $inspeksi->id,
                        'path' => $path,
                        'nama_file' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'disk' => 'public'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('inspeksi.hasil', $inspeksi->id);

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // =========================
    // 🔥 HASIL (FIX DISINI)
    // =========================
    public function hasil($id)
    {
        $inspeksi = Inspeksi::with([
            'detailInspeksi.subUraian.uraian.kategori',
            'fotos' // ✅ WAJIB pakai ini
        ])->findOrFail($id);

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

    // =========================
    // 🔥 CETAK (FIX DISINI JUGA)
    // =========================
    public function cetak($id)
    {
        $inspeksi = Inspeksi::with([
            'detailInspeksi.subUraian.uraian.kategori',
            'fotos' // ✅ WAJIB
        ])->findOrFail($id);

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
        ));

        return $pdf->download('inspeksi-'.$id.'.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new InspeksiExport, 'inspeksi.xlsx');
    }
}