<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Product::create([
            'name' => '2022 IPHONE 14 pro max',
            'price' => 45000,
            'description' => '2021 IPHONE 14 pro max 新上市',
            'quantity' => 50
        ]);
    
        Product::create([
            'name' => '2023 IPHONE 15 pro max',
            'price' => 48000,
            'description' => '2023 IPHONE 15 pro max 新上市',
            'quantity' => 50
        ]);
    }
}
