<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\ExternalCreated as ExternalCreatedEvent;
use App\Helpers\ExternalMediaLink;
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

        $event->external->update([
            'is_uploaded' => Str::contains($event->external->url, url('')),
            'media_type' => $media_link->getExternalMediaType(),
            'content_type' => $media_link->getExternalContentType()
        ]);
    }
}
