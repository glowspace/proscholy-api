<?php

namespace App\Jobs;

use App\External;
use App\Services\ExternalMusicXmlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class RenderExternalMusicXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $external_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($external_id)
    {
        $this->external_id = $external_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExternalMusicXmlService $mxml_service)
    {
        $external = External::find($this->external_id);
        $mxml_service->renderExternalMusicXml($external);
    }
}
