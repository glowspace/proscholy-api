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
        // if current user cannot publish songs-> the song is not published yet
        // and is waiting for approval
        $user = Auth::user();

        if ($user) {
            $event->song_lyric->update([
                'is_published' => $user->can('publish songs'),
                'user_creator_id' => $user->id
            ]);
        }
    }
}
