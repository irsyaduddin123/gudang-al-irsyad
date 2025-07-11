<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('permintaan_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah'); // jumlah barang yang keluar
            $table->date('tanggal_keluar');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('permintaan_id')->references('id')->on('permintaan')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
