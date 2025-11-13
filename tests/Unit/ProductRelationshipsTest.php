<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductSku;
use App\Models\Wishlist;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Cart;
use App\Models\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductRelationshipsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function product_belongs_to_category()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
        $this->assertEquals('Electronics', $product->category->name);
    }

    /** @test */
    public function product_has_many_product_skus()
    {
        $product = Product::factory()->create();

        ProductSku::factory()->count(3)->create([
            'product_id' => $product->id,
            'sku' => $this->faker->unique()->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->numberBetween(0, 100)
        ]);

        $this->assertCount(3, $product->productSkus);
        $this->assertInstanceOf(ProductSku::class, $product->productSkus->first());

        // Verify all SKUs belong to this product
        $product->productSkus->each(function ($sku) use ($product) {
            $this->assertEquals($product->id, $sku->product_id);
        });
    }

    /** @test */
    public function product_has_many_wishlists()
    {
        $product = Product::factory()->create();
        $users = User::factory()->count(2)->create();

        foreach ($users as $user) {
            Wishlist::factory()->create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
        }

        $this->assertCount(2, $product->wishlists);
        $this->assertInstanceOf(Wishlist::class, $product->wishlists->first());

        // Verify all wishlists belong to this product
        $product->wishlists->each(function ($wishlist) use ($product) {
            $this->assertEquals($product->id, $wishlist->product_id);
        });
    }

    /** @test */
    public function product_has_many_cart_items()
    {
        $product = Product::factory()->create();
        $users = User::factory()->count(2)->create();

        foreach ($users as $user) {
            $cart = Cart::factory()->create(['user_id' => $user->id]);
            CartItem::factory()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 5)
            ]);
        }

        $this->assertCount(2, $product->cartItems);
        $this->assertInstanceOf(CartItem::class, $product->cartItems->first());

        // Verify all cart items belong to this product
        $product->cartItems->each(function ($cartItem) use ($product) {
            $this->assertEquals($product->id, $cartItem->product_id);
        });
    }

    /** @test */
    public function product_has_many_order_items()
    {
        $product = Product::factory()->create();
        $users = User::factory()->count(2)->create();

        foreach ($users as $user) {
            $orderDetail = OrderDetail::factory()->create(['user_id' => $user->id]);
            OrderItem::factory()->create([
                'order_id' => $orderDetail->id,
                'product_id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 3)
            ]);
        }

        $this->assertCount(2, $product->orderItems);
        $this->assertInstanceOf(OrderItem::class, $product->orderItems->first());

        // Verify all order items belong to this product
        $product->orderItems->each(function ($orderItem) use ($product) {
            $this->assertEquals($product->id, $orderItem->product_id);
        });
    }

    /** @test */
    public function product_can_be_loaded_with_all_relationships()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);

        // Create related records
        ProductSku::factory()->create(['product_id' => $product->id]);
        $user = User::factory()->create();
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);

        $orderDetail = OrderDetail::factory()->create(['user_id' => $user->id]);
        OrderItem::factory()->create([
            'order_id' => $orderDetail->id,
            'product_id' => $product->id
        ]);

        // Load product with all relationships
        $loadedProduct = Product::with([
            'category',
            'productSkus',
            'wishlists',
            'cartItems',
            'orderItems'
        ])->find($product->id);

        $this->assertTrue($loadedProduct->relationLoaded('category'));
        $this->assertTrue($loadedProduct->relationLoaded('productSkus'));
        $this->assertTrue($loadedProduct->relationLoaded('wishlists'));
        $this->assertTrue($loadedProduct->relationLoaded('cartItems'));
        $this->assertTrue($loadedProduct->relationLoaded('orderItems'));

        $this->assertInstanceOf(Category::class, $loadedProduct->category);
        $this->assertEquals(1, $loadedProduct->productSkus->count());
        $this->assertEquals(1, $loadedProduct->wishlists->count());
        $this->assertEquals(1, $loadedProduct->cartItems->count());
        $this->assertEquals(1, $loadedProduct->orderItems->count());
    }

    /** @test */
    public function product_relationship_queries_work_correctly()
    {
        $category = Category::factory()->create(['name' => 'Test Category']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        // Test querying products through category relationship
        $categoryProducts = $category->products;
        $this->assertCount(1, $categoryProducts);
        $this->assertEquals($product->id, $categoryProducts->first()->id);

        // Test querying category through product relationship
        $productCategory = $product->category;
        $this->assertEquals($category->id, $productCategory->id);
        $this->assertEquals('Test Category', $productCategory->name);
    }

    /** @test */
    public function product_can_have_multiple_users_in_wishlist()
    {
        $product = Product::factory()->create();
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            Wishlist::factory()->create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
        }

        $this->assertCount(5, $product->wishlists);

        // Each wishlist should have a different user
        $userIds = $product->wishlists->pluck('user_id')->toArray();
        $this->assertCount(5, array_unique($userIds));
    }

    /** @test */
    public function product_can_be_in_multiple_carts()
    {
        $product = Product::factory()->create();
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            $cart = Cart::factory()->create(['user_id' => $user->id]);
            CartItem::factory()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 5)
            ]);
        }

        $this->assertCount(3, $product->cartItems);

        // Each cart item should belong to a different cart
        $cartIds = $product->cartItems->pluck('cart_id')->toArray();
        $this->assertCount(3, array_unique($cartIds));
    }

    /** @test */
    public function product_can_be_in_multiple_orders()
    {
        $product = Product::factory()->create();
        $users = User::factory()->count(4)->create();

        foreach ($users as $user) {
            $orderDetail = OrderDetail::factory()->create(['user_id' => $user->id]);
            OrderItem::factory()->create([
                'order_id' => $orderDetail->id,
                'product_id' => $product->id,
                'quantity' => $this->faker->numberBetween(1, 3)
            ]);
        }

        $this->assertCount(4, $product->orderItems);

        // Each order item should belong to a different order
        $orderIds = $product->orderItems->pluck('order_id')->toArray();
        $this->assertCount(4, array_unique($orderIds));
    }

    /** @test */
    public function product_without_relationships_returns_empty_collections()
    {
        $product = Product::factory()->create();

        $this->assertCount(0, $product->productSkus);
        $this->assertCount(0, $product->wishlists);
        $this->assertCount(0, $product->cartItems);
        $this->assertCount(0, $product->orderItems);
    }
}
