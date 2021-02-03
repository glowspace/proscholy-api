<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LilypondService;

class LilypondServiceTest extends TestCase
{
    protected LilypondService $llservice;

    public function boot()
    {
        $llservice = new LilypondService();
    }

    public function testMake()
    {
        $this->llservice->getLilypondSvg('{ c }');
    }
}
