<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            UserRolesPermissionsSeeder::class,

            SongSeeder::class,
            TagsSeeder::class,
            ExternalsSeeder::class,
            AuthorSeeder::class,

            AuthorSongSeeder::class,
            ExternalSongSeeder::class,
            
            AuthorTagSeeder::class,
            SongTagSeeder::class,
            ExternalTagSeeder::class,
        ]);
    }
}
