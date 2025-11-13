<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductSku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductCustomMethodsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function get_products_with_category_loads_category_relationship()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $products = Product::getProductsWithCategory();

        $this->assertCount(2, $products);
        $this->assertTrue($products->first()->relationLoaded('category'));
        $this->assertEquals('Electronics', $products->first()->category->name);
    }

    /** @test */
    public function get_products_with_skus_loads_product_skus_relationship()
    {
        $product = Product::factory()->create();
        ProductSku::factory()->count(3)->create(['product_id' => $product->id]);

        $products = Product::getProductsWithSkus();

        $this->assertCount(1, $products);
        $this->assertTrue($products->first()->relationLoaded('productSkus'));
        $this->assertEquals(3, $products->first()->productSkus->count());
    }

    /** @test */
    public function get_products_by_category_filters_by_category_id()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $category1Products = Product::getProductsByCategory($category1->id);
        $category2Products = Product::getProductsByCategory($category2->id);

        $this->assertCount(3, $category1Products);
        $this->assertCount(2, $category2Products);

        // Verify all products belong to the correct category
        $category1Products->each(function ($product) use ($category1) {
            $this->assertEquals($category1->id, $product->category_id);
        });

        $category2Products->each(function ($product) use ($category2) {
            $this->assertEquals($category2->id, $product->category_id);
        });
    }

    /** @test */
    public function get_products_by_category_returns_empty_collection_for_non_existent_category()
    {
        Product::factory()->count(3)->create();

        $products = Product::getProductsByCategory(999);

        $this->assertCount(0, $products);
        $this->assertTrue($products->isEmpty());
    }

    /** @test */
    public function search_products_finds_products_by_name()
    {
        Product::factory()->create(['name' => 'iPhone 15 Pro']);
        Product::factory()->create(['name' => 'Samsung Galaxy S24']);
        Product::factory()->create(['name' => 'iPhone 15']);
        Product::factory()->create(['name' => 'Google Pixel 8']);

        $iphoneProducts = Product::searchProducts('iPhone');
        $samsungProducts = Product::searchProducts('Samsung');

        $this->assertCount(2, $iphoneProducts);
        $this->assertCount(1, $samsungProducts);

        $iphoneProducts->each(function ($product) {
            $this->assertStringContainsString('iPhone', $product->name);
        });
    }

    /** @test */
    public function search_products_finds_products_by_description()
    {
        Product::factory()->create([
            'name' => 'Laptop',
            'description' => 'High-performance gaming laptop with NVIDIA graphics'
        ]);
        Product::factory()->create([
            'name' => 'Desktop',
            'description' => 'Gaming desktop computer with AMD processor'
        ]);
        Product::factory()->create([
            'name' => 'Monitor',
            'description' => 'Professional monitor for office work'
        ]);

        $gamingProducts = Product::searchProducts('gaming');
        $nvidiaProducts = Product::searchProducts('NVIDIA');

        $this->assertCount(2, $gamingProducts);
        $this->assertCount(1, $nvidiaProducts);
    }

    /** @test */
    public function search_products_is_case_insensitive()
    {
        Product::factory()->create(['name' => 'Apple MacBook Pro']);
        Product::factory()->create(['description' => 'Apple iPhone with iOS']);

        $lowerCaseResults = Product::searchProducts('apple');
        $upperCaseResults = Product::searchProducts('APPLE');
        $mixedCaseResults = Product::searchProducts('Apple');

        $this->assertCount(2, $lowerCaseResults);
        $this->assertCount(2, $upperCaseResults);
        $this->assertCount(2, $mixedCaseResults);
    }

    /** @test */
    public function search_products_returns_empty_collection_when_no_matches()
    {
        Product::factory()->create(['name' => 'Product 1']);
        Product::factory()->create(['name' => 'Product 2']);

        $results = Product::searchProducts('NonExistentProduct');

        $this->assertCount(0, $results);
        $this->assertTrue($results->isEmpty());
    }

    /** @test */
    public function search_products_searches_both_name_and_description()
    {
        Product::factory()->create([
            'name' => 'Smartphone',
            'description' => 'Latest Android device'
        ]);
        Product::factory()->create([
            'name' => 'Tablet Android Pro',
            'description' => 'Professional tablet for business'
        ]);
        Product::factory()->create([
            'name' => 'Laptop',
            'description' => 'Windows laptop for students'
        ]);

        $androidResults = Product::searchProducts('Android');

        $this->assertCount(2, $androidResults);
    }

    /** @test */
    public function search_products_handles_partial_matches()
    {
        Product::factory()->create(['name' => 'Smartphone']);
        Product::factory()->create(['name' => 'Smart TV']);
        Product::factory()->create(['name' => 'Smart Watch']);

        $smartResults = Product::searchProducts('Smart');

        $this->assertCount(3, $smartResults);
    }

    /** @test */
    public function create_product_static_method_creates_product()
    {
        $category = Category::factory()->create();
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'summary' => 'Test summary',
            'cover' => 'https://example.com/image.jpg',
            'category_id' => $category->id
        ];

        $product = Product::createProduct($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productData['name'], $product->name);
        $this->assertDatabaseHas('products', $productData);
    }

    /** @test */
    public function get_product_by_id_static_method_finds_product()
    {
        $product = Product::factory()->create(['name' => 'Found Product']);

        $foundProduct = Product::getProductById($product->id);

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($product->id, $foundProduct->id);
        $this->assertEquals('Found Product', $foundProduct->name);
    }

    /** @test */
    public function get_product_by_id_returns_null_for_non_existent_id()
    {
        $product = Product::getProductById(99999);

        $this->assertNull($product);
    }

    /** @test */
    public function get_all_products_static_method_returns_all_products()
    {
        Product::factory()->count(5)->create();

        $products = Product::getAllProducts();

        $this->assertCount(5, $products);
        $this->assertInstanceOf(Product::class, $products->first());
    }

    /** @test */
    public function update_product_instance_method_updates_product()
    {
        $product = Product::factory()->create(['name' => 'Original Name']);
        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated description'
        ];

        $result = $product->updateProduct($updateData);

        $this->assertTrue($result);
        $product->refresh();
        $this->assertEquals('Updated Name', $product->name);
        $this->assertEquals('Updated description', $product->description);
    }

    /** @test */
    public function delete_product_instance_method_soft_deletes_product()
    {
        $product = Product::factory()->create();

        $result = $product->deleteProduct();

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function custom_methods_work_with_soft_deleted_products()
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);
        $deletedProduct = Product::factory()->create(['category_id' => $category->id]);

        $deletedProduct->delete();

        // These methods should not include soft deleted products
        $allProducts = Product::getAllProducts();
        $categoryProducts = Product::getProductsByCategory($category->id);
        $productsWithCategory = Product::getProductsWithCategory();

        $this->assertCount(2, $allProducts);
        $this->assertCount(2, $categoryProducts);
        $this->assertCount(2, $productsWithCategory);

        // None should contain the deleted product
        $this->assertFalse($allProducts->contains('id', $deletedProduct->id));
        $this->assertFalse($categoryProducts->contains('id', $deletedProduct->id));
        $this->assertFalse($productsWithCategory->contains('id', $deletedProduct->id));
    }
}
