<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'product_id',
    ];
    /**
     * キャンセルできるのは発注から2日後まで
     * @return bool
     */
    public function isCancelable()
    {
        $orderDate = Carbon::parse($this->created_at);
        return $orderDate->addDays(2)->isFuture();
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
