<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

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

        User::create([
            'name' => 'Jan Novák',
            'email' => 'admin@admin.com',
            'password' => Hash::make('secret')
        ]);
    }
}
