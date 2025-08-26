<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiBank extends Model
{
    /** @use HasFactory<\Database\Factories\MutasiBankFactory> */
    use HasFactory;

    protected $fillable = [
        'tanggal', 'deskripsi', 'nominal', 'tipe', 'saldo_setelah',
        'status_rekon', 'transaksi_id'
    ];

    public function trasakasi(): BelongsTo{
        return $this->belongsTo(Transaksi::class);
    }
}
