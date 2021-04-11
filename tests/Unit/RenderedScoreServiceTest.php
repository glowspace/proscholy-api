<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LilypondService;
use App\Services\RenderedScoreService;

use Illuminate\Support\Facades\Storage;

class RenderedScoreServiceTest extends TestCase
{
    protected RenderedScoreService $rs_service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rs_service = new RenderedScoreService();
    }

    public function testConfigHashSwappedOrder()
    {
        $config1 = [
            'foo' => 'bar',
            'hide_voices' => ['men', 'women']
        ];

        $config2 = [
            'hide_voices' => ['men', 'women'],
            'foo' => 'bar'
        ];

        $this->assertEquals($this->rs_service->getRenderConfigHash($config1), $this->rs_service->getRenderConfigHash($config2));
    }

    public function testConfigHashSwappedValueItems()
    {
        $config1 = [
            'hide_voices' => ['men', 'women']
        ];

        $config2 = [
            'hide_voices' => ['women', 'men'],
        ];

        $this->assertEquals($this->rs_service->getRenderConfigHash($config1), $this->rs_service->getRenderConfigHash($config2));
    }

    public function testMakeFileUnqiue()
    {
        $filename = $this->rs_service->makeFile('test_content', 'txt');

        $fpath = Storage::path("rendered_scores/$filename.txt");

        $this->assertFileExists($fpath);
        $this->assertFileIsReadable($fpath);
        $this->assertStringEqualsFile($fpath, 'test_content');

        $res = Storage::delete("rendered_scores/$filename.txt");
        $this->assertTrue($res);
    }

    public function testMakeFileSpecific()
    {
        $this->rs_service->makeFile('test_content', 'svg', 'score');
        $this->rs_service->makeFile('test_content', 'midi', 'score');

        $fpath_noext = Storage::path("rendered_scores/score");

        $this->assertFileExists("$fpath_noext.svg");
        $this->assertFileExists("$fpath_noext.midi");
    }

    /**
     * @depends testMakeFileSpecific
     */
    public function testDeleteFiles()
    {
        $r = $this->rs_service->deleteFile('score', 'svg');
        $r2 = $this->rs_service->deleteFile('score', 'midi');

        $this->assertTrue($r);
        $this->assertTrue($r2);
    }
}
