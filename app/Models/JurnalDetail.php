<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalDetail extends Model
{
    /** @use HasFactory<\Database\Factories\JurnalDetailFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function kategoriTransaksi(): BelongsTo{
        return $this->belongsTo(KategoriTransaksi::class);
    }

    public function transaksi(): BelongsTo{
        return $this->belongsTo(Transaksi::class);
    }
}
