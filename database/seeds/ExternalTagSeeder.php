<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\External;

class ExternalTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        foreach (Tag::$external_types as $sl_type) {
//            foreach (External::all()->random(20) as $external) {
//                $external->tags()->sync(
//                    Tag::whereType($sl_type)->get()->random(2)
//                );
//            }
//        }
    }
}
