<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderStatusRequest;
use App\Models\PurchaseOrder;
use App\Services\PurchaseOrderService;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService
    ) {
    }

    /**
     * Store a new purchase order as DRAFT.
     */
    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Purchase order created successfully.',
            'data' => $purchaseOrder->load('supplier', 'items.product'),
        ], 201);
    }

    /**
     * Update purchase order status.
     */
    public function updateStatus(
        UpdatePurchaseOrderStatusRequest $request,
        PurchaseOrder $purchaseOrder
    ): JsonResponse {
        try {
            $purchaseOrder = $this->purchaseOrderService->updateStatus(
                $purchaseOrder->id,
                $request->validated('status')
            );

            return response()->json([
                'message' => 'Purchase order status updated successfully.',
                'data' => $purchaseOrder->load('supplier', 'items.product'),
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}