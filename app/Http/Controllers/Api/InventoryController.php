<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->where('is_active', true)
            ->select([
                'id',
                'sku',
                'name',
                'unit_price',
                'stock_quantity',
                'low_stock_threshold',
            ])
            ->orderBy('name')
            ->paginate(10);

        return response()->json($products);
    }

    public function lowStock()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->select([
                'id',
                'sku',
                'name',
                'unit_price',
                'stock_quantity',
                'low_stock_threshold',
            ])
            ->orderBy('stock_quantity')
            ->get();

        return response()->json($products);
    }
}