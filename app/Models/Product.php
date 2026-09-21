<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'unit_price',
        'stock_quantity',
        'low_stock_threshold',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'is_active' => 'boolean',
    ];

    public function purchaseOrderItems()
    {

        return $this->hasMany(PurchaseOrderItem::class);

    }
}
