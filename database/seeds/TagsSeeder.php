<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->where('id', '>', 0)->delete();

        $tags_by_types = [
            0 => [
                "za jednu notu křesťanů", "po dobrém jídle"
            ],
            1 => [
                "vstup", "výstup", "závěrečné požehnání"
            ],
            2 => [
                "Vánoce", "Velikonoce", "karanténa"
            ],
            3 => [
                "Sv. Gorazd", "Sv. Discord"
            ],
            4 => [
                "evangelijní moteto", "státní hymna"
            ],
            5 => [
                "obřízka", "žarošická pouť"
            ],
            6 => [
                "láska", "naděje", "víra"
            ],
            10 => [
                "Doba kamenná", "Doba bronzová", "Velký třesk"
            ],
            40 => [
                'Svátek sv. rodiny', 'Nějaká random památka', 'Dařbujána a Pandrholy'
            ],
            50 => [
                "Kazoo", "Fujara", "Citera"
            ],
            100 => [
                "hard rock", "alternative indie folk"
            ]
        ];

        foreach ($tags_by_types as $type => $type_names) {
            foreach ($type_names as $name) {
                App\Tag::create([
                    'name' => $name,
                    'type' => $type
                ]);
            }
        }
    }
}
