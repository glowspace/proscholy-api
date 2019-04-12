<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\External;

class ExternalUrlTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testYoutube()
    {
        $this->assertEquals("0d2zOrebuwM",
            External::urlAsYoutube("https://www.youtube.com/watch?v=0d2zOrebuwM")
        );
        $this->assertEquals("0d2zOrebuwM",
            External::urlAsYoutube("https://youtu.be/0d2zOrebuwM")
        );
        $this->assertEquals("0d2zOrebuwM?start=2",
            External::urlAsYoutube("https://youtu.be/0d2zOrebuwM?t=2")
        );

        // <iframe width="560" height="315" src="https://www.youtube.com/embed/L_XJ_s5IsQc?start=361"
        //      frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    }

    public function testSoundcloud()
    {
        $this->assertNotFalse(
            External::urlAsSoundcloud("https://soundcloud.com/mirek-p-ikryl/chapela-blahoslaven-chud-v")
        );
    }

    public function testSpotify()
    {
        $this->assertNotFalse(
            External::urlAsSpotify("spotify:track:3X7QBr7rq6NIzLmEXbiXAS")
        );
        $this->assertNotFalse(
            External::urlAsSpotify("https://open.spotify.com/track/2nwCO1PqpvyoFIvq3Vrj8N?si=kpz8FS1zSYG7dKv12kU1kA")
        );
    }
}
