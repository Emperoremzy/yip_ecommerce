<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $electronics = Category::create(['name' => 'Electronics']);
        $fashion = Category::create(['name' => 'Fashion']);
        $home = Category::create(['name' => 'Home']);

        Product::insert([
            [
                'category_id' => $electronics->id,
                'name' => 'Wireless Headphones',
                'price' => 89.99,
                'stock' => 24,
                'image' => 'https://picsum.photos/id/668/900/675',
                'short_description' => 'Noise-cancelling over-ear headphones.',
                'description' => 'Enjoy crystal-clear audio with active noise cancellation and long battery life.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $fashion->id,
                'name' => 'Classic Denim Jacket',
                'price' => 59.50,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1523381210438-271e66be9310?auto=format&fit=crop&w=900&q=80',
                'short_description' => 'Timeless style for all seasons.',
                'description' => 'A durable denim jacket with a comfortable fit and premium stitching.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $home->id,
                'name' => 'Modern Table Lamp',
                'price' => 34.00,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?auto=format&fit=crop&w=900&q=80',
                'short_description' => 'Warm lighting for your workspace.',
                'description' => 'Minimalist table lamp with adjustable brightness and durable build quality.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
