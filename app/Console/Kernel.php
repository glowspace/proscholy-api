<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ComputeUserStats::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('compute:stats')
            ->dailyAt('22:00');

        $schedule->command('compute:visits')
            ->hourly();

        $scores_dir = Storage::path(config('lilypond.rendered_scores_dir'));
        $scores_zip = Storage::path(config('lilypond.rendered_scores_zip'));

        // create a zip of rendered svg scores
        $schedule->exec("cd $scores_dir && find -name \"*.svg\" -printf '%f\n' | tar -zcf $scores_zip -T -")->hourly();

        // update elastic data
        // todo: make elastic update automatically after data edit
        $schedule->exec('./elastic_update.sh')->daily();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
