<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Uraian;
use App\Models\SubUraian;
use App\Models\Inspeksi;
use App\Models\DetailInspeksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\InspeksiExport;
use Maatwebsite\Excel\Facades\Excel;




class InspeksiController extends Controller
{
    // ============================
    // WIZARD (AUTO REFRESH DATA)
    // ============================
    public function wizard()
    {
        $kategoris = Kategori::with(['uraian.subUraian'])
            ->orderBy('nama_kategori')
            ->get();

        return view('inspeksi.wizard', compact('kategoris'));
    }

    public function getUraian($id)
{
    return \App\Models\Uraian::where('kategori_id', $id)->get();
}
    // ============================
    // MASTER DATA (FIX RETURN + AUTO RELOAD SUPPORT)
    // ============================
    public function storeMasterData(Request $request, $type)
{
    try {

        if ($type === 'kategori') {

            $request->validate([
                'nama_kategori' => 'required|string|unique:kategori,nama_kategori'
            ]);

            $data = Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);

        } elseif ($type === 'uraian') {

            $request->validate([
                'kategori_id' => 'required|exists:kategori,id',
                'nama_uraian' => 'required|string'
            ]);

            $data = Uraian::create([
                'kategori_id' => $request->kategori_id,
                'nama_uraian' => $request->nama_uraian
            ]);

        } elseif ($type === 'suburaian') {

            $request->validate([
                'uraian_id' => 'required|exists:uraian,id',
                'nama_sub_uraian' => 'required'
            ]);

            // 🔥 FIX: handle string & array
            $subUraians = $request->nama_sub_uraian;

            if (!is_array($subUraians)) {
                $subUraians = [$subUraians];
            }

            $data = [];

            foreach ($subUraians as $sub) {
                if (!empty(trim($sub))) {
                    $data[] = SubUraian::create([
                        'uraian_id' => $request->uraian_id,
                        'nama_sub_uraian' => trim($sub)
                    ]);
                }
            }

        } else {
            return response()->json(['error' => 'Tipe tidak valid'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data ?? null
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}


    // ============================
    // SIMPAN INSPEKSI (FIX TOTAL)
    // ============================
    public function storeInspeksi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'ruangan' => 'required|string',
            'nama_petugas_k3rs' => 'required|string',
            'nama_petugas_ruangan' => 'required|string',
        ]);

        $parafK3rsFile = null;
        $parafRuanganFile = null;

        // PARAF K3RS
        if ($request->paraf_petugas_k3rs) {
            $image = str_replace('data:image/png;base64,', '', $request->paraf_petugas_k3rs);
            $image = str_replace(' ', '+', $image);
            $parafK3rsFile = 'paraf_k3rs_' . time() . '.png';

            Storage::disk('public')->put('paraf/' . $parafK3rsFile, base64_decode($image));
        }

        // PARAF RUANGAN
        if ($request->paraf_petugas_ruangan) {
            $image = str_replace('data:image/png;base64,', '', $request->paraf_petugas_ruangan);
            $image = str_replace(' ', '+', $image);
            $parafRuanganFile = 'paraf_ruangan_' . time() . '.png';

            Storage::disk('public')->put('paraf/' . $parafRuanganFile, base64_decode($image));
        }

        $inspeksi = DB::transaction(function () use ($request, $parafK3rsFile, $parafRuanganFile) {

            $inspeksi = Inspeksi::create([
                'tanggal' => $request->tanggal,
                'ruangan' => $request->ruangan,
                'nama_petugas_k3rs' => $request->nama_petugas_k3rs,
                'paraf_petugas_k3rs' => $parafK3rsFile,
                'nama_petugas_ruangan' => $request->nama_petugas_ruangan,
                'paraf_petugas_ruangan' => $parafRuanganFile,
            ]);

            // FIX NILAI
            if ($request->has('nilai')) {
                foreach ($request->nilai as $subId => $nilai) {

                    if ($nilai === null || $nilai === '') continue;

                    DetailInspeksi::create([
                        'inspeksi_id' => $inspeksi->id,
                        'sub_uraian_id' => $subId,
                        'nilai' => strtolower($nilai),
                        'catatan' => $request->catatan[$subId] ?? null
                    ]);
                }
            }

            return $inspeksi;
        });

        return redirect()->route('inspeksi.hasil', $inspeksi->id)
            ->with('success', 'Inspeksi berhasil disimpan!');
    }

    // ============================
    // HASIL
    // ============================
    public function hasil($id)
    {
        $inspeksi = Inspeksi::with([
            'detailInspeksi.subUraian.uraian.kategori'
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

    // ============================
    // PDF
    // ============================
    public function cetak($id)
    {
        $inspeksi = Inspeksi::with([
            'detailInspeksi.subUraian.uraian.kategori'
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
        ))->setPaper('A4', 'portrait');

        return $pdf->download('inspeksi-'.$id.'.pdf');
    }

    // ============================
    // EXCEL
    // ============================
    public function exportExcel()
    {
        return Excel::download(new InspeksiExport, 'inspeksi.xlsx');
    }

    // ============================
    // DASHBOARD
    // ============================
    public function dashboard()
    {
        $inspeksi = Inspeksi::latest()->first();

        $detail = $inspeksi ? $inspeksi->detailInspeksi : collect();

        $ya = $detail->where('nilai', 'ya')->count();
        $tidak = $detail->where('nilai', '!=', 'ya')->count();
        $total = $ya + $tidak;
        $persentase = $total ? ($ya / $total) * 100 : 0;

        $kategoris = Kategori::with(['uraian.subUraian'])->get();

        return view('inspeksi.dashboard', compact(
            'inspeksi',
            'detail',
            'ya',
            'tidak',
            'total',
            'persentase',
            'kategoris'
        ));
    }
}
