<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KategoriTransaksi extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriTransaksiFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function tipeTransaksi(): BelongsTo{
        return $this->belongsTo(TipeTransaksi::class);
    }
}
