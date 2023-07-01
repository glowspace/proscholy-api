<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\User;
use App\Visit;
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
        $result = $this->countSongLyricVisits(Carbon::now()->subWeeks(1), Carbon::now()->subMonths(4));

        $this->info('Updating the visits_aggregates table');
        $result->map(function ($row) {
            return [
                'visitable_id' => $row->id,
                'visitable_type' => 'App\SongLyric',
                'count_after_prune' => $row->count_after_prune,
                'count_week' => $row->count_week,
                'count_pruned' => $row->count_pruned + $row->count_pruned_old
            ];
        })
            ->chunk(1000)
            ->each(function ($chunk) {
                VisitAggregate::upsert($chunk->toArray(), ['visitable_id', 'visitable_type']);
            });

        return 0;
    }

    private function countSongLyricVisits(Carbon $from, Carbon $prune_until)
    {
        $res = DB::select(DB::raw(
            "SELECT
                id,
                (select count(*) from visits 
                    where song_lyrics.id = visits.visitable_id and visits.visitable_type = \"App\\\SongLyric\"
                        and created_at > :prune_until_i) 
                as count_after_prune,
                (select count(*) from visits 
                    where song_lyrics.id = visits.visitable_id and visits.visitable_type = \"App\\\SongLyric\"
                        and created_at > :from)
                as count_week,
                (select count(*) from visits
                where song_lyrics.id = visits.visitable_id and visits.visitable_type = \"App\\\SongLyric\"
                        and created_at <= :prune_until)
                as count_pruned,

                -- we don't want to ovewrite the data in visit_aggregates.count_pruned but instead to add them up
                -- that's why we retrieve them here to add them wit count_pruned
                (select count_pruned from visit_aggregates
                where song_lyrics.id = visit_aggregates.visitable_id and visit_aggregates.visitable_type = \"App\\\SongLyric\")
                as count_pruned_old
                from song_lyrics where song_lyrics.deleted_at is null
            "
        ), [
            // 'visit_type' => $visit_type,
            'prune_until_i' => $prune_until->format('Y-m-d H:i:s'),
            'from' => $from->format('Y-m-d H:i:s'),
            'prune_until' => $prune_until->format('Y-m-d H:i:s'),
        ]);

        Visit::where('created_at', '<', $prune_until->format('Y-m-d H:i:s'))->delete();

        return collect($res);
    }
}
