<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

use App\Events\SongLyricCreated as SongLyricCreatedEvent;

use App\SongLyric;
use Log;



class SongLyricCreated
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
    public function handle(SongLyricCreatedEvent $event)
    {
        // todo: move to a Service/Manager class

        $event->song_lyric->update([
            'song_number' => $event->song_lyric->id
        ]);

        $user = Auth::user();

        if ($user) {
            $event->song_lyric->update([
                'user_creator_id' => $user->id
            ]);
        }
    }
}
