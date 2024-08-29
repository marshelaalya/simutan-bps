<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path ke file CSV
        $filePath = storage_path('app/public/pegawai.csv');

        // Baca CSV
        $data = array_map('str_getcsv', file($filePath));
        $header = array_shift($data); // Ambil header

        foreach ($data as $row) {
            $rowData = array_combine($header, $row);

            // Simpan data ke database
            User::create([
                'name' => $rowData['name'],
                'panggilan' => $rowData['panggilan'],
                'role' => $rowData['role'],
                'username' => $rowData['username'],
                'password' => Hash::make($rowData['password']), // Hash password
                'ttd' => $rowData['ttd'],
                'foto' => $rowData['foto'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
