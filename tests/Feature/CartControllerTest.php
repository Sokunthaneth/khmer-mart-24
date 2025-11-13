<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_add_product_to_cart()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product with SKU
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $productSku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'price' => '25.99',
            'quantity' => 10,
        ]);

        // Test adding product to cart
        $response = $this->postJson("/api/cart/add/{$product->id}", [
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product added to cart successfully',
        ]);

        $response->assertJsonStructure([
            'message',
            'product' => [
                'id',
                'name',
                'quantity_in_cart',
                'price',
            ],
            'cart_item_count',
        ]);

        // Check if cart was actually updated in session
        $cart = session('cart', []);
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(2, $cart[$product->id]);
    }

    public function test_view_cart_items()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product with SKU
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $productSku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'price' => '25.99',
            'quantity' => 10,
        ]);

        // Add product to cart via session
        session(['cart' => [$product->id => 2]]);

        // Test viewing cart
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'items' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'quantity',
                    'subtotal',
                ],
            ],
            'total',
        ]);
    }

    public function test_remove_product_from_cart()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        // Add product to cart via session
        session(['cart' => [$product->id => 2]]);

        // Test removing product from cart completely (no quantity specified)
        $response = $this->postJson("/api/cart/remove/{$product->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product completely removed from cart',
        ]);

        // Check if product was removed from session
        $cart = session('cart', []);
        $this->assertArrayNotHasKey($product->id, $cart);
    }

    public function test_remove_specific_quantity_from_cart()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        // Add product to cart via session (5 items)
        session(['cart' => [$product->id => 5]]);

        // Test removing 2 items from cart
        $response = $this->postJson("/api/cart/remove/{$product->id}", [
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Removed 2 item(s) from cart. 3 remaining',
            'remaining_quantity' => 3,
        ]);

        // Check if cart was updated correctly
        $cart = session('cart', []);
        $this->assertEquals(3, $cart[$product->id]);
    }

    public function test_remove_more_than_available_quantity_removes_all()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        // Add product to cart via session (3 items)
        session(['cart' => [$product->id => 3]]);

        // Test removing 5 items (more than available)
        $response = $this->postJson("/api/cart/remove/{$product->id}", [
            'quantity' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product completely removed from cart',
            'remaining_quantity' => 0,
        ]);

        // Check if product was completely removed
        $cart = session('cart', []);
        $this->assertArrayNotHasKey($product->id, $cart);
    }

    public function test_cannot_add_more_than_available_stock()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product with limited stock
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $productSku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'price' => '25.99',
            'quantity' => 5, // Only 5 items in stock
        ]);

        // Try to add more than available stock
        $response = $this->postJson("/api/cart/add/{$product->id}", [
            'quantity' => 10, // Requesting 10 but only 5 available
        ]);

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'message' => 'Not enough stock available. Available: 5, Requested: 10',
        ]);

        // Check that nothing was added to cart
        $cart = session('cart', []);
        $this->assertArrayNotHasKey($product->id, $cart);
    }

    public function test_cart_index_shows_correct_price_and_stock_info()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create a product with SKU
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $productSku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'price' => '25.99',
            'quantity' => 10,
        ]);

        // Add product to cart via session
        session(['cart' => [$product->id => 2]]);

        // Test viewing cart
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'items' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'quantity',
                    'subtotal',
                    'available_stock',
                ],
            ],
            'total',
        ]);

        // Verify the price and stock information
        $cartData = $response->json();
        $this->assertEquals(25.99, $cartData['items'][0]['price']);
        $this->assertEquals(10, $cartData['items'][0]['available_stock']);
        $this->assertEquals(51.98, $cartData['items'][0]['subtotal']); // 25.99 * 2
        $this->assertEquals(51.98, $cartData['total']);
    }

    public function test_get_cart_count()
    {
        // Create a category and products
        $category = Category::factory()->create();

        $product1 = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);

        ProductSku::factory()->create(['product_id' => $product1->id]);
        ProductSku::factory()->create(['product_id' => $product2->id]);

        // Add products to cart via session
        session(['cart' => [
            $product1->id => 2,
            $product2->id => 3,
        ]]);

        // Test getting cart count
        $response = $this->getJson('/api/cart/count');

        $response->assertStatus(200);
        $response->assertJson([
            'count' => 5, // 2 + 3
        ]);
    }

    public function test_clear_cart()
    {
        // Create a category and product
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        ProductSku::factory()->create(['product_id' => $product->id]);

        // Add product to cart via session
        session(['cart' => [$product->id => 2]]);

        // Test clearing cart
        $response = $this->postJson('/api/cart/clear');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Cart cleared successfully',
        ]);

        // Check that cart is empty
        $cart = session('cart', []);
        $this->assertEmpty($cart);
    }

    public function test_update_cart_item_quantity()
    {
        // Create a category and product
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $productSku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'price' => '25.99',
            'quantity' => 10,
        ]);

        // Add product to cart via session
        session(['cart' => [$product->id => 2]]);

        // Test updating cart item quantity
        $response = $this->patchJson("/api/cart/update/{$product->id}", [
            'quantity' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Cart updated successfully',
        ]);

        $response->assertJsonStructure([
            'message',
            'product' => [
                'id',
                'name',
                'quantity_in_cart',
                'price',
            ],
            'cart_item_count',
        ]);

        // Check that cart was updated
        $cart = session('cart', []);
        $this->assertEquals(5, $cart[$product->id]);
    }

    public function test_update_cart_item_with_insufficient_stock()
    {
        // Create a category and product with limited stock
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $productSku = ProductSku::factory()->create([
            'product_id' => $product->id,
            'price' => '25.99',
            'quantity' => 3, // Only 3 in stock
        ]);

        // Add product to cart via session
        session(['cart' => [$product->id => 2]]);

        // Test updating to more than available stock
        $response = $this->patchJson("/api/cart/update/{$product->id}", [
            'quantity' => 5, // Requesting 5 but only 3 available
        ]);

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'message' => 'Not enough stock available. Available: 3, Requested: 5',
        ]);

        // Check that cart was not updated
        $cart = session('cart', []);
        $this->assertEquals(2, $cart[$product->id]); // Should remain unchanged
    }
}
