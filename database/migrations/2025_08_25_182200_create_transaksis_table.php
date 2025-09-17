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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->foreignId('kategori_transaksi_id');
            $table->string('bukti')->nullable();
            $table->foreignId('created_by');
            $table->foreignId('updated_by');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('tipe', ['penerimaan', 'pengeluaran', 'penyesuaian', 'piutang']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
