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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product description',
            'summary' => 'Test product summary',
            'cover' => 'https://example.com/image.jpg',
            'category_id' => $category->id,
        ];

        $product = Product::createProduct($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productData['name'], $product->name);
        $this->assertEquals($productData['description'], $product->description);
        $this->assertEquals($productData['summary'], $product->summary);
        $this->assertEquals($productData['cover'], $product->cover);
        $this->assertEquals($productData['category_id'], $product->category_id);
        $this->assertDatabaseHas('products', $productData);
    }

    /** @test */
    public function it_can_create_a_product_with_minimal_data()
    {
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Minimal Product',
            'category_id' => $category->id,
        ];

        $product = Product::createProduct($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productData['name'], $product->name);
        $this->assertEquals($productData['category_id'], $product->category_id);
        $this->assertNull($product->description);
        $this->assertNull($product->summary);
        $this->assertNull($product->cover);
    }

    /** @test */
    public function it_can_read_a_product_by_id()
    {
        $product = Product::factory()->create();

        $foundProduct = Product::getProductById($product->id);

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($product->id, $foundProduct->id);
        $this->assertEquals($product->name, $foundProduct->name);
    }

    /** @test */
    public function it_returns_null_for_non_existent_product()
    {
        $product = Product::getProductById(999);

        $this->assertNull($product);
    }

    /** @test */
    public function it_can_get_all_products()
    {
        $productsCount = 3;
        Product::factory()->count($productsCount)->create();

        $products = Product::getAllProducts();

        $this->assertCount($productsCount, $products);
        $this->assertInstanceOf(Product::class, $products->first());
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated description',
            'summary' => 'Updated summary',
            'cover' => 'https://example.com/updated-image.jpg',
            'category_id' => $category->id,
        ];

        $result = $product->updateProduct($updateData);

        $this->assertTrue($result);
        $product->refresh();
        $this->assertEquals($updateData['name'], $product->name);
        $this->assertEquals($updateData['description'], $product->description);
        $this->assertEquals($updateData['summary'], $product->summary);
        $this->assertEquals($updateData['cover'], $product->cover);
        $this->assertEquals($updateData['category_id'], $product->category_id);
    }

    /** @test */
    public function it_can_partially_update_a_product()
    {
        $product = Product::factory()->create([
            'name' => 'Original Name',
            'description' => 'Original Description'
        ]);

        $updateData = [
            'name' => 'Updated Name Only',
        ];

        $result = $product->updateProduct($updateData);

        $this->assertTrue($result);
        $product->refresh();
        $this->assertEquals($updateData['name'], $product->name);
        $this->assertEquals('Original Description', $product->description);
    }

    /** @test */
    public function it_can_soft_delete_a_product()
    {
        $product = Product::factory()->create();

        $result = $product->deleteProduct();

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNotNull($product->fresh()->deleted_at);
    }

    /** @test */
    public function it_can_restore_a_soft_deleted_product()
    {
        $product = Product::factory()->create();
        $product->deleteProduct();

        $product->restore();

        $this->assertNull($product->fresh()->deleted_at);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function deleted_products_are_not_included_in_regular_queries()
    {
        Product::factory()->count(2)->create();
        $deletedProduct = Product::factory()->create();
        $deletedProduct->deleteProduct();

        $products = Product::getAllProducts();

        $this->assertCount(2, $products);
        $this->assertFalse($products->contains('id', $deletedProduct->id));
    }

    /** @test */
    public function it_can_search_products_by_name()
    {
        Product::factory()->create(['name' => 'Apple iPhone']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);
        Product::factory()->create(['name' => 'Apple iPad']);

        $results = Product::searchProducts('Apple');

        $this->assertCount(2, $results);
        $this->assertTrue($results->every(function ($product) {
            return str_contains($product->name, 'Apple');
        }));
    }

    /** @test */
    public function it_can_search_products_by_description()
    {
        Product::factory()->create([
            'name' => 'Phone',
            'description' => 'Latest smartphone with advanced features'
        ]);
        Product::factory()->create([
            'name' => 'Laptop',
            'description' => 'Gaming laptop with high performance'
        ]);

        $results = Product::searchProducts('smartphone');

        $this->assertCount(1, $results);
        $this->assertEquals('Phone', $results->first()->name);
    }

    /** @test */
    public function search_returns_empty_collection_when_no_matches()
    {
        Product::factory()->create(['name' => 'Product 1']);
        Product::factory()->create(['name' => 'Product 2']);

        $results = Product::searchProducts('NonExistentProduct');

        $this->assertCount(0, $results);
        $this->assertTrue($results->isEmpty());
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    /** @test */
    public function it_can_get_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(2)->create(['category_id' => $category1->id]);
        Product::factory()->create(['category_id' => $category2->id]);

        $products = Product::getProductsByCategory($category1->id);

        $this->assertCount(2, $products);
        $this->assertTrue($products->every(function ($product) use ($category1) {
            return $product->category_id == $category1->id;
        }));
    }

    /** @test */
    public function it_can_get_products_with_category_relationship()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        Product::factory()->create(['category_id' => $category->id]);

        $products = Product::getProductsWithCategory();

        $this->assertCount(1, $products);
        $this->assertTrue($products->first()->relationLoaded('category'));
        $this->assertEquals('Electronics', $products->first()->category->name);
    }

    /** @test */
    public function it_has_many_product_skus()
    {
        $product = Product::factory()->create();

        // Create some ProductSkus for this product
        ProductSku::factory()->count(3)->create(['product_id' => $product->id]);

        $this->assertCount(3, $product->productSkus);
        $this->assertInstanceOf(ProductSku::class, $product->productSkus->first());
    }

    /** @test */
    public function it_can_get_products_with_skus_relationship()
    {
        $product = Product::factory()->create();
        ProductSku::factory()->count(2)->create(['product_id' => $product->id]);

        $products = Product::getProductsWithSkus();

        $this->assertCount(1, $products);
        $this->assertTrue($products->first()->relationLoaded('productSkus'));
        $this->assertEquals(2, $products->first()->productSkus->count());
    }

    /** @test */
    public function product_has_fillable_attributes()
    {
        $product = new Product();
        $expectedFillable = [
            'name',
            'description',
            'summary',
            'cover',
            'category_id'
        ];

        $this->assertEquals($expectedFillable, $product->getFillable());
    }

    /** @test */
    public function product_uses_soft_deletes()
    {
        $product = new Product();

        $this->assertArrayHasKey('deleted_at', $product->getCasts());
        $this->assertEquals('datetime', $product->getCasts()['deleted_at']);
    }

    /** @test */
    public function product_has_timestamps()
    {
        $product = Product::factory()->create();

        $this->assertNotNull($product->created_at);
        $this->assertNotNull($product->updated_at);
    }
}
