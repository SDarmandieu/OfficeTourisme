<?php

use Illuminate\Database\Seeder;

class PoisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pois = factory(App\Poi::class, 10)->create();

    }
}
