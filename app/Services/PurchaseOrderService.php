<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function create(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {

            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'status' => 'DRAFT',
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {

                $subtotal = $item['quantity'] * $item['unit_price'];

                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $purchaseOrder->update([
                'total_amount' => $total,
            ]);

            return $purchaseOrder->load('items');
        });
    }

    public function updateStatus(
        int $purchaseOrderId,
        string $newStatus
    ): PurchaseOrder {
        return DB::transaction(function () use ($purchaseOrderId, $newStatus) {

            $purchaseOrder = PurchaseOrder::query()
                ->lockForUpdate()
                ->findOrFail($purchaseOrderId);

            if (in_array($purchaseOrder->status, ['RECEIVED', 'CANCELLED'])) {
                throw new \DomainException(
                    'A received or cancelled purchase order cannot be modified.'
                );
            }

            if (
                $purchaseOrder->status === 'DRAFT' &&
                $newStatus === 'APPROVED'
            ) {
                $purchaseOrder->update([
                    'status' => 'APPROVED',
                ]);

                return $purchaseOrder->fresh();
            }

            if (
                $purchaseOrder->status === 'DRAFT' &&
                $newStatus === 'CANCELLED'
            ) {
                $purchaseOrder->update([
                    'status' => 'CANCELLED',
                ]);

                return $purchaseOrder->fresh();
            }

            if (
                $purchaseOrder->status === 'APPROVED' &&
                $newStatus === 'RECEIVED'
            ) {
                $purchaseOrder->load('items');

                foreach ($purchaseOrder->items as $item) {

                    $product = $item->product()
                        ->lockForUpdate()
                        ->first();

                    $product->increment(
                        'stock_quantity',
                        $item->quantity
                    );
                }

                $purchaseOrder->update([
                    'status' => 'RECEIVED',
                ]);

                return $purchaseOrder->fresh();
            }

            throw new \DomainException(
                "Invalid status transition from {$purchaseOrder->status} to {$newStatus}."
            );
        });
    }
}
