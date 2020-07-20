<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\Author;

class AuthorTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Tag::$author_types as $sl_type) {
            foreach (Author::all()->random(20) as $author) {
                $author->tags()->sync(
                    Tag::whereType($sl_type)->get()->random(rand(1,2))
                );
            }
        }
    }
}
