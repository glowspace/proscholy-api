<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\User;
use App\VisitAggregate;

class ComputeAggregateVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compute:visits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute visits stats and store to visits_aggregate table';

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
    public function handle()
    {
        $this->info('Counting the visits count data');
        $result = $this->countSongLyricVisits(Carbon::now()->subWeeks(1));

        $this->info('Updating the visits_aggregates table');
        $result->map(function ($row) {
            return [
                'visitable_id' => $row->id,
                'visitable_type' => 'App\SongLyric',
                'count_week' => $row->count_from,
                'count_total' => $row->count_total
            ];
        })
            ->chunk(1000)
            ->each(function ($chunk) {
                VisitAggregate::upsert($chunk->toArray(), ['visitable_id', 'visitable_type']);
            });

        return 0;
    }

    private function countSongLyricVisits(Carbon $from)
    {
        $res = DB::select(DB::raw(
            "SELECT
                id,
                (select count(*) from visits 
                    where song_lyrics.id = visits.visitable_id and visits.visitable_type = \"App\\\SongLyric\") 
                as count_total,
                (select count(*) from visits 
                    where song_lyrics.id = visits.visitable_id and visits.visitable_type = \"App\\\SongLyric\"
                        and created_at > :from)
                as count_from
                from song_lyrics where song_lyrics.deleted_at is null
            "
        ), [
            // 'visit_type' => $visit_type,
            'from' => $from->format('Y-m-d H:i:s')
        ]);

        return collect($res);
    }
}
