<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\SongLyricSaved as SongLyricSavedEvent;

use App\SongLyric;
use Log;


class SongLyricSaved
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
    public function handle(SongLyricSavedEvent $event)
    {
        // CAUSES FOR RECACHING
        // 1. and only: lyrics has changed -> recache
        if ($event->song_lyric->lyrics !== $event->song_lyric->getOriginal('lyrics')) 
        {
            // temporarily disable eventing
            $dispatcher = SongLyric::getEventDispatcher();
            SongLyric::unsetEventDispatcher();
    
            $event->song_lyric->update([
                'has_chords' => $this->hasChords($event->song_lyric)
            ]);
                
            // and back enabling the event dispatcher
            SongLyric::setEventDispatcher($dispatcher);
        }
    }

    private function hasChords($song_lyric)
    {
        if (strpos($song_lyric->lyrics, '[') === false) {
            return false;
        }

        return true;
    }
}
