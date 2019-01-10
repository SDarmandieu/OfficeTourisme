<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagetypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('imagetypes')->insert(['title' => 'logo']);
        DB::table('imagetypes')->insert(['title' => 'icon']);
        DB::table('imagetypes')->insert(['title' => 'game']);
    }
}
