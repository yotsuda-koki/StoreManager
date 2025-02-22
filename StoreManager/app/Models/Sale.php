<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function sale_items()
    {
        return $this->hasMany(\App\Models\SaleItem::class);
    }
}
