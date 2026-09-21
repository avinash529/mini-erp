<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function supplierSpend(): JsonResponse
    {
        $report = PurchaseOrder::query()
            ->join('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
            ->where('purchase_orders.status', 'RECEIVED')
            ->selectRaw('
                suppliers.id as supplier_id,
                suppliers.name as supplier_name,
                COUNT(purchase_orders.id) as purchase_order_count,
                SUM(purchase_orders.total_amount) as total_spend
            ')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_spend')
            ->get();

        return response()->json([
            'data' => $report,
        ]);
    }
}