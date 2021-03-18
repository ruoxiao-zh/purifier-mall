<?php

use Illuminate\Database\Seeder;

class MakeMoneyTipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\MakeMoneyTip::class)->times(100)->create();
    }
}
