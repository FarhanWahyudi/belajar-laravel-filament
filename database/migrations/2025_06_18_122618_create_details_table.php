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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('faktur_id')->constrained('fakturs', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('diskon');
            $table->string('nama_barang');
            $table->bigInteger('harga');
            $table->bigInteger('subtotal');
            $table->integer('qty');
            $table->integer('hasil_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
