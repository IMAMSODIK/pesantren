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
        Schema::create('mutasi_banks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->enum('tipe', ['debit', 'kredit']);
            $table->decimal('saldo_setelah', 15, 2)->nullable();
            $table->enum('status_rekon', ['pending', 'matched', 'unmatched'])->default('pending');
            $table->foreignId('transaksi_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_banks');
    }
};
