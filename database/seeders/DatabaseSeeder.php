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
    private function calculatePrice($buy_price, $type)
    {
        switch ($type) {
            case 'Ecer':
                return $buy_price * 1.3; // Harga ecer 30% lebih mahal
            case 'Grosir':
                return $buy_price * 1.1; // Harga grosir 10% lebih mahal
            // case 'Distributor':
            //     return $buy_price * 0.9; // Harga distributor 10% lebih murah
        }
    }
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
      

        // Seed customers
        DB::table('customers')->insert([
            'name' => 'John Doe',
            'contact_info' => '082145867879',
            'credit_balance' => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Seed product categories
        DB::table('product_categories')->insert([
            ['name' => 'Makanan & Minuman', 'description' => 'Produk makanan dan minuman sehari-hari', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebersihan', 'description' => 'Produk kebersihan yang dibutuhkan dalam rumah tangga', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Obat-obatan', 'description' => 'Produk kesehatan dasar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alat Tulis', 'description' => 'Kebutuhan alat tulis untuk keperluan sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Produk Bayi', 'description' => 'Produk perawatan dan kebutuhan bayi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kosmetik', 'description' => 'Produk perawatan tubuh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebutuhan Dapur', 'description' => 'Bumbu dapur dan kebutuhan memasak', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Tidak Ada',
            'description' => 'Barang Tidak/Belum Perlu Kategori Khusus ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Seed products for each category
        DB::table('products')->insert([
            // Makanan & Minuman
            [
                'category_id' => 1,
                'name' => 'Beras Rojolele',
                'barcode' => '1110001',
                'image' => '/default-image.png',
                'buy_price' => 12000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Minyak Goreng Bimoli',
                'barcode' => '1110002',
                'image' => '/default-image.png',
                'buy_price' => 14000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Gula Pasir Gulaku',
                'barcode' => '1110003',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Teh Sariwangi',
                'barcode' => '1110004',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Kopi Kapal Api',
                'barcode' => '1110005',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Mi Instan Indomie',
                'barcode' => '1110006',
                'image' => '/default-image.png',
                'buy_price' => 2500,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Air Mineral Aqua',
                'barcode' => '1110007',
                'image' => '/default-image.png',
                'buy_price' => 4000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Susu Frisian Flag',
                'barcode' => '1110008',
                'image' => '/default-image.png',
                'buy_price' => 7000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Saus ABC',
                'barcode' => '1110009',
                'image' => '/default-image.png',
                'buy_price' => 3000,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'category_id' => 1,
                'name' => 'Roti Tawar Sari Roti',
                'barcode' => '1110010',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Produk Kebersihan Rumah Tangga

            [
                'category_id' => 2, // Assuming category_id 2 is for 'Produk Kebersihan Rumah Tangga'
                'name' => 'Detergen Rinso',
                'barcode' => '1120001',
                'image' => '/default-image.png',
                'buy_price' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Sabun Mandi Lifebuoy',
                'barcode' => '1120002',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Pembersih Lantai Super Pell',
                'barcode' => '1120003',
                'image' => '/default-image.png',
                'buy_price' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Sabun Cuci Piring Sunlight',
                'barcode' => '1120004',
                'image' => '/default-image.png',
                'buy_price' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Pewangi Pakaian Molto',
                'barcode' => '1120005',
                'image' => '/default-image.png',
                'buy_price' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Pembersih Kaca Mr. Muscle',
                'barcode' => '1120006',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Sapu Lidi',
                'barcode' => '1120007',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Alat Pel Bolde',
                'barcode' => '1120008',
                'image' => '/default-image.png',
                'buy_price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Cairan Pemutih Bayclin',
                'barcode' => '1120009',
                'image' => '/default-image.png',
                'buy_price' => 9000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Tisu Basah Paseo',
                'barcode' => '1120010',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3, // Assuming category_id 3 is for 'Produk Kesehatan & Obat-obatan Dasar'
                'name' => 'Paracetamol',
                'barcode' => '1130001',
                'image' => '/default-image.png',
                'buy_price' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Antangin JRG',
                'barcode' => '1130002',
                'image' => '/default-image.png',
                'buy_price' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Minyak Kayu Putih Cap Lang',
                'barcode' => '1130003',
                'image' => '/default-image.png',
                'buy_price' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Betadine',
                'barcode' => '1130004',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Promag',
                'barcode' => '1130005',
                'image' => '/default-image.png',
                'buy_price' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Minyak Telon My Baby',
                'barcode' => '1130006',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Bodrex',
                'barcode' => '1130007',
                'image' => '/default-image.png',
                'buy_price' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Salonpas Koyo',
                'barcode' => '1130008',
                'image' => '/default-image.png',
                'buy_price' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Minyak Angin Aromatherapy Fresh Care',
                'barcode' => '1130009',
                'image' => '/default-image.png',
                'buy_price' => 9000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Diapet',
                'barcode' => '1130010',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4, // Assuming category_id 3 is for 'Alat Tulis dan Sekolah'
                'name' => 'Pensil Faber-Castell 2B',
                'barcode' => '1130001',
                'image' => '/default-image.png',
                'buy_price' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Pulpen Pilot Hitam',
                'barcode' => '1130002',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Penghapus Kenko',
                'barcode' => '1130003',
                'image' => '/default-image.png',
                'buy_price' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Spidol Snowman',
                'barcode' => '1130004',
                'image' => '/default-image.png',
                'buy_price' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Buku Tulis Sinar Dunia',
                'barcode' => '1130005',
                'image' => '/default-image.png',
                'buy_price' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Penggaris Kayu 30cm',
                'barcode' => '1130006',
                'image' => '/default-image.png',
                'buy_price' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Stabilo Warna Kuning',
                'barcode' => '1130007',
                'image' => '/default-image.png',
                'buy_price' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Crayon Pentel',
                'barcode' => '1130008',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Kotak Pensil Karakter',
                'barcode' => '1130009',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Lem Kertas Fox',
                'barcode' => '1130010',
                'image' => '/default-image.png',
                'buy_price' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5, // Assuming category_id 3 is for 'Produk Ibu dan Bayi'
                'name' => 'Popok Bayi Pampers',
                'barcode' => '1130001',
                'image' => '/default-image.png',
                'buy_price' => 35000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Susu Formula S26',
                'barcode' => '1130002',
                'image' => '/default-image.png',
                'buy_price' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Minyak Telon Konicare',
                'barcode' => '1130003',
                'image' => '/default-image.png',
                'buy_price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Sabun Bayi Zwitsal',
                'barcode' => '1130004',
                'image' => '/default-image.png',
                'buy_price' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Bedak Bayi Johnson’s',
                'barcode' => '1130005',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Tisu Basah MamyPoko',
                'barcode' => '1130006',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Botol Susu Pigeon',
                'barcode' => '1130007',
                'image' => '/default-image.png',
                'buy_price' => 20000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Tisu Kering Paseo',
                'barcode' => '1130008',
                'image' => '/default-image.png',
                'buy_price' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Bubur Bayi Cerelac',
                'barcode' => '1130009',
                'image' => '/default-image.png',
                'buy_price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Kapas Bayi My Baby',
                'barcode' => '1130010',
                'image' => '/default-image.png',
                'buy_price' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6, // category_id for 'Produk Perawatan Pribadi'
                'name' => 'Shampoo Sunsilk',
                'barcode' => '1130001',
                'image' => '/default-image.png',
                'buy_price' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Sabun Dove',
                'barcode' => '1130002',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Pasta Gigi Pepsodent',
                'barcode' => '1130003',
                'image' => '/default-image.png',
                'buy_price' => 6000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Deodoran Rexona',
                'barcode' => '1130004',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Lotion Vaseline',
                'barcode' => '1130005',
                'image' => '/default-image.png',
                'buy_price' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Minyak Rambut Gatsby',
                'barcode' => '1130006',
                'image' => '/default-image.png',
                'buy_price' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Bedak Marcks',
                'barcode' => '1130007',
                'image' => '/default-image.png',
                'buy_price' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Hand Sanitizer Dettol',
                'barcode' => '1130008',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Face Wash Garnier',
                'barcode' => '1130009',
                'image' => '/default-image.png',
                'buy_price' => 14000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Body Mist Marina',
                'barcode' => '1130010',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7, // Assuming category_id 7 is for 'Bumbu & Kebutuhan Dapur'
                'name' => 'Garam Dapur Cap Kapal',
                'barcode' => '1170001',
                'image' => '/default-image.png',
                'buy_price' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Gula Pasir Gulaku',
                'barcode' => '1170002',
                'image' => '/default-image.png',
                'buy_price' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Kecap Manis ABC',
                'barcode' => '1170003',
                'image' => '/default-image.png',
                'buy_price' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Minyak Goreng Bimoli',
                'barcode' => '1170004',
                'image' => '/default-image.png',
                'buy_price' => 14000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Saus Sambal Indofood',
                'barcode' => '1170005',
                'image' => '/default-image.png',
                'buy_price' => 7000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Tepung Terigu Segitiga Biru',
                'barcode' => '1170006',
                'image' => '/default-image.png',
                'buy_price' => 9000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Margarin Blue Band',
                'barcode' => '1170007',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Bawang Putih Bubuk Indofood',
                'barcode' => '1170008',
                'image' => '/default-image.png',
                'buy_price' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Penyedap Rasa Royco Ayam',
                'barcode' => '1170009',
                'image' => '/default-image.png',
                'buy_price' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Santan Kara',
                'barcode' => '1170010',
                'image' => '/default-image.png',
                'buy_price' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // Seed product_prices
        $products = [
            ["id" => 1, "buy_price" => 12000],
            ["id" => 2, "buy_price" => 14000],
            ["id" => 3, "buy_price" => 10000],
            ["id" => 4, "buy_price" => 5000],
            ["id" => 5, "buy_price" => 8000],
            ["id" => 6, "buy_price" => 2500],
            ["id" => 7, "buy_price" => 4000],
            ["id" => 8, "buy_price" => 7000],
            ["id" => 9, "buy_price" => 3000],
            ["id" => 10, "buy_price" => 10000],
            ["id" => 11, "buy_price" => 12000],
            ["id" => 12, "buy_price" => 5000],
            ["id" => 13, "buy_price" => 6000],
            ["id" => 14, "buy_price" => 7000],
            ["id" => 15, "buy_price" => 4000],
            ["id" => 16, "buy_price" => 8000],
            ["id" => 17, "buy_price" => 5000],
            ["id" => 18, "buy_price" => 15000],
            ["id" => 19, "buy_price" => 9000],
            ["id" => 20, "buy_price" => 5000],
            ["id" => 21, "buy_price" => 2000],
            ["id" => 22, "buy_price" => 3000],
            ["id" => 23, "buy_price" => 7000],
            ["id" => 24, "buy_price" => 5000],
            ["id" => 25, "buy_price" => 4000],
            ["id" => 26, "buy_price" => 8000],
            ["id" => 27, "buy_price" => 3000],
            ["id" => 28, "buy_price" => 2000],
            ["id" => 29, "buy_price" => 9000],
            ["id" => 30, "buy_price" => 5000],
            ["id" => 31, "buy_price" => 2000],
            ["id" => 32, "buy_price" => 5000],
            ["id" => 33, "buy_price" => 1000],
            ["id" => 34, "buy_price" => 4000],
            ["id" => 35, "buy_price" => 3000],
            ["id" => 36, "buy_price" => 2000],
            ["id" => 37, "buy_price" => 6000],
            ["id" => 38, "buy_price" => 10000],
            ["id" => 39, "buy_price" => 8000],
            ["id" => 40, "buy_price" => 3000],
            ["id" => 41, "buy_price" => 35000],
            ["id" => 42, "buy_price" => 100000],
            ["id" => 43, "buy_price" => 15000],
            ["id" => 44, "buy_price" => 12000],
            ["id" => 45, "buy_price" => 10000],
            ["id" => 46, "buy_price" => 8000],
            ["id" => 47, "buy_price" => 20000],
            ["id" => 48, "buy_price" => 6000],
            ["id" => 49, "buy_price" => 15000],
            ["id" => 50, "buy_price" => 7000],
            ["id" => 51, "buy_price" => 12000],
            ["id" => 52, "buy_price" => 8000],
            ["id" => 53, "buy_price" => 6000],
            ["id" => 54, "buy_price" => 10000],
            ["id" => 55, "buy_price" => 15000],
            ["id" => 56, "buy_price" => 12000],
            ["id" => 57, "buy_price" => 7000],
            ["id" => 58, "buy_price" => 5000],
            ["id" => 59, "buy_price" => 14000],
            ["id" => 60, "buy_price" => 10000],
            ["id" => 61, "buy_price" => 2000],
            ["id" => 62, "buy_price" => 12000],
            ["id" => 63, "buy_price" => 10000],
            ["id" => 64, "buy_price" => 14000],
            ["id" => 65, "buy_price" => 7000],
            ["id" => 66, "buy_price" => 9000],
            ["id" => 67, "buy_price" => 5000],
            ["id" => 68, "buy_price" => 8000],
            ["id" => 69, "buy_price" => 500],
            ["id" => 70, "buy_price" => 5000]
        ];
        $price_types = ['Ecer', 'Grosir'];
        foreach ($products as $product) {
            foreach ($price_types as $type) {
                DB::table('product_prices')->insert([
                    'product_id' => $product['id'],
                    'price_type' => $type,
                    'price' => $this->calculatePrice($product['buy_price'], $type),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        foreach ($products as $product) {
            foreach ($price_types as $type) {
                DB::table('product_stock')->insert([
                    'product_id' => $product['id'],
                    'stock_quantity' => rand(12, 32),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
    }
}
