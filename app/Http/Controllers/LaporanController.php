<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // =========================
    // INDEX LAPORAN
    // =========================
    public function index(Request $request)
    {
        $query = DB::table('inspeksi as i')
            ->leftJoin('detail_inspeksi as d', 'd.inspeksi_id', '=', 'i.id')
            ->leftJoin('sub_uraian as su', 'su.id', '=', 'd.sub_uraian_id')
            ->leftJoin('uraian as u', 'u.id', '=', 'su.uraian_id')
            ->leftJoin('kategori as k', 'k.id', '=', 'u.kategori_id')
            ->select(
                'i.tanggal',
                'i.ruangan',
                'k.nama_kategori',
                'u.nama_uraian',
                'su.nama_sub_uraian',
                'd.nilai',
                'd.catatan'
            );

        // 🔥 FILTER FLEXIBLE
        if ($request->filled('tanggal')) {
            $query->whereDate('i.tanggal', $request->tanggal);
        }

        if ($request->filled('ruangan')) {
            $query->where('i.ruangan', 'like', '%' . $request->ruangan . '%');
        }

        $data = $query->orderBy('i.tanggal', 'desc')->get();

        return view('laporan.index', compact('data'));
    }

    // =========================
    // CETAK PDF (UPGRADE)
    // =========================
    public function cetakPerRuangan(Request $request)
    {
        $query = DB::table('inspeksi as i')
            ->leftJoin('detail_inspeksi as d', 'd.inspeksi_id', '=', 'i.id')
            ->leftJoin('sub_uraian as su', 'su.id', '=', 'd.sub_uraian_id')
            ->leftJoin('uraian as u', 'u.id', '=', 'su.uraian_id')
            ->leftJoin('kategori as k', 'k.id', '=', 'u.kategori_id')
            ->select(
                'i.tanggal',
                'i.ruangan',
                'k.nama_kategori',
                'u.nama_uraian',
                'su.nama_sub_uraian',
                'd.nilai',
                'd.catatan'
            );

        // 🔥 FILTER SAMA DENGAN INDEX
        if ($request->filled('tanggal')) {
            $query->whereDate('i.tanggal', $request->tanggal);
        }

        if ($request->filled('ruangan')) {
            $query->where('i.ruangan', 'like', '%' . $request->ruangan . '%');
        }

        $data = $query->orderBy('i.tanggal', 'desc')->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan!');
        }

        $pdf = Pdf::loadView('laporan.pdf_ruangan', [
            'data' => $data,
            'tanggal' => $request->tanggal,
            'ruangan' => $request->ruangan
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan.pdf');
    }
}