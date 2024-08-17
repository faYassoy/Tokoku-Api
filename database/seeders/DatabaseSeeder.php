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
            'name' => 'Pen Lovein',
            'barcode' => '1326001',
            'image' => '/default-image.png',
            'buy_price' => 1500,
            // 'sales_counter' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('products')->insert([
            'category_id' => 1, // Assuming the first product category ID is 1
            'name' => 'Buku',
            'barcode' => '00987',
            'image' => '/default-image.png',
            'buy_price' => 2000,
            // 'sales_counter' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed product_prices
        DB::table('product_prices')->insert([
            'product_id' => 1, // Assuming the first product ID is 1
            'price_type' => 'Ecer',
            'price' => 2500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('product_prices')->insert([
            'product_id' => 2, // Assuming the first product ID is 1
            'price_type' => 'Ecer',
            'price' => 2500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('product_stock')->insert([
            'product_id' => 1, // Assuming the first product ID is 1
            'stock_quantity' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('product_stock')->insert([
            'product_id' => 2, // Assuming the first product ID is 1
            'stock_quantity' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
