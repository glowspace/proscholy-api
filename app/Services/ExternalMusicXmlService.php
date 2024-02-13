<?php

namespace App\Services;

use App\External;
use Illuminate\Support\Facades\Storage;

class ExternalMusicXmlService
{
    protected LilypondClientService $ly_service;
    protected RenderedScoreService $rs_service;

    public function __construct(LilypondClientService $ly_service, RenderedScoreService $rs_service)
    {
        $this->ly_service = $ly_service;   
        $this->rs_service = $rs_service;
    }

    public static function isMediaTypeRenderable(string $media_type) {
        return $media_type == 'file/xml' || $media_type == 'file/mxml';
    }

    // is called by RenderExternalMusicXml job
    public function renderExternalMusicXml(External $external) 
    {
        if (!self::isMediaTypeRenderable($external->media_type)) {
            logger('ExternalMusicXmlService: Not rendering, expected media type file/xml or file/mxml, but got ' . $external->media_type);
            return;
        }

        $contents = file_get_contents(Storage::path($external->filepath));
        $data = $this->ly_service->doClientRenderSvgFromXml($contents);

        $this->rs_service->createMusicXmlRenderedScore($external, $data['svg']);
    }
}