<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $activeProducts = Product::where('is_active', true)->count();

        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->count();

        $totalExpenditure = PurchaseOrder::where('status', 'RECEIVED')
            ->sum('total_amount');

        $latestPurchaseOrders = PurchaseOrder::with('supplier')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'activeProducts',
            'lowStockCount',
            'totalExpenditure',
            'latestPurchaseOrders'
        ));
    }
}