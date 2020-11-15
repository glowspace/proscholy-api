<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers\ChordSign;

class ChordSignTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testIs()
    {
        $this->assertEquals(
            'C#',
            ChordSign::parseFromText('Cis7')->getBase()
        );
    }

    public function testEs()
    {
        $this->assertEquals(
            'Db',
            ChordSign::parseFromText('Des7')->getBase()
        );
    }

    public function test47()
    {
        $this->assertEquals(
            '4/7',
            ChordSign::parseFromText('C#4/7')->getExtension()
        );
        $this->assertEquals(
            '',
            ChordSign::parseFromText('C#4/7')->getBassNote()
        );
    }

    public function testSus()
    {
        $this->assertEquals(
            'A',
            ChordSign::parseFromText('Asus4')->getBase()
        );
    }
}
