<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTablesSeeder::class);
        $this->call(SlideshowSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(MakeMoneyTipSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UserAddressSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
