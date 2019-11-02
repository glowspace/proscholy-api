<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 1)->create();

        User::create([
            'name' => 'Mira',
            'email' => 'athes01@gmail.com',
            'password' => Hash::make('abcdefgh')
        ]);
    }
}
