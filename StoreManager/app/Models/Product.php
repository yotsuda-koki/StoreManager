<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function inventory()
    {
        return $this->hasOne(\App\Models\Inventory::class);
    }

    public function sale_item()
    {
        return $this->hasOne(\App\Models\SaleItem::class);
    }
}
