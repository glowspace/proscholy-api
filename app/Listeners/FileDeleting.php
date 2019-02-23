<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Storage;
use App\Events\FileDeleting as FileDeletingEvent;

class FileDeleting
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
    public function handle(FileDeletingEvent $event)
    {
        Storage::delete($event->file->filename);
        \Log::info("deleted file ".$event->file->filename);
    }
}
