<?php

namespace Database\Seeders;

use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Testimonial;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $faker = Faker::create();

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


        for ($i = 0; $i < 20; $i++) {
            DB::table('categories')->insert([
                'name' => $faker->word,
                'parent_id' => 1, // Assuming some categories have parents
                'slug' => Str::slug($faker->unique()->word),
                'icon' => $faker->optional()->imageUrl(),
                'description' => $faker->paragraph,
                'products_count' => $faker->numberBetween(0, 100),
                'is_active' => $faker->boolean,
                'sort_order' => $faker->numberBetween(0, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            DB::table('brands')->insert([
                'name' => $faker->company,
                'slug' => Str::slug($faker->company),
                'logo' => $faker->optional()->imageUrl(),
                'description' => $faker->paragraph,
                'is_active' => $faker->boolean,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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

        for ($i = 0; $i < 50; $i++) {
            DB::table('products')->insert([
                'name' => $faker->words(3, true),
                'slug' => Str::slug($faker->words(3, true)),
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 10, 1000),
                'stock' => $faker->numberBetween(0, 100),
                'category_id' => $faker->numberBetween(1, 10), // Assuming you have 10 categories
                'brand_id' => $faker->optional()->numberBetween(1, 10), // Assuming you have 10 brands
                'old_price' => $faker->optional()->randomFloat(2, 10, 1000),
                'is_active' => $faker->boolean,
                'is_featured' => $faker->boolean(20), // 20% chance of being featured
                'is_new' => $faker->boolean(20), // 20% chance of being new
                'discount' => $faker->optional()->numberBetween(0, 50),
                'rating' => $faker->randomFloat(1, 0, 5),
                'reviews_count' => $faker->numberBetween(0, 100),
                'specifications' => json_encode([
                    'color' => $faker->colorName,
                    'size' => $faker->randomElement(['S', 'M', 'L', 'XL']),
                    'material' => $faker->word,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }



        Testimonial::create([
            'avatar' => 'https://avatar.iran.liara.run/public',
            'author' => 'John Doe',
            'content' => 'This is the best shop ever! I love the products and the service is excellent.',
            'rating' => 4,
            'position' => 'Software Engineer',
        ]);

        Testimonial::create([
            'avatar' => 'https://avatar.iran.liara.run/public',
            'author' => 'Jane Smith',
            'content' => 'I have been shopping here for years and I am always satisfied with my purchases.',
            'rating' => 4,
            'position' => 'Ceo',
        ]);



    }
}
