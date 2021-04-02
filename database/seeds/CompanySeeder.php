<?php

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( !Company::query()->find(1)) {
            factory(Company::class)->times(1)->create();
        }
    }
}
