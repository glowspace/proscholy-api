<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ExternalMediaLink
{
    protected $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function urlAsSpotify()
    {
        // spotify:track:3X7QBr7rq6NIzLmEXbiXAS
        $uri_prefix = "spotify:track:";
        // https://open.spotify.com/track/2nwCO1PqpvyoFIvq3Vrj8N?si=kpz8FS1zSYG7dKv12kU1kA
        $link_prefix = preg_quote("https://open.spotify.com/track/", "/");

        // check if it's a valid Spotify URI or link
        if (!preg_match("/($uri_prefix|$link_prefix)([^\?]+)/", $this->url, $groups)) {
            return false;
        }

        return $groups[2];

        // return "https://open.spotify.com/embed/track/$groups[2]";
    }

    public function urlAsYoutube()
    {
        $short = preg_quote("https://youtu.be/", '/');
        $long = preg_quote("https://www.youtube.com/watch?v=", '/');

        if (!preg_match("/($short|$long)(.+)/", $this->url, $groups)) {
            return false;
        }

        $code = str_replace("t=", "start=", $groups[2]);

        return $code;

        // return "https://www.youtube.com/embed/$groups[2]";
    }

    public function urlAsSoundcloud()
    {
        $prefix = preg_quote("https://soundcloud.com/", "/");
        if (!preg_match("/$prefix(.+)/", $this->url)) {
            return false;
        }

        return $this->url;

        // return "https://w.soundcloud.com/player/?url=$this->url
        //     &color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true";
    }

    public function getExternalMediaType()
    {
        // external services
        if ($this->urlAsSpotify($this->url)) return "spotify";
        if ($this->urlAsSoundcloud($this->url)) return "soundcloud";
        if ($this->urlAsYoutube($this->url)) return "youtube";

        // has the url a file extension..?
        if (preg_match("/\/.*\.(\w+)$/", $this->url, $groups)) {
            $extension = $groups[1];
            $extension = str_replace('jpg', 'jpeg', $extension);

            if (!in_array($groups[1], ['com', 'cz', 'sk', 'org'])) {
                return "file/$groups[1]";
            }
        }

        // todo: we could try to get the info from header response

        return "";
    }

    public function getExternalContentType()
    {
        $media_type = $this->getExternalMediaType();

        if (in_array($media_type, ['spotify', 'soundcloud', 'file/mp3', 'file/wav', 'file/aac', 'file/flac', 'youtube', 'file/mp4', 'file/mkv'])) {
            return 1;
        }

        if (in_array($media_type, ['file/pdf', 'file/jpeg', 'file/png', 'file/gif', 'file/musx'])) {
            return 2;
        }

        if (in_array($media_type, ['file/doc', 'file/docx'])) {
            return 3;
        }

        return 0;
    }
}
