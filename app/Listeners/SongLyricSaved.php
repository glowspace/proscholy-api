<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\SongLyricSaved as SongLyricSavedEvent;

use App\Helpers\Chord;
use App\Helpers\ChordSign;
use App\Helpers\ChordQueue;
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
        // 1. lyrics has changed -> recache
        // 2. formatted_lyrics hasn't been set yet
        if ($event->song_lyric->lyrics !== $event->song_lyric->getOriginal('lyrics')
            || $event->song_lyric->formatted_lyrics == NULL) 
        {
            // temporarily disable eventing
            $dispatcher = SongLyric::getEventDispatcher();
            SongLyric::unsetEventDispatcher();
    
            $event->song_lyric->update([
                'formatted_lyrics' => $this->getFormattedLyrics($event->song_lyric),
                'has_chords' => $this->hasChords($event->song_lyric)
            ]);
                
            // and back enabling the event dispatcher
            SongLyric::setEventDispatcher($dispatcher);

            Log::info("lyrics recached");
        }
    }

    private function hasChords($song_lyric)
    {
        if (strpos($song_lyric->lyrics, '[') === false) {
            return false;
        }

        return true;
    }

    // FOR THE NEW FRONTEND VIEWER
    private function getFormattedLyrics($song_lyric)
    {
        $lines = explode("\n", $song_lyric->lyrics);

        $output = "";
        $chordQueue = new ChordQueue();

        foreach ($lines as $line){
            $output .= '<div class="song-line">'.$this->processLine($line, $chordQueue).'</div>';
        }

        return $output;
    }
 
    private function processLine($line, $chordQueue)
    {
        $chords = array();
        $currentChordText = "";
        $line = trim($line);
        
        // starting of a line, notify Chord "repeater" if we are in a verse
        if (strlen($line) > 0 && is_numeric($line[0])) {
            $chordQueue->notifyVerse($line[0]);
        }

        for ($i = 0; $i < strlen($line); $i++) {
            if ($line[$i] == "["){
                if ($currentChordText != "")
                    $chords[] = Chord::parseFromText($currentChordText, $chordQueue);
                $currentChordText = "";
            }

            $currentChordText .= $line[$i];
        }

        $chords[] = Chord::parseFromText($currentChordText, $chordQueue);

        $string = "";
        foreach ($chords as $chord) 
            $string .= $chord->toHTML();

        return $string;
    }
}
