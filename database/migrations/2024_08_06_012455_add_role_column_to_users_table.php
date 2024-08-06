<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' dengan tipe enum dan nilai default
            $table->enum('role', ['admin', 'supervisor', 'pegawai'])->default('pegawai')->after('name'); // Ganti 'existing_column' dengan nama kolom yang ada setelahnya jika diperlukan
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom 'role'
            $table->dropColumn('role');
        });
    }
};
