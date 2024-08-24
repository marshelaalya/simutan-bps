<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class PemasukanExport
{
    public function export($filePath)
    {
        // Panggil fungsi untuk memperbarui data dari Excel
        $this->updateInventoryFromExcel($filePath);
    
        // Simpan file yang telah diperbarui
        $newFilePath = resource_path('excel/Laporan_Rincian_Persediaan_Updated.xlsx');
        $spreadsheet = IOFactory::load($filePath);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($newFilePath);
    
        return $newFilePath;
    }
    

    private function updateInventoryFromExcel($filePath) {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $kelompok_kode = '';
        $rowIndex = 0;

        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex++;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Melewati header (baris 1-11 dan setiap 8 baris setelahnya)
            if ($rowIndex <= 11 || (($rowIndex - 12) % 8 == 0)) {
                continue;
            }

            // Deteksi jika baris adalah kode kelompok
            if (strlen($rowData[2]) == 10) {  // Asumsikan kode kelompok ada di kolom C (index 2)
                $kelompok_kode = $rowData[2];
            } else if (strlen($rowData[2]) == 6 && $kelompok_kode != '') {
                // Gabungkan kode kelompok dengan kode barang dari Excel
                $kode_barang_full = $kelompok_kode . $rowData[2];

                // Cari barang di database berdasarkan kode yang digabungkan
                $barang = DB::table('barangs')->where('kode', $kode_barang_full)->first();

                if ($barang) {
                    $sheet->setCellValue('Q' . $rowIndex, $barang->qty_item);
                } else {
                    $sheet->setCellValue('Q' . $rowIndex, '0');
                }
            }
        }

        // Simpan perubahan pada Excel file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);  // Simpan kembali ke file asli atau simpan ke path baru
    }
}
