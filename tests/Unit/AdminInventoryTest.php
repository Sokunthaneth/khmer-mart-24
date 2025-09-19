<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_inventory(): void
    {
        // Create admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Create product
        $product = Product::factory()->create(['stock' => 1]);

        // Act as admin and update inventory
        $response = $this->actingAs($admin)
            ->patchJson("/api/admin/products/{$product->id}/inventory", ['stock' => 3]);

        // Assert response
        $response->assertOk()
            ->assertJsonPath('stock', 3)
            ->assertJsonPath('ok', true);

        // Assert database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3
        ]);
    }

    public function test_non_admin_cannot_update_inventory(): void
    {
        // Create regular user
        $user = User::factory()->create(['is_admin' => false]);

        // Create product
        $product = Product::factory()->create(['stock' => 1]);

        // Act as regular user and try to update inventory
        $response = $this->actingAs($user)
            ->patchJson("/api/admin/products/{$product->id}/inventory", ['stock' => 3]);

        // Assert forbidden
        $response->assertForbidden();

        // Assert database unchanged
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1
        ]);
    }

    public function test_unauthenticated_user_cannot_update_inventory(): void
    {
        // Create product
        $product = Product::factory()->create(['stock' => 1]);

        // Try to update inventory without authentication
        $response = $this->patchJson("/api/admin/products/{$product->id}/inventory", ['stock' => 3]);

        // Assert unauthorized
        $response->assertUnauthorized();
    }

    public function test_admin_can_bulk_update_inventory(): void
    {
        // Create admin user
        $admin = User::factory()->create(['is_admin' => true]);

        // Create products
        $product1 = Product::factory()->create(['stock' => 1]);
        $product2 = Product::factory()->create(['stock' => 2]);

        // Bulk update data
        $updates = [
            ['product_id' => $product1->id, 'stock' => 10],
            ['product_id' => $product2->id, 'stock' => 20],
        ];

        // Act as admin and bulk update
        $response = $this->actingAs($admin)
            ->patchJson('/api/admin/products/bulk-inventory', ['updates' => $updates]);

        // Assert response
        $response->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonStructure([
                'ok',
                'updated_products',
                'message'
            ]);

        // Assert database updates
        $this->assertDatabaseHas('products', ['id' => $product1->id, 'stock' => 10]);
        $this->assertDatabaseHas('products', ['id' => $product2->id, 'stock' => 20]);
    }
}
