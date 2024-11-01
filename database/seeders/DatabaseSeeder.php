<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Tag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();



        // Yönetici rolü oluşturma
        $adminRole = Role::create(['name' => 'admin']);

        // Kullanıcı rolü oluşturma
        $userRole = Role::create(['name' => 'user']);


        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com'
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $user = User::find(1);
        $user->assignRole('admin');

        $user2 = User::find(2);
        $user2->assignRole('user');



        // kategori oluşturma
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Clothing'],
            ['name' => 'Books'],
            ['name' => 'Home & Garden'],
            ['name' => 'Sports & Outdoors'],
            ['name' => 'Beauty & Personal Care'],
            ['name' => 'Automotive'],
            ['name' => 'Toys & Games'],
            ['name' => 'Health & Wellness'],
            ['name' => 'Food & Beverages'],
            ['name' => 'Office Supplies'],
            ['name' => 'Jewelry & Watches'],
            ['name' => 'Music'],
            ['name' => 'Movies & TV Shows'],
            ['name' => 'Pet Supplies'],
            ['name' => 'Baby Products'],
            ['name' => 'Tools & Home Improvement'],
            ['name' => 'Gifts & Crafts'],
            ['name' => 'Travel Accessories'],
            ['name' => 'Photography'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }


        // etiket oluşturma
        $tags = [
            ['name' => 'Electronics_tag'],
            ['name' => 'Clothing_tag'],
            ['name' => 'Books_tag'],
            ['name' => 'Home & Garden_tag'],
            ['name' => 'Sports & Outdoors_tag'],
            ['name' => 'Beauty & Personal Care_tag'],
            ['name' => 'Automotive_tag'],
            ['name' => 'Toys & Games_tag'],
            ['name' => 'Health & Wellness_tag'],
            ['name' => 'Food & Beverages_tag'],
            ['name' => 'Office Supplies_tag'],
            ['name' => 'Jewelry & Watches_tag'],
            ['name' => 'Music_tag'],
            ['name' => 'Movies & TV Shows_tag'],
            ['name' => 'Pet Supplies_tag'],
            ['name' => 'Baby Products_tag'],
            ['name' => 'Tools & Home Improvement_tag'],
            ['name' => 'Gifts & Crafts_tag'],
            ['name' => 'Travel Accessories_tag'],
            ['name' => 'Photography_tag'],
            ['name' => 'Fitness_tag'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
        




        // products oluşturma
        $products = [
            [
                'name' => 'Product 1',
                'price' => 100,
                'description' => 'Product 1 description',
                'category_id' => 1,
                'featured' => 1,
                'stock' => 10,
                'slug' => 'product-1'
            ],
            [
                'name' => 'Product 2',
                'price' => 150,
                'description' => 'Product 2 description',
                'category_id' => 1,
                'featured' => 0,
                'stock' => 20,
                'slug' => 'product-2'
            ],
            [
                'name' => 'Product 3',
                'price' => 200,
                'description' => 'Product 3 description',
                'category_id' => 2,
                'featured' => 1,
                'stock' => 15,
                'slug' => 'product-3'
            ],
            [
                'name' => 'Product 4',
                'price' => 250,
                'description' => 'Product 4 description',
                'category_id' => 2,
                'featured' => 0,
                'stock' => 5,
                'slug' => 'product-4'
            ],
            [
                'name' => 'Product 5',
                'price' => 300,
                'description' => 'Product 5 description',
                'category_id' => 3,
                'featured' => 1,
                'stock' => 8,
                'slug' => 'product-5'
            ],
            [
                'name' => 'Product 6',
                'price' => 350,
                'description' => 'Product 6 description',
                'category_id' => 3,
                'featured' => 0,
                'stock' => 12,
                'slug' => 'product-6'
            ],
            [
                'name' => 'Product 7',
                'price' => 400,
                'description' => 'Product 7 description',
                'category_id' => 4,
                'featured' => 1,
                'stock' => 6,
                'slug' => 'product-7'
            ],
            [
                'name' => 'Product 8',
                'price' => 450,
                'description' => 'Product 8 description',
                'category_id' => 4,
                'featured' => 0,
                'stock' => 18,
                'slug' => 'product-8'
            ],
            [
                'name' => 'Product 9',
                'price' => 500,
                'description' => 'Product 9 description',
                'category_id' => 5,
                'featured' => 1,
                'stock' => 7,
                'slug' => 'product-9'
            ],
            [
                'name' => 'Product 10',
                'price' => 550,
                'description' => 'Product 10 description',
                'category_id' => 5,
                'featured' => 0,
                'stock' => 13,
                'slug' => 'product-10'
            ],
            [
                'name' => 'Product 11',
                'price' => 600,
                'description' => 'Product 11 description',
                'category_id' => 6,
                'featured' => 1,
                'stock' => 9,
                'slug' => 'product-11'
            ],
            [
                'name' => 'Product 12',
                'price' => 650,
                'description' => 'Product 12 description',
                'category_id' => 6,
                'featured' => 0,
                'stock' => 14,
                'slug' => 'product-12'
            ],
            [
                'name' => 'Product 13',
                'price' => 700,
                'description' => 'Product 13 description',
                'category_id' => 7,
                'featured' => 1,
                'stock' => 11,
                'slug' => 'product-13'
            ],
            [
                'name' => 'Product 14',
                'price' => 750,
                'description' => 'Product 14 description',
                'category_id' => 7,
                'featured' => 0,
                'stock' => 17,
                'slug' => 'product-14'
            ],
            [
                'name' => 'Product 15',
                'price' => 800,
                'description' => 'Product 15 description',
                'category_id' => 8,
                'featured' => 1,
                'stock' => 3,
                'slug' => 'product-15'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }






    }
}
