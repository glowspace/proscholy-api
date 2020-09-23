<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\ExternalCreated as ExternalCreatedEvent;

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
        $media_type = $event->external->guessMediaType();

        $event->external->update([
            // todo remove type
            'type' => $event->external->guessType(),
            'is_uploaded' => Str::contains($event->external->url, url('')),
            'media_type' => $media_type,
            'content_type' => $event->external->guessContentType($media_type)
        ]);
    }
}
