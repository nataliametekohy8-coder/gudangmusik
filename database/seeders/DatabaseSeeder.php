<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin Musik',
            'email' => 'admin@musik.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $staff = User::forceCreate([
            'name' => 'Staff Gudang',
            'email' => 'staff@musik.test',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $cats = [];
        foreach (['Gitar & Ukulele', 'Keyboard', 'Perkusi', 'Aksesoris', 'Kabel & Audio'] as $name) {
            $cats[$name] = Category::create(['name' => $name]);
        }

        $s1 = Supplier::create(['name' => 'PT Nada Sejahtera', 'phone' => '021-5550101', 'address' => 'Jakarta']);
        $s2 = Supplier::create(['name' => 'CV Irama Jaya', 'phone' => '022-5550202', 'address' => 'Bandung']);

        $items = [
            ['Gitar Akustik F310', 'GTR-001', 'Yamaha', 'Gitar & Ukulele', $s1, 12, 3, 1400000, 1750000],
            ['Ukulele Concert', 'UKL-001', 'Cort', 'Gitar & Ukulele', $s2, 20, 5, 350000, 480000],
            ['Keyboard PSR-E373', 'KEY-001', 'Yamaha', 'Keyboard', $s1, 6, 2, 3200000, 3800000],
            ['Kalimba 17 Nada', 'PRC-001', 'Gecko', 'Perkusi', $s2, 25, 5, 90000, 150000],
            ['Harmonika Diatonis', 'PRC-002', 'Hohner', 'Perkusi', $s2, 4, 5, 120000, 190000],
            ['Senar Gitar Set', 'ACC-001', 'Elixir', 'Aksesoris', $s1, 50, 10, 60000, 95000],
            ['Pick Gitar (isi 12)', 'ACC-002', 'Fender', 'Aksesoris', $s1, 80, 20, 15000, 30000],
            ['Capo Gitar', 'ACC-003', 'Kyser', 'Aksesoris', $s2, 3, 5, 45000, 80000],
            ['Kabel Jack 3m', 'CBL-001', 'Mogami', 'Kabel & Audio', $s1, 30, 8, 70000, 120000],
            ['Mic Dinamis SM58', 'CBL-002', 'Shure', 'Kabel & Audio', $s1, 9, 3, 1300000, 1650000],
        ];

        foreach ($items as [$name, $sku, $brand, $cat, $sup, $stock, $min, $buy, $sell]) {
            $p = Product::create([
                'category_id' => $cats[$cat]->id,
                'supplier_id' => $sup->id,
                'name' => $name,
                'sku' => $sku,
                'brand' => $brand,
                'stock' => $stock,
                'min_stock' => $min,
                'buy_price' => $buy,
                'sell_price' => $sell,
            ]);

            StockTransaction::create([
                'product_id' => $p->id,
                'user_id' => $admin->id,
                'type' => 'in',
                'quantity' => $stock,
                'note' => 'Stok awal',
                'transaction_date' => now()->subDays(7)->toDateString(),
            ]);
        }

        StockTransaction::create([
            'product_id' => 1,
            'user_id' => $staff->id,
            'type' => 'out',
            'quantity' => 2,
            'note' => 'Penjualan toko',
            'transaction_date' => now()->toDateString(),
        ]);
        Product::find(1)->decrement('stock', 2);
    }
}