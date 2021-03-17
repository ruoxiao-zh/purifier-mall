<?php

use Illuminate\Database\Seeder;

class SlideshowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Slideshow::class)->times(100)->create();
    }
}
