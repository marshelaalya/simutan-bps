<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\StokAwalBulan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StokAwalBulanSeeder extends Seeder
{
    public function run()
    {
        // Path ke file CSV
        $file = storage_path('app/public/barang.csv');

        // Baca CSV
        $data = array_map('str_getcsv', file($file));
        $header = array_shift($data);

        foreach ($data as $row) {
            $rowData = array_combine($header, $row);

            StokAwalBulan::create([
                'barang_id' => $rowData['id'],
                'qty_awal' => $rowData['qty_item'],
                'tahun' => 2024,
                'bulan' => 8,
            ]);
        }
    }
}
