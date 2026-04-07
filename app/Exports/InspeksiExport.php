<?php

namespace App\Exports;
use App\Models\Inspeksi;
use Maatwebsite\Excel\Concerns\FromCollection;

class InspeksiExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
          return Inspeksi::all();
    }
}
