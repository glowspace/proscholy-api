<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalMusicXmlService;
use App\External;

class RenderAllMusicxml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'musicxml:render-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forces all Externals (MusicXML) to re-render';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ExternalMusicXmlService $serv)
    {
        // todo: also filter by content type? (when there are more xmls than just sheet music)

        $exts = External::where('url', 'like', '%.xml')->get();

        foreach ($exts as $ext) {
            echo("External ID $ext->id for song_lyric_id $ext->song_lyric_id");
            $ext->rendered_scores()->delete();
            $serv->renderExternalMusicXml($ext);
        }
    }
}

