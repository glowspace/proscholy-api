<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\FileDeleting' => [
            'App\Listeners\FileDeleting'
        ],
        'App\Events\SongLyricSaved' => [
            'App\Listeners\SongLyricSaved'
        ],
        'App\Events\SongLyricCreated' => [
            'App\Listeners\SongLyricCreated'
        ],
        'App\Events\ExternalCreated' => [
            'App\Listeners\ExternalCreated'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
