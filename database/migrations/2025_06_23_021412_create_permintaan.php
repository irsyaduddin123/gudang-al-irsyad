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
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id'); // foreign key ke tabel pengguna
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            // $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'butuh_validasi_manager'])->default('menunggu');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan');
    }
};
