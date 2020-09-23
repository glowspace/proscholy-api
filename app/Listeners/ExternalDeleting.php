<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Storage;
use App\Events\ExternalDeleting as ExternalDeletingEvent;

class ExternalDeleting
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
    public function handle(ExternalDeletingEvent $event)
    {
        if ($event->external->is_uploaded) {
            Storage::delete($event->external->filepath);
            logger("deleted file associated with external at path: " . $event->external->filepath);
        }
    }
}
