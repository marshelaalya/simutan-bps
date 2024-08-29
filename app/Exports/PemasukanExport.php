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
            // Jika kode adalah kelompok kode (10 digit), inisialisasi sebagai array untuk menyimpan baris terkait
            $kelompok_kode = $kode;
            $kelompokKodeBaris[$kelompok_kode] = []; // Pastikan ini adalah array
            $kelompokKodeBaris[$kelompok_kode]['last_row'] = $row->getRowIndex();
        } elseif ($kode && strlen($kode) == 6 && isset($kelompok_kode)) {
            // Jika kode adalah barang (6 digit) dalam kelompok kode, perbarui baris terakhir kelompok kode
            $kelompokKodeBaris[$kelompok_kode]['last_row'] = $row->getRowIndex();
            $kelompokKodeBaris[$kelompok_kode][$kode] = $row->getRowIndex();
        }
    }

    // Masukkan barang baru di bawah baris terakhir dari kelompok kode yang sesuai di sheet utama
    foreach ($barangBaru as $barang) {
        $kelompokKode = substr($barang->kode, 0, 10); // Dapatkan 10 digit pertama sebagai kelompok kode
        $kodeBarang = substr($barang->kode, 10); // Dapatkan 6 digit terakhir sebagai kode barang

        if (isset($kelompokKodeBaris[$kelompokKode])) {
            // Tentukan posisi baris berdasarkan urutan kode barang
            $barisTujuan = $kelompokKodeBaris[$kelompokKode]['last_row'] + 1;
            foreach ($kelompokKodeBaris[$kelompokKode] as $existingKode => $baris) {
                if (is_numeric($existingKode) && $kodeBarang < $existingKode) {
                    $barisTujuan = $baris;
                    break;
                }
            }

            // Sisipkan barang baru di posisi yang sesuai
            $sheet->insertNewRowBefore($barisTujuan, 1);
            $sheet->setCellValue('B' . $barisTujuan, $kodeBarang); // Masukkan 6 digit terakhir sebagai kode barang
            $sheet->setCellValue('C' . $barisTujuan, $barang->nama); // Masukkan nama barang
            $sheet->setCellValue('E' . $barisTujuan, 0); // Set kolom E ke 0
            $sheet->setCellValue('J' . $barisTujuan, 0); // Set kolom J ke 0

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
            $sheet->setCellValue('D' . $barisTujuan, $stokAwal); // Stok awal pada kolom D
            $sheet->setCellValue('F' . $barisTujuan, $totalPemasukan); // Pemasukan pada kolom F
            $sheet->setCellValue('G' . $barisTujuan, -$totalPengeluaran); // Pengeluaran pada kolom G (negatif)
            $sheet->setCellValue('H' . $barisTujuan, "=F" . $barisTujuan . "+G" . $barisTujuan); // Rumus untuk kolom H
            $sheet->setCellValue('I' . $barisTujuan, $stokAkhir); // Stok akhir pada kolom I

            // Perbarui posisi kelompok kode
            $kelompokKodeBaris[$kelompokKode]['last_row']++;
            $kelompokKodeBaris[$kelompokKode][$kodeBarang] = $barisTujuan;
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
