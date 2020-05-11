<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\External;

class ExternalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\External::class, 50)->create();
    }
}
