<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Supplier $supplier;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->supplier = Supplier::create([
            'name' => 'Test Supplier',
            'email' => 'supplier@test.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        $this->product = Product::create([
            'sku' => 'TEST001',
            'name' => 'Test Product',
            'unit_price' => 100,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
            'is_active' => true,
        ]);
    }

    public function test_purchase_order_total_is_calculated_correctly(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/purchase-orders', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                    'unit_price' => 100,
                ],
            ],
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.total_amount', '500.00');

        $this->assertDatabaseHas('purchase_order_items', [
            'product_id' => $this->product->id,
            'quantity' => 5,
            'unit_price' => 100,
            'subtotal' => 500,
        ]);
    }

    public function test_empty_items_are_rejected(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/purchase-orders', [
            'supplier_id' => $this->supplier->id,
            'items' => [],
        ]);

        $response->assertStatus(422);
    }

    public function test_negative_quantity_is_rejected(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/purchase-orders', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => -5,
                    'unit_price' => 100,
                ],
            ],
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthorized_user_cannot_create_purchase_order(): void
    {
        $response = $this->postJson('/api/purchase-orders', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                    'unit_price' => 100,
                ],
            ],
        ]);

        $response->assertStatus(401);
    }

    public function test_receiving_purchase_order_increases_stock(): void
    {
        $this->actingAs($this->user);

        $createResponse = $this->postJson('/api/purchase-orders', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                    'unit_price' => 100,
                ],
            ],
        ]);

        $poId = $createResponse->json('data.id');

        $this->patchJson(
            "/api/purchase-orders/{$poId}/status",
            ['status' => 'APPROVED']
        )->assertSuccessful();

        $this->patchJson(
            "/api/purchase-orders/{$poId}/status",
            ['status' => 'RECEIVED']
        )->assertSuccessful();

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock_quantity' => 15,
        ]);
    }

    public function test_received_purchase_order_cannot_be_received_again(): void
    {
        $this->actingAs($this->user);

        $po = PurchaseOrder::create([
            'supplier_id' => $this->supplier->id,
            'status' => 'APPROVED',
            'total_amount' => 500,
        ]);

        $po->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 5,
            'unit_price' => 100,
            'subtotal' => 500,
        ]);

        $this->patchJson(
            "/api/purchase-orders/{$po->id}/status",
            ['status' => 'RECEIVED']
        )->assertSuccessful();

        $this->patchJson(
            "/api/purchase-orders/{$po->id}/status",
            ['status' => 'RECEIVED']
        )->assertStatus(422);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock_quantity' => 15,
        ]);
    }

    public function test_draft_purchase_order_can_be_cancelled(): void
    {
        $this->actingAs($this->user);

        $po = PurchaseOrder::create([
            'supplier_id' => $this->supplier->id,
            'status' => 'DRAFT',
            'total_amount' => 100,
        ]);

        $this->patchJson(
            "/api/purchase-orders/{$po->id}/status",
            ['status' => 'CANCELLED']
        )->assertSuccessful();

        $this->assertDatabaseHas('purchase_orders', [
            'id' => $po->id,
            'status' => 'CANCELLED',
        ]);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $this->actingAs($this->user);

        $po = PurchaseOrder::create([
            'supplier_id' => $this->supplier->id,
            'status' => 'DRAFT',
            'total_amount' => 100,
        ]);

        $this->patchJson(
            "/api/purchase-orders/{$po->id}/status",
            ['status' => 'RECEIVED']
        )->assertStatus(422);
    }
}