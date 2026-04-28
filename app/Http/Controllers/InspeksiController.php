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

class InspeksiController extends Controller
{
    public function dashboard()
    {
        $inspeksis = Inspeksi::with(['detailInspeksi', 'fotos'])->latest()->get();
        $kategoris = Kategori::with(['uraian.subUraian'])->get();

        $total = 0;
        $ya = 0;
        $tidak = 0;

        foreach ($inspeksis as $inspeksi) {
            $detail = $inspeksi->detailInspeksi ?? collect();

            $total += $detail->count();
            $ya += $detail->where('nilai', 'ya')->count();
            $tidak += $detail->where('nilai', '!=', 'ya')->count();
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
            // =========================
            // SIMPAN INSPEKSI
            // =========================
            $inspeksi = Inspeksi::create([
                'tanggal' => $request->tanggal,
                'ruangan' => $request->ruangan,
                'nama_petugas_k3rs' => $request->nama_petugas_k3rs,
                'nama_petugas_ruangan' => $request->nama_petugas_ruangan,
                'paraf_petugas_k3rs' => $request->paraf_petugas_k3rs,
                'paraf_petugas_ruangan' => $request->paraf_petugas_ruangan,
            ]);

            // =========================
            // SIMPAN DETAIL
            // =========================
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

            // =========================
            // SIMPAN FOTO (ANTI ERROR)
            // =========================
            $adaKolomSub = Schema::hasColumn('foto_inspeksi', 'sub_uraian_id');

            if ($request->hasFile('foto')) {

                foreach ($request->file('foto') as $subUraianId => $files) {

                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {

                        if (!$file || !$file->isValid()) continue;

                        $path = $file->store('foto_inspeksi', 'public');

                        // 🔥 AUTO ADAPT DB
                        $data = [
                            'inspeksi_id' => $inspeksi->id,
                            'path'        => $path
                        ];

                        if ($adaKolomSub) {
                            $data['sub_uraian_id'] = $subUraianId;
                        }

                        FotoInspeksi::create($data);
                    }
                }
            }

            DB::commit();

            return redirect()->route('inspeksi.hasil', $inspeksi->id)
                ->with('success', 'Data inspeksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage()); // biar gak gelap lagi
        }
    }

    public function hasil($id)
    {
        $inspeksi = Inspeksi::with([
            'detailInspeksi.subUraian.uraian.kategori',
            'fotos'
        ])->findOrFail($id);

        $detail = $inspeksi->detailInspeksi ?? collect();

        $ya = $detail->where('nilai', 'ya')->count();
        $tidak = $detail->where('nilai', '!=', 'ya')->count();
        $total = $ya + $tidak;
        $persentase = $total ? round(($ya / $total) * 100, 2) : 0;

        return view('inspeksi.hasil', compact(
            'inspeksi',
            'detail',
            'ya',
            'tidak',
            'total',
            'persentase'
        ));
    }

    public function cetak($id)
    {
        $inspeksi = Inspeksi::with([
            'detailInspeksi.subUraian.uraian.kategori',
            'fotos'
        ])->findOrFail($id);

        $detail = $inspeksi->detailInspeksi ?? collect();

        $ya = $detail->where('nilai', 'ya')->count();
        $tidak = $detail->where('nilai', '!=', 'ya')->count();
        $total = $ya + $tidak;
        $persentase = $total ? round(($ya / $total) * 100, 2) : 0;

        $pdf = Pdf::loadView('inspeksi.pdf', compact(
            'inspeksi',
            'detail',
            'ya',
            'tidak',
            'total',
            'persentase'
        ));

        return $pdf->download('inspeksi-' . $id . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new InspeksiExport, 'inspeksi.xlsx');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $inspeksi = Inspeksi::with(['detailInspeksi', 'fotos'])->findOrFail($id);

            foreach ($inspeksi->fotos as $foto) {
                if ($foto->path && Storage::disk('public')->exists($foto->path)) {
                    Storage::disk('public')->delete($foto->path);
                }
                $foto->delete();
            }

            foreach ($inspeksi->detailInspeksi as $detail) {
                $detail->delete();
            }

            $inspeksi->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data inspeksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
