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
        factory(App\City::class, 7)->create()
            ->each(function ($city) {
                $city->points()->saveMany(factory(App\Point::class, 7)->make());

                $city->images()->saveMany(factory(App\Image::class, 10)->make())
                    ->each(function ($image) {
                        if ($image->imagetype_id == 2) {
                            $game = factory(App\Game::class)->make();
                            $game->city_id = $image->city_id;
                            $image->games()->save($game);

                            $points = App\Point::where('city_id', '=', $image->city_id)->pluck('id')->toArray();

                            $game->points()->attach($points);
                        }
                    });
            });
    }
}
