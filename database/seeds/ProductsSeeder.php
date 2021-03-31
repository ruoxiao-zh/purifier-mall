<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = factory(\App\Models\Product::class)->times(300)->create();
        foreach ($products as $product) {
            $skus = factory(\App\Models\ProductSku::class)->times(10)->create(['product_id' => $product->id]);
            $product->update(['price' => $skus->min('price')]);
        }
    }
}
