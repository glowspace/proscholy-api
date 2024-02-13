<?php

namespace App\Listeners;


use App\Events\ExternalCreated as ExternalCreatedEvent;
use App\Helpers\ExternalMediaLink;
use App\Jobs\RenderExternalMusicXml;
use App\Services\ExternalMusicXmlService;
use Illuminate\Support\Str;

class ExternalCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ExternalCreatedEvent $event)
    {
        $media_link = new ExternalMediaLink($event->external->url);
        $media_type = $media_link->getExternalMediaType();

        $event->external->update([
            'is_uploaded' => Str::contains($event->external->url, url('')),
            'media_type' => $media_type,
            'content_type' => $media_link->getExternalContentType()
        ]);

        if (ExternalMusicXmlService::isMediaTypeRenderable($media_type)) {
            RenderExternalMusicXml::dispatch($event->external->id);
        }
    }
}
