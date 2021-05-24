<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LilypondClientService;

class LilypondClientServiceTest extends TestCase
{
    public function testSvg()
    {
        $llservice = new LilypondClientService();
        $res = $llservice->makeSvgFast('{ c }');

        logger($res);
    }

    public function testSvgLog()
    {
        $llservice = new LilypondClientService();
        $res = $llservice->makeSvgFast('{ c ');

        logger($res);
    }


    // public function testSvgCrop()
    // {
    //     $llservice = new LilypondClientService();
    //     $res = $llservice->makeSvg('{ c }');

    //     logger($res);
    // }
}
