<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'name' => 'Admin User',
                'role' => 'admin',
            ],
            [
                'username' => 'cashier',
                'password' => Hash::make('password'),
                'name' => 'Cashier User',
                'role' => 'cashier',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
        // Seed product_categories
        DB::table('product_categories')->insert([
            'name' => 'Tidak Ada',
            'description' => 'Barang Tidak/Belum Perlu Kategori Khusus ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed customers
        DB::table('customers')->insert([
            'name' => 'John Doe',
            'contact_info' => '082145867879',
            'credit_balance' => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed products
        DB::table('products')->insert([
            'category_id' => 1, // Assuming the first product category ID is 1
            'name' => 'Smartphone',
            'barcode' => 'Itel S23',
            'image' => 'path/to/image.jpg',
            'popularity_rating' => 5,
            'sales_counter' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed product_prices
        DB::table('product_prices')->insert([
            'product_id' => 1, // Assuming the first product ID is 1
            'price_type' => 'Retail',
            'price' => 299,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
