<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function sale()
    {
        return $this->belongsTo(\App\Models\Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
