<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LilypondService;

class LilypondServiceTest extends TestCase
{
    public function testSvg()
    {
        $llservice = new LilypondService();
        $res = $llservice->makeSvg('{ c }', false);

        logger($res);
    }

    public function testSvgCrop()
    {
        $llservice = new LilypondService();
        $res = $llservice->makeSvg('{ c }');

        logger($res);
    }
}
