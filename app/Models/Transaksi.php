<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    /** @use HasFactory<\Database\Factories\TransaksiFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function getFormatTanggalAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    public function getFormatRupiahAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }


    public function kategoriTransaksi(): BelongsTo{
        return $this->belongsTo(KategoriTransaksi::class);
    }

    public function jurnalDetail(): HasMany{
        return $this->hasMany(JurnalDetail::class);
    }
    
    public function createdBy(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo{
        return $this->belongsTo(User::class, 'updated_by');
    }
}
