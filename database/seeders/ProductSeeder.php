<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::whereHas('role', fn($q) => $q->where('name', 'creator'))->first();
        $categories = Category::all();

        for ($i = 1; $i <= 10; $i++) {
            $title = "Sample Product $i";
            $slug = Str::slug($title);

            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'creator_id' => $creator->id,
                    'category_id' => $categories->random()->id,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => "Description for sample product $i",
                    'price' => rand(10, 100) . '.99',
                    'file_path' => "products/sample{$i}.pdf",
                    'file_size' => rand(100000, 5000000),
                    'mime_type' => 'application/pdf',
                ]
            );
        }
    }
}