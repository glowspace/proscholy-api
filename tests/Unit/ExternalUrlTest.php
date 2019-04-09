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
        $this->assertEquals("https://www.youtube.com/embed/Zd9Pty7f6v8",
            External::urlAsYoutube("https://www.youtube.com/watch?v=Zd9Pty7f6v8")
        );
        $this->assertEquals("https://www.youtube.com/embed/Zd9Pty7f6v8",
            External::urlAsYoutube("https://youtu.be/Zd9Pty7f6v8")
        );
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

    // public function testYoutubeShort()
    // {
    // }

    // public function testSoundcloud()
    // {
    //     // $this->assertFalse(External::urlAsSoundcloud("https://www.youtube.com/watch?v=Zd9Pty7f6v8"));
    //     // $this->assertFalse(External::urlAsSoundcloud("https://www.youtu.be/Zd9Pty7f6v8"));
    //     // $this->assertNotFalse(External::urlAsSoundcloud("https://soundcloud.com/mirek-p-ikryl/chapela-blahoslaven-chud-v"));
    //     // $this->assertFalse(External::urlAsSoundcloud("spotify:track:3X7QBr7rq6NIzLmEXbiXAS"));
    // }

        // public static function quickTest()
    // {
    //     $urls = [
    //         "https://www.youtube.com/watch?v=Zd9Pty7f6v8", 
    //         "https://soundcloud.com/mirek-p-ikryl/chapela-blahoslaven-chud-v",
    //         "spotify:track:3X7QBr7rq6NIzLmEXbiXAS"];
    // }
}
