<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderStatusRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\PurchaseOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService
    ) {
    }

    public function create(): View
    {
        return view('purchase-orders.create', [
            'suppliers' => Supplier::orderBy('name')->get(),
            'products' => Product::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StorePurchaseOrderRequest $request): RedirectResponse
    {
        $purchaseOrder = $this->purchaseOrderService->create(
            $request->validated()
        );

        return redirect()
            ->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load([
            'supplier',
            'items.product',
        ]);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function updateStatus(
        UpdatePurchaseOrderStatusRequest $request,
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {
        try {
            $this->purchaseOrderService->updateStatus(
                $purchaseOrder->id,
                $request->validated('status')
            );

            return back()->with(
                'success',
                'Purchase order status updated successfully.'
            );
        } catch (\DomainException $e) {
            return back()->withErrors([
                'status' => $e->getMessage(),
            ]);
        }
    }
}