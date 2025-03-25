<?php

namespace App\Console\Commands;

use App\Jobs\AlertStock;
use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\Warehouse;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TriggerAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trigger-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'triggers alerts programmed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDateTime = Carbon::now();
        echo "time : ".$currentDateTime->format('H:i')."\n";
        echo "Date : ".$currentDateTime->toDateString()."\n";
        $alerts = Alert::where(function ($query) use ($currentDateTime) {
            $query->where('alerts.time','<=', $currentDateTime->format('H:i'))
                ->where('alerts.every_day', 1);
        })
            ->orWhere('date','<=', $currentDateTime->toDateString())
            ->join('historique_alerts', function ($join) {
                $join->on('alerts.id', '=', 'historique_alerts.idalert');
                $join->where('historique_alerts.status', 0);
                $join->orwhere(function($q){
                    $q->where('historique_alerts.status', 2);
                    $q->where('historique_alerts.attempts','<', 3);
                });
            })
            ->where("alerts.status", 1)
            ->with(["type"])
            ->get();
        echo "*********** count_alerts : ".count($alerts)."\n"; ;
        if (count($alerts) > 0) {
            foreach ($alerts as $alert) {
                $alert_history=AlertHistory::where("idalert",$alert->id)->first();
                if(isset($alert_history)){
                    $alert_history->attempts=intval($alert_history->attempts)+1;
                    $alert_history->status=1;
                    $alert_history->save();
                }else{
                    AlertHistory::create([
                        'idalert' => $alert->id,
                        'iduser' => $alert->iduser,
                        'attempts' => 1,
                        "status"=>1
                    ]);
                }
                echo "*********** alert_type : ".$alert->type->slug."\n";
                if (isset($alert->type->slug) && $alert->type->slug == "expired-stock") {
                    echo "*********** alert expired_stock trigger  ID: ".$alert->id."\n"; ;
                    dispatch((new AlertStock($alert->id))->onQueue('alert-stock')->onConnection('database'));

                }
            }

        }
    }
}