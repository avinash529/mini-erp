<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('inventory.index', compact('products'));
    }
}