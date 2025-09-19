<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create categories and products for testing
        $category = Category::factory()->create();
        $this->product1 = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'price' => 99.99
        ]);
        $this->product2 = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 5,
            'price' => 49.99
        ]);
    }

    public function test_user_can_create_order_with_valid_cart(): void
    {
        $user = User::factory()->create();

        $cartData = [
            'cart' => [
                ['product_id' => $this->product1->id, 'quantity' => 2],
                ['product_id' => $this->product2->id, 'quantity' => 1],
            ],
            'shipping_address' => '123 Main St, City, Country',
            'billing_address' => '123 Main St, City, Country',
            'payment_method' => 'credit_card'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/orders', $cartData);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'order_id',
                'order_number'
            ]);

        // Assert order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => (99.99 * 2) + (49.99 * 1)
        ]);

        // Assert stock was decremented
        $this->assertDatabaseHas('products', [
            'id' => $this->product1->id,
            'stock' => 8 // 10 - 2
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $this->product2->id,
            'stock' => 4 // 5 - 1
        ]);
    }

    public function test_order_creation_fails_with_insufficient_stock(): void
    {
        $user = User::factory()->create();

        $cartData = [
            'cart' => [
                ['product_id' => $this->product1->id, 'quantity' => 15], // More than available
            ],
            'shipping_address' => '123 Main St, City, Country',
            'billing_address' => '123 Main St, City, Country',
            'payment_method' => 'credit_card'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/orders', $cartData);

        $response->assertStatus(400)
            ->assertJsonPath('success', false);

        // Assert no order was created
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id
        ]);

        // Assert stock unchanged
        $this->assertDatabaseHas('products', [
            'id' => $this->product1->id,
            'stock' => 10
        ]);
    }

    public function test_user_can_cancel_pending_order(): void
    {
        $user = User::factory()->create();

        // Create an order first
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => 99.99
        ]);

        // Create order items
        $order->orderItems()->create([
            'product_id' => $this->product1->id,
            'quantity' => 2,
            'price' => $this->product1->price
        ]);

        // Decrement stock to simulate order creation
        $this->product1->update(['stock' => 8]);

        $response = $this->actingAs($user)
            ->patchJson("/api/orders/{$order->id}/cancel");

        $response->assertOk()
            ->assertJsonPath('success', true);

        // Assert order status updated
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled'
        ]);

        // Assert stock restored
        $this->assertDatabaseHas('products', [
            'id' => $this->product1->id,
            'stock' => 10 // Back to original
        ]);
    }

    public function test_user_cannot_cancel_non_pending_order(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'shipped'
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/orders/{$order->id}/cancel");

        $response->assertStatus(400)
            ->assertJsonPath('success', false);

        // Assert order status unchanged
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped'
        ]);
    }

    public function test_user_cannot_access_other_users_orders(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)
            ->getJson("/api/orders/{$order->id}");

        $response->assertForbidden();
    }

    public function test_health_check_endpoint(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'timestamp',
                'environment',
                'version'
            ])
            ->assertJsonPath('status', 'ok');
    }
}
