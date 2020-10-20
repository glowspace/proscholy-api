<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\User;

class ComputeUserStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compute:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute user stats and store to stats_json column';

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
        $this->info('Running SQL query - since 09/01/2020');
        $res_start = $this->getResults(new Carbon('2020-09-01 0:0:0'));
        $this->info('Running SQL query - a month ago');
        $res_month = $this->getResults(Carbon::now()->subWeeks(4));
        $this->info('Running SQL query - two months ago');
        $res_twomonths = $this->getResults(Carbon::now()->subWeeks(8));

        $this->info('Storing results to DB');
        $this->storeResults($res_start, 'from_start');
        $this->storeResults($res_month, 'month');
        $this->storeResults($res_twomonths, 'two_months');

        return 1;
    }

    private function storeResults($results, $name)
    {
        // "name": "Miroslav Šerý"
        // +"user_id": 2
        // +"visits_short": "9"
        // +"visits_long": "9"


        foreach ($results as $result) {
            $this->storeUserResult($result->user_id, $name, [
                'visits_short' => $result->visits_short,
                'visits_long' => (int)$result->visits_long
            ]);
        }
    }

    private function storeUserResult($user_id, $key, $values)
    {
        $user = User::find($user_id);
        $data = json_decode($user->stats_json, true);
        if ($data === null) {
            $data = [];
        }
        $data[$key] = $values;
        logger($data);
        $user->update([
            'stats_json' => json_encode($data)
        ]);
    }

    private function getResults(Carbon $min_dt, ?Carbon $max_dt = null)
    {
        return DB::select(DB::raw(
            "SELECT 
                users.name, 
                users.id as user_id,
                sum(case when visit_type = 0 then 1 end) as visits_short,
                sum(case when visit_type = 1 then 1 end) as visits_long from 
                    (SELECT distinct 
                        revisionable_id,
                        user_id, 
                        revisions.created_at as revision_time,
                        date(visits.created_at) as visit_time,
                        visit_type,
                        visitable,
                        revisionable_type FROM `revisions`
                    join `visits` on visitable_id = revisionable_id and visitable = revisionable_type and visits.created_at > revisions.created_at
                    where revisions.created_at > :datetime) as stats
                join users on user_id = users.id
                group by user_id
                order by count(*) desc"
        ), [
            'datetime' => $min_dt->format('Y-m-d H:i:s'),
        ]);
    }
}
