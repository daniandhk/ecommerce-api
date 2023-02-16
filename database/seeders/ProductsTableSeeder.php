<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop Acer',
            'description' => 'Product 1 description',
            'price' => 100000,
            'quantity' => 10,
        ]);

        Product::create([
            'name' => 'Laptop Lenovo',
            'description' => 'Product 2 description',
            'price' => 150000,
            'quantity' => 10,
        ]);
    }
}
