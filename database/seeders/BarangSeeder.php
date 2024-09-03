<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run()
    {
        // Path ke file CSV
        $filePath = base_path('app/Console/Commands/barang.csv');

        // Baca CSV
        $data = array_map('str_getcsv', file($filePath));
        $header = array_shift($data); // Ambil header

        foreach ($data as $row) {
            $rowData = array_combine($header, $row);

            // Simpan data ke database
            Barang::create([
                'kode' => $rowData['kode'],
                'nama' => $rowData['nama'],
                'kelompok_id' => $rowData['kelompok_id'],
                'qty_item' => $rowData['qty_item'],
                'satuan' => $rowData['satuan'],
                'foto_barang' => $rowData['foto_barang'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
