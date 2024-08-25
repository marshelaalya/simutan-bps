<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemasukanExport
{
    public function export($filePath, $saveAwalBulan = false)
    {
        // Muat spreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Update inventory data berdasarkan kebutuhan (save awal bulan atau akhir bulan)
        $changesMade = $this->updateInventoryFromExcel($sheet, $saveAwalBulan);

        // Simpan perubahan jika ada
        if ($changesMade) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            // Buat nama file baru berdasarkan waktu saat ini
            $newFileName = $saveAwalBulan ? 'Laporan_Rincian_Persediaan_Awal_Bulan_' . now()->format('Ymd_His') . '.xlsx' 
                                          : 'Laporan_Rincian_Persediaan_Updat_' . now()->format('Ymd_His') . '.xlsx';
            $newFilePath = resource_path('excel/' . $newFileName);

            // Simpan ke file baru
            $writer->save($newFilePath);
            Log::info("Perubahan disimpan ke file baru: $newFilePath");
            return $newFilePath;
        } else {
            Log::info("Tidak ada perubahan yang dilakukan.");
        }

        return null; // Tidak ada perubahan yang perlu disimpan
    }

    private function updateInventoryFromExcel($sheet, $saveAwalBulan)
    {
        $kelompok_kode = '';
        $rowIndex = 0;
        $changesMade = false;

        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex++;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Deteksi jika baris adalah kode kelompok (10 digit) atau 6 digit kode barang
            if (strlen($rowData[2]) == 10) {  // Asumsikan kode kelompok ada di kolom C (index 2)
                $kelompok_kode = $rowData[2];
                Log::info("Kelompok kode ditemukan: $kelompok_kode pada baris $rowIndex");
            } else if ($kelompok_kode != '') {  // Pastikan kode kelompok sudah ditemukan
                
                // Mencari kolom mana yang berisi 6 digit kode barang
                $kode_barang = $this->getKodeBarangFromMergedCells($rowData, [2, 3, 5]); // Asumsikan kolom yang di-merge adalah C, D, F (index 2, 3, 5)

                if ($kode_barang && strlen($kode_barang) == 6) {
                    // Menggabungkan kelompok_kode dan kode_barang
                    $kode_barang_full = "{$kelompok_kode}{$kode_barang}";

                    // Cari barang di database berdasarkan kode yang digabungkan
                    $barang = DB::table('barangs')->where('kode', $kode_barang_full)->first();

                    if ($barang) {
                        $sheet->setCellValue('AL' . $rowIndex, $barang->qty_item);
                        // Jika saveAwalBulan diaktifkan dan kolom Q kosong, simpan qty_item di awal bulan ke kolom Q
                        if ($saveAwalBulan && !$sheet->getCell('Q' . $rowIndex)->getValue()) {
                            $sheet->setCellValue('Q' . $rowIndex, $barang->qty_item);
                            $changesMade = true; // Tanda bahwa ada perubahan yang dilakukan
                            Log::info("Data awal bulan disimpan di kolom Q untuk baris $rowIndex");
                        }

                        // Selalu update qty_item di akhir bulan ke kolom U
                        $sheet->setCellValue('U' . $rowIndex, $barang->qty_item);
                        $changesMade = true; // Tanda bahwa ada perubahan yang dilakukan
                        Log::info("Data akhir bulan disimpan di kolom U untuk baris $rowIndex");
                    } else {
                        Log::warning("Barang tidak ditemukan untuk kode: $kode_barang_full pada baris $rowIndex");
                    }
                } else {
                    Log::warning("Kode barang tidak ditemukan atau tidak valid pada baris $rowIndex");
                }
            }
        }

        // Kembalikan apakah ada perubahan yang dilakukan
        return $changesMade;
    }

    private function getKodeBarangFromMergedCells($rowData, $indices)
    {
        // Cek setiap indeks yang mungkin untuk nilai kode barang
        foreach ($indices as $index) {
            if (isset($rowData[$index]) && !empty($rowData[$index])) {
                return $rowData[$index];
            }
        }

        // Jika semua kosong, kembalikan null
        return null;
    }
}
