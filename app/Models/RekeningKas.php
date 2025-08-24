<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningKas extends Model
{
    /** @use HasFactory<\Database\Factories\RekeningKasFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
