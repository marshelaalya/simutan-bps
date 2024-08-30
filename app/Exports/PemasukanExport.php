<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PemasukanExport
{
    public function export($filePath, $startDate, $endDate)
    {
        // Muat spreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Update header dan data inventory
        $this->updateHeader($sheet, $startDate, $endDate);
        $this->updateInventoryFromExcel($spreadsheet, $sheet, $startDate, $endDate);

        // Simpan perubahan ke file sementara
        $tempFilePath = storage_path('app/excel/Laporan_Rincian_Persediaan_' . now()->format('Ymd_His') . '.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFilePath);
        
        return $tempFilePath;
    }

    private function updateHeader($sheet, $startDate, $endDate)
    {
        // Ubah teks pada sel A5
        $sheet->setCellValue('A5', 'UNTUK PERIODE YANG BERAKHIR TANGGAL ' . Carbon::parse($endDate)->format('d-m-Y'));

        // Ubah teks pada sel A6 hanya menampilkan tahun
        $sheet->setCellValue('A6', 'TAHUN ANGGARAN : ' . Carbon::parse($endDate)->format('Y'));

        // Ubah teks pada kolom D10 menjadi "Nilai" di baris pertama dan "$startDate" di baris kedua
        $sheet->setCellValue('D10', "Nilai" . PHP_EOL . Carbon::parse($startDate)->format('d-m-Y'));

        // Atur format sel agar mendukung pemisah baris (wrap text)
        $sheet->getStyle('D10')->getAlignment()->setWrapText(true);

        // Ubah teks pada kolom I10 menjadi "Nilai $endDate"
        $sheet->setCellValue('I10', "Nilai" . PHP_EOL . Carbon::parse($endDate)->format('d-m-Y'));
        // Atur format sel agar mendukung pemisah baris (wrap text)
        $sheet->getStyle('I10')->getAlignment()->setWrapText(true);
    }

    private function updateInventoryFromExcel($spreadsheet, $sheet, $startDate, $endDate)
    {
        $rowIndex = 12; // Baris data mulai dari baris ke-12
        $existingBarang = []; // Array untuk menyimpan kode barang yang sudah ada di Excel
        $kelompok_kode = null;

        foreach ($sheet->getRowIterator($rowIndex) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Ambil kode barang dari kolom B (index 1)
            $kode = $rowData[1];

            // Gabungkan kelompok kode dengan kode barang jika ada
            if ($kode) {
                if (strlen($kode) == 10) {
                    // Jika kode memiliki 10 digit, maka ini adalah kelompok_kode
                    $kelompok_kode = $kode;
                } elseif (strlen($kode) == 6 && isset($kelompok_kode)) {
                    // Jika kode memiliki 6 digit, gabungkan dengan kelompok_kode
                    $kode_barang_full = "{$kelompok_kode}{$kode}";

                    // Tambahkan kode barang penuh ke existingBarang
                    $existingBarang[] = $kode_barang_full;

                    // Cari barang di database berdasarkan kode yang digabungkan
                    $barang = DB::table('barangs')->where('kode', $kode_barang_full)->first();

                    if ($barang) {
                        // Hitung stok awal pada start_date
                        $stokAwal = DB::table('stok_awal_bulans')
                            ->where('barang_id', $barang->id)
                            ->where('tahun', Carbon::parse($startDate)->year)
                            ->where('bulan', Carbon::parse($startDate)->month)
                            ->value('qty_awal');

                        // Hitung pemasukan di antara start_date dan endDate
                        $totalPemasukan = DB::table('pemasukans')
                            ->where('barang_id', $barang->id)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->sum('qty');

                        // Hitung pengeluaran di antara start_date dan endDate
                        $totalPengeluaran = DB::table('pengeluarans')
                            ->where('barang_id', $barang->id)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->sum('qty');

                        // Hitung stok akhir
                        $stokAkhir = $stokAwal + $totalPemasukan - $totalPengeluaran;

                        // Set nilai baru ke dalam sheet Excel
                        $sheet->setCellValue('D' . $rowIndex, $stokAwal); // Stok awal pada kolom D
                        $sheet->setCellValue('F' . $rowIndex, $totalPemasukan); // Pemasukan pada kolom F
                        $sheet->setCellValue('G' . $rowIndex, -$totalPengeluaran); // Pengeluaran pada kolom G (negatif)
                        $sheet->setCellValue('H' . $rowIndex, "=IF(F$rowIndex+G$rowIndex<0, F$rowIndex+G$rowIndex, F$rowIndex+G$rowIndex)");
                        $sheet->setCellValue('I' . $rowIndex, $stokAkhir); // Stok akhir pada kolom I
                    } else {
                        Log::warning("Barang tidak ditemukan untuk kode: $kode_barang_full pada baris $rowIndex");
                    }
                }
            }

            $rowIndex++;
        }
        // Simpan existingBarang untuk digunakan pada sheet baru
        $this->createNewSheetWithExistingAndNewBarang($spreadsheet, $existingBarang, $startDate, $endDate);
    }

    private function createNewSheetWithExistingAndNewBarang($spreadsheet, $existingBarang, $startDate, $endDate)
    {
        // Buat sheet baru untuk barang baru
        $newSheet = $spreadsheet->createSheet();
        $newSheet->setTitle('Barang Baru');
        
        // Tambahkan header di sheet baru
        $newSheet->setCellValue('A1', 'Kode Barang Baru');
        $newSheet->setCellValue('B1', 'Nama Barang Baru');
        
        $rowIndex = 2; // Mulai dari baris kedua untuk memasukkan data barang baru
        
        // Ambil semua barang dari database yang tidak ada di Excel
        $barangBaru = DB::table('barangs')
            ->whereNotIn('kode', $existingBarang)
            ->get();
    
        // Inisialisasi array untuk menyimpan baris terakhir dari setiap kelompok kode
        $kelompokKodeBaris = [];
    
        // Iterasi melalui sheet untuk menemukan baris terakhir dari setiap kelompok kode
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($sheet->getRowIterator(12) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
    
            $kode = $rowData[1];
            if ($kode && strlen($kode) == 10) {
                // Jika kode adalah kelompok kode (10 digit), simpan barisnya
                $kelompok_kode = $kode;
                $kelompokKodeBaris[$kelompok_kode] = $row->getRowIndex();
            } elseif ($kode && strlen($kode) == 6 && isset($kelompok_kode)) {
                // Jika kode adalah barang (6 digit) dalam kelompok kode, perbarui baris terakhir kelompok kode
                $kelompokKodeBaris[$kelompok_kode] = $row->getRowIndex();
            }
        }
    
        // Masukkan barang baru di bawah baris terakhir dari kelompok kode yang sesuai di sheet utama
        foreach ($barangBaru as $barang) {
            $kelompokKode = substr($barang->kode, 0, 10); // Dapatkan 10 digit pertama sebagai kelompok kode
    
            if (isset($kelompokKodeBaris[$kelompokKode])) {
                // Baris di mana kelompok kode terakhir kali muncul
                $barisKelompok = $kelompokKodeBaris[$kelompokKode];
                
                // Sisipkan barang baru di bawah baris terakhir kelompok kode yang sesuai
                $sheet->insertNewRowBefore($barisKelompok + 1, 1);
                $sheet->setCellValue('B' . ($barisKelompok + 1), substr($barang->kode, 10)); // Masukkan 6 digit terakhir sebagai kode barang
                $sheet->setCellValue('C' . ($barisKelompok + 1), $barang->nama); // Masukkan nama barang
    
                // Hitung stok awal, pemasukan, pengeluaran, dan stok akhir
                $stokAwal = DB::table('stok_awal_bulans')
                    ->where('barang_id', $barang->id)
                    ->where('tahun', Carbon::parse($startDate)->year)
                    ->where('bulan', Carbon::parse($startDate)->month)
                    ->value('qty_awal');
    
                $totalPemasukan = DB::table('pemasukans')
                    ->where('barang_id', $barang->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->sum('qty');
    
                $totalPengeluaran = DB::table('pengeluarans')
                    ->where('barang_id', $barang->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->sum('qty');
    
                $stokAkhir = $stokAwal + $totalPemasukan - $totalPengeluaran;
    
                // Masukkan nilai stok awal, pemasukan, pengeluaran, dan stok akhir ke dalam sheet Excel
                $sheet->setCellValue('D' . ($barisKelompok + 1), $stokAwal); // Stok awal pada kolom D
                $sheet->setCellValue('F' . ($barisKelompok + 1), $totalPemasukan); // Pemasukan pada kolom F
                $sheet->setCellValue('G' . ($barisKelompok + 1), -$totalPengeluaran); // Pengeluaran pada kolom G (negatif)
                $sheet->setCellValue('H' . ($barisKelompok + 1), "=F" . ($barisKelompok + 1) . "+G" . ($barisKelompok + 1)); // Rumus untuk kolom H
                $sheet->setCellValue('I' . ($barisKelompok + 1), $stokAkhir); // Stok akhir pada kolom I
    
                // Perbarui posisi kelompok kode
                $kelompokKodeBaris[$kelompokKode]++;
            }
        }
    
        // Juga tambahkan barang baru ke sheet "Barang Baru" untuk pelacakan tambahan
        foreach ($barangBaru as $barang) {
            $newSheet->setCellValue('A' . $rowIndex, $barang->kode);
            $newSheet->setCellValue('B' . $rowIndex, $barang->nama);
            $rowIndex++;
        }
    }
    
}
