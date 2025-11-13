<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class ProductSoftDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function product_can_be_soft_deleted()
    {
        $product = Product::factory()->create(['name' => 'Test Product']);

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNotNull($product->fresh()->deleted_at);
    }

    /** @test */
    public function product_delete_method_performs_soft_delete()
    {
        $product = Product::factory()->create();

        $result = $product->deleteProduct();

        $this->assertTrue($result);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNotNull($product->fresh()->deleted_at);
    }

    /** @test */
    public function soft_deleted_product_can_be_restored()
    {
        $product = Product::factory()->create();
        $product->delete();

        $product->restore();

        $this->assertNull($product->fresh()->deleted_at);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function soft_deleted_products_are_excluded_from_regular_queries()
    {
        Product::factory()->count(3)->create();
        $deletedProduct = Product::factory()->create();
        $deletedProduct->delete();

        $allProducts = Product::all();
        $foundProduct = Product::find($deletedProduct->id);

        $this->assertCount(3, $allProducts);
        $this->assertNull($foundProduct);
        $this->assertFalse($allProducts->contains('id', $deletedProduct->id));
    }

    /** @test */
    public function soft_deleted_products_can_be_found_with_trashed()
    {
        $product = Product::factory()->create(['name' => 'Deleted Product']);
        $product->delete();

        $trashedProduct = Product::withTrashed()->find($product->id);
        $allWithTrashed = Product::withTrashed()->get();

        $this->assertNotNull($trashedProduct);
        $this->assertEquals('Deleted Product', $trashedProduct->name);
        $this->assertNotNull($trashedProduct->deleted_at);
        $this->assertGreaterThan(0, $allWithTrashed->count());
    }

    /** @test */
    public function only_trashed_products_can_be_retrieved()
    {
        Product::factory()->count(2)->create();
        $deletedProduct1 = Product::factory()->create();
        $deletedProduct2 = Product::factory()->create();

        $deletedProduct1->delete();
        $deletedProduct2->delete();

        $onlyTrashed = Product::onlyTrashed()->get();

        $this->assertCount(2, $onlyTrashed);
        $this->assertTrue($onlyTrashed->contains('id', $deletedProduct1->id));
        $this->assertTrue($onlyTrashed->contains('id', $deletedProduct2->id));
    }

    /** @test */
    public function deleted_at_timestamp_is_set_correctly()
    {
        $beforeDelete = Carbon::now()->subSecond();
        $product = Product::factory()->create();

        $product->delete();
        $afterDelete = Carbon::now()->addSecond();

        $deletedProduct = Product::withTrashed()->find($product->id);
        $this->assertNotNull($deletedProduct->deleted_at);
        $this->assertTrue($deletedProduct->deleted_at->between($beforeDelete, $afterDelete));
    }

    /** @test */
    public function deleted_at_cast_works_correctly()
    {
        $product = Product::factory()->create();
        $product->delete();

        $deletedProduct = Product::withTrashed()->find($product->id);

        $this->assertInstanceOf(Carbon::class, $deletedProduct->deleted_at);
        $this->assertArrayHasKey('deleted_at', $deletedProduct->getCasts());
        $this->assertEquals('datetime', $deletedProduct->getCasts()['deleted_at']);
    }

    /** @test */
    public function force_delete_permanently_removes_product()
    {
        $product = Product::factory()->create();
        $productId = $product->id;

        $product->forceDelete();

        $this->assertDatabaseMissing('products', ['id' => $productId]);
        $this->assertNull(Product::withTrashed()->find($productId));
    }

    /** @test */
    public function restore_sets_deleted_at_to_null()
    {
        $product = Product::factory()->create();
        $product->delete();

        $this->assertNotNull($product->fresh()->deleted_at);

        $product->restore();

        $restoredProduct = Product::find($product->id);
        $this->assertNotNull($restoredProduct);
        $this->assertNull($restoredProduct->deleted_at);
    }

    /** @test */
    public function soft_delete_affects_relationships()
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);

        $product1->delete();

        // Only non-deleted products should be returned
        $categoryProducts = $category->products;
        $this->assertCount(1, $categoryProducts);
        $this->assertEquals($product2->id, $categoryProducts->first()->id);
    }

    /** @test */
    public function soft_delete_works_with_custom_static_methods()
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);
        $deletedProduct = Product::factory()->create(['category_id' => $category->id]);

        $deletedProduct->delete();

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

    /** @test */
    public function search_products_excludes_soft_deleted_products()
    {
        Product::factory()->create(['name' => 'Apple iPhone 15']);
        $deletedProduct = Product::factory()->create(['name' => 'Apple iPad Pro']);
        Product::factory()->create(['name' => 'Apple MacBook']);

        $deletedProduct->delete();

        $results = Product::searchProducts('Apple');

        $this->assertCount(2, $results);
        $this->assertFalse($results->contains('id', $deletedProduct->id));
    }

    /** @test */
    public function multiple_products_can_be_soft_deleted()
    {
        $products = Product::factory()->count(5)->create();

        $products->take(3)->each(function ($product) {
            $product->delete();
        });

        $remainingProducts = Product::all();
        $trashedProducts = Product::onlyTrashed()->get();

        $this->assertCount(2, $remainingProducts);
        $this->assertCount(3, $trashedProducts);
    }

    /** @test */
    public function soft_deleted_products_maintain_data_integrity()
    {
        $product = Product::factory()->create([
            'name' => 'Original Product',
            'description' => 'Original Description',
            'summary' => 'Original Summary'
        ]);

        $product->delete();

        $deletedProduct = Product::withTrashed()->find($product->id);

        $this->assertEquals('Original Product', $deletedProduct->name);
        $this->assertEquals('Original Description', $deletedProduct->description);
        $this->assertEquals('Original Summary', $deletedProduct->summary);
        $this->assertNotNull($deletedProduct->deleted_at);
    }

    /** @test */
    public function restored_product_appears_in_regular_queries()
    {
        $product = Product::factory()->create(['name' => 'Restored Product']);
        $product->delete();
        $product->restore();

        $allProducts = Product::all();
        $foundProduct = Product::find($product->id);
        $searchResults = Product::searchProducts('Restored');

        $this->assertTrue($allProducts->contains('id', $product->id));
        $this->assertNotNull($foundProduct);
        $this->assertEquals('Restored Product', $foundProduct->name);
        $this->assertCount(1, $searchResults);
    }
}
