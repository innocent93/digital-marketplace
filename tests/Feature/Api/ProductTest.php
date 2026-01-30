<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('public can list products', function () {
    Product::factory()->count(5)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id', 'title', 'slug', 'price', 'category'
                     ]
                 ],
                 'links', 'meta'
             ]);
});

test('creator can create product', function () {
    $creator = User::factory()->create(['role_id' => 1]); // creator role
    Sanctum::actingAs($creator);

    $category = Category::factory()->create();

    $response = $this->postJson('/api/products', [
        'title' => 'New Product',
        'slug' => 'new-product',
        'description' => 'Test description',
        'price' => 49.99,
        'category_id' => $category->id,
    ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.title', 'New Product');

    $this->assertDatabaseHas('products', ['title' => 'New Product']);
});

test('non-creator cannot create product', function () {
    $customer = User::factory()->create(['role_id' => 2]); // customer role
    Sanctum::actingAs($customer);

    $response = $this->postJson('/api/products', [
        'title' => 'Invalid Product',
    ]);

    $response->assertStatus(403);
});