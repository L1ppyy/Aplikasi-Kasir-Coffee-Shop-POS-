<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08123456789',
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '08987654321',
        ]);

        // Categories
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'color' => '#f59e0b', 'icon' => 'utensils'],
            ['name' => 'Minuman', 'slug' => 'minuman', 'color' => '#3b82f6', 'icon' => 'cup-straw'],
            ['name' => 'Snack', 'slug' => 'snack', 'color' => '#10b981', 'icon' => 'cookie'],
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'color' => '#8b5cf6', 'icon' => 'cpu'],
            ['name' => 'Kebutuhan Rumah', 'slug' => 'kebutuhan-rumah', 'color' => '#ef4444', 'icon' => 'home'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'color' => '#06b6d4', 'icon' => 'heart'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $products = [
            ['category_id' => 1, 'name' => 'Nasi Goreng Spesial', 'sku' => 'MKN001', 'purchase_price' => 15000, 'selling_price' => 25000, 'stock' => 100, 'unit' => 'porsi'],
            ['category_id' => 1, 'name' => 'Mie Ayam Bakso', 'sku' => 'MKN002', 'purchase_price' => 12000, 'selling_price' => 20000, 'stock' => 80, 'unit' => 'porsi'],
            ['category_id' => 1, 'name' => 'Ayam Bakar', 'sku' => 'MKN003', 'purchase_price' => 18000, 'selling_price' => 30000, 'stock' => 60, 'unit' => 'porsi'],
            ['category_id' => 2, 'name' => 'Es Teh Manis', 'sku' => 'MNM001', 'purchase_price' => 2000, 'selling_price' => 5000, 'stock' => 200, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Jus Jeruk', 'sku' => 'MNM002', 'purchase_price' => 5000, 'selling_price' => 12000, 'stock' => 150, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Kopi Hitam', 'sku' => 'MNM003', 'purchase_price' => 3000, 'selling_price' => 8000, 'stock' => 180, 'unit' => 'gelas'],
            ['category_id' => 2, 'name' => 'Teh Tarik', 'sku' => 'MNM004', 'purchase_price' => 4000, 'selling_price' => 10000, 'stock' => 120, 'unit' => 'gelas'],
            ['category_id' => 3, 'name' => 'Keripik Singkong', 'sku' => 'SNK001', 'purchase_price' => 3000, 'selling_price' => 6000, 'stock' => 300, 'unit' => 'pcs'],
            ['category_id' => 3, 'name' => 'Chitato', 'sku' => 'SNK002', 'purchase_price' => 7000, 'selling_price' => 12000, 'stock' => 250, 'unit' => 'pcs'],
            ['category_id' => 3, 'name' => 'Oreo', 'sku' => 'SNK003', 'purchase_price' => 8000, 'selling_price' => 14000, 'stock' => 200, 'unit' => 'pcs'],
            ['category_id' => 4, 'name' => 'Baterai AA Energizer', 'sku' => 'ELK001', 'purchase_price' => 15000, 'selling_price' => 22000, 'stock' => 50, 'unit' => 'pack'],
            ['category_id' => 5, 'name' => 'Sabun Mandi Lifebuoy', 'sku' => 'RMH001', 'purchase_price' => 5000, 'selling_price' => 8500, 'stock' => 100, 'unit' => 'pcs'],
            ['category_id' => 5, 'name' => 'Shampo Sunsilk', 'sku' => 'RMH002', 'purchase_price' => 12000, 'selling_price' => 18000, 'stock' => 80, 'unit' => 'pcs'],
            ['category_id' => 6, 'name' => 'Paracetamol 500mg', 'sku' => 'KSH001', 'purchase_price' => 3000, 'selling_price' => 5000, 'stock' => 200, 'unit' => 'strip'],
            ['category_id' => 6, 'name' => 'Vitamin C 1000mg', 'sku' => 'KSH002', 'purchase_price' => 15000, 'selling_price' => 25000, 'stock' => 100, 'unit' => 'botol'],
        ];

        foreach ($products as $i => $product) {
            Product::create(array_merge($product, [
                'slug' => \Illuminate\Support\Str::slug($product['name']) . '-' . ($i + 1),
                'barcode' => '899' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                'min_stock' => 10,
            ]));
        }

        // Settings
        $settings = [
            ['key' => 'store_name', 'value' => 'Toko Maju Jaya'],
            ['key' => 'store_address', 'value' => 'Jl. Sudirman No. 123, Jakarta Pusat'],
            ['key' => 'store_phone', 'value' => '021-12345678'],
            ['key' => 'store_email', 'value' => 'toko@majujaya.com'],
            ['key' => 'tax_percent', 'value' => '0'],
            ['key' => 'currency', 'value' => 'IDR'],
            ['key' => 'receipt_footer', 'value' => 'Terima kasih atas kunjungan anda!'],
            ['key' => 'logo', 'value' => ''],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
