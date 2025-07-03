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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->integer('harga_beli');
            $table->integer('stok');
            $table->string('satuan');
            $table->unsignedBigInteger('safetystok_id');
            $table->timestamps();

            $table->foreign('safetystok_id')->references('id')->on('safetystoks')
            ->onDelete('cascade')
            ->onUpdate('cascade')
            ;


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
