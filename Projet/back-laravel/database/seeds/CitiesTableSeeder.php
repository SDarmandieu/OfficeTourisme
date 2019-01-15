<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\City::class, 7)->create()->each(function ($city) {
            for ($i = 0; $i < 7; $i++) {
                $city->points()->save(factory(App\Point::class)->make());
            }
        });
    }
}
