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
        Schema::create('rop_eoqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->double('lead_time'); // waktu tunggu barang datang (dalam hari)
            $table->double('pemakaian_rata'); // per hari
            $table->double('biaya_pesan'); //ongkir bisa
            $table->double('biaya_simpan'); //per periode (30 hari)
            $table->double('rop');
            $table->double('eoq');
            $table->integer('total')->nullable();
            $table->integer('hari')->nullable();
            $table->integer('safety_stok')->nullable();
            $table->string('bulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rop_eoqs');
    }
};
