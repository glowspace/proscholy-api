<?php

namespace App\GraphQL\Queries;

use App\Song;

class News
{
    public function resolve($rootValue, array $args)
    {
        return [
            [
                'text' => 'dummy text',
                'fa_icon_class' => 'fa fa-key',
                'link' => '/o-zpevniku',
                'type' => 2
            ]
        ];
    }
}
