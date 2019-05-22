<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\ExternalCreated as ExternalCreatedEvent;

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
        $event->external->update([
            'type' => $event->external->guessType()
        ]);
    }
}
