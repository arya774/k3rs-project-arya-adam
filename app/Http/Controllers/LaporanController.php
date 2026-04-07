<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $ruangan = $request->ruangan ?? '';

        $data = DB::table('inspeksi as i')
            ->leftJoin('detail_inspeksi as d', 'd.inspeksi_id', '=', 'i.id')
            ->leftJoin('sub_uraian as su', 'su.id', '=', 'd.sub_uraian_id')
            ->leftJoin('uraian as u', 'u.id', '=', 'su.uraian_id')
            ->leftJoin('kategori as k', 'k.id', '=', 'u.kategori_id')
            ->whereDate('i.tanggal', $tanggal)
            ->when($ruangan, function ($query) use ($ruangan) {
                $query->where('i.ruangan', 'like', '%' . $ruangan . '%');
            })
            ->select(
                'i.tanggal',
                'i.ruangan',
                'k.nama_kategori',
                'u.nama_uraian',
                'su.nama_sub_uraian',
                'd.nilai',
                'd.catatan'
            )
            ->orderBy('k.id')
            ->orderBy('u.id')
            ->get();

        return view('laporan.index', compact('data', 'tanggal', 'ruangan'));
    }
}