<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_list_all_products()
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson('/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'summary',
                        'cover',
                        'category_id',
                        'created_at',
                        'updated_at',
                        'category' => [
                            'id',
                            'name'
                        ]
                    ]
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function it_can_filter_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(2)->create(['category_id' => $category1->id]);
        Product::factory()->create(['category_id' => $category2->id]);

        $response = $this->getJson("/products?category_id={$category1->id}");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function it_can_show_a_single_product()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'category_id' => $category->id
        ]);

        $response = $this->getJson("/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => 'Test Product',
                'category' => [
                    'id' => $category->id,
                    'name' => 'Electronics'
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_product()
    {
        $response = $this->getJson('/products/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_create_a_new_product()
    {
        $category = Category::factory()->create();

        $productData = [
            'name' => 'New Test Product',
            'description' => 'A new test product description',
            'summary' => 'Test summary',
            'cover' => 'https://example.com/image.jpg',
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/products', $productData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'New Test Product',
                'description' => 'A new test product description',
                'category_id' => $category->id,
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New Test Product',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_product()
    {
        $response = $this->postJson('/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'category_id']);
    }

    /** @test */
    public function it_validates_category_exists_when_creating_product()
    {
        $productData = [
            'name' => 'Test Product',
            'category_id' => 999, // Non-existent category
        ];

        $response = $this->postJson('/products', $productData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }

    /** @test */
    public function it_validates_unique_name_when_creating_product()
    {
        $category = Category::factory()->create();
        Product::factory()->create([
            'name' => 'Existing Product',
            'category_id' => $category->id
        ]);

        $productData = [
            'name' => 'Existing Product', // Duplicate name
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/products', $productData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_update_an_existing_product()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Original Product',
            'category_id' => $category1->id
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'summary' => 'Updated summary',
            'cover' => 'https://example.com/updated-image.jpg',
            'category_id' => $category2->id,
        ];

        $response = $this->putJson("/products/{$product->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Product',
                'description' => 'Updated description',
                'category_id' => $category2->id,
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'category_id' => $category2->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_updating_product()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/products/{$product->id}", [
            'name' => '', // Empty name
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'category_id']);
    }

    /** @test */
    public function it_validates_unique_name_when_updating_product_except_itself()
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create([
            'name' => 'Product 1',
            'category_id' => $category->id
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Product 2',
            'category_id' => $category->id
        ]);

        // Updating product2 with product1's name should fail
        $updateData = [
            'name' => 'Product 1',
            'category_id' => $category->id,
        ];

        $response = $this->putJson("/products/{$product2->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_allows_updating_product_with_same_name()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Same Product',
            'category_id' => $category->id
        ]);

        // Updating with the same name should be allowed
        $updateData = [
            'name' => 'Same Product',
            'description' => 'Updated description',
            'category_id' => $category->id,
        ];

        $response = $this->putJson("/products/{$product->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Same Product',
                'description' => 'Updated description',
            ]);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_product()
    {
        $response = $this->deleteJson('/products/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_get_categories_for_product_creation()
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/products/create');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_get_product_and_categories_for_editing()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        Category::factory()->count(2)->create(); // Additional categories

        $response = $this->getJson("/products/{$product->id}/edit");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'product' => [
                    'id',
                    'name',
                    'category_id'
                ],
                'categories' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('categories'));
        $this->assertEquals($product->id, $response->json('product.id'));
    }

    /** @test */
    public function deleted_products_are_not_shown_in_index()
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);
        $deletedProduct = Product::factory()->create(['category_id' => $category->id]);

        $deletedProduct->delete();

        $response = $this->getJson('/products');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function products_are_paginated()
    {
        $category = Category::factory()->create();
        Product::factory()->count(15)->create(['category_id' => $category->id]);

        $response = $this->getJson('/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);

        $this->assertEquals(10, $response->json('per_page'));
        $this->assertEquals(15, $response->json('total'));
        $this->assertEquals(2, $response->json('last_page'));
    }
}
