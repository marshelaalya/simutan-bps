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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id(); // kolom id otomatis
            $table->string('kode');
            $table->integer('kelompok_id');
            $table->string('nama')->nullable(); // kolom nama
            $table->integer('qty_item')->nullable(); // kolom qty_item
            $table->enum('satuan', ['buah', 'set', 'kotak', 'rim', 'pad', 'tube']); // kolom satuan
            $table->timestamps(); // kolom timestamps (created_at dan updated_at)
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barangs');
    }
};
