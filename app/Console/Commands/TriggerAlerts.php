<?php

namespace App\Console\Commands;

use App\Jobs\AlertStock;
use App\Models\Alert;
use App\Models\AlertHistory;
use Illuminate\Console\Command;
use Carbon\Carbon;

class
TriggerAlerts extends Command
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
    protected $description = 'Triggers alerts programmed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDateTime = Carbon::now();
        echo "time : " . $currentDateTime->format('H:i') . "\n";
        echo "Date : " . $currentDateTime->toDateString() . "\n";

        // Fetch alerts based on conditions
        $alerts = Alert::where(function ($query) use ($currentDateTime) {
            $query->where('alerts.time', '<=', $currentDateTime->format('H:i'))
                  ->where('alerts.every_day', 1); // Daily alerts
        })
        ->orWhere('alerts.date', '<=', $currentDateTime->toDateString())
        ->join('alerts_history', function ($join) {
            $join->on('alerts.id', '=', 'alerts_history.alert_id'); // Correct table and column
            $join->where('alerts_history.status', 0); // Pending status
            $join->orWhere(function ($q) {
                $q->where('alerts_history.status', 2); // Failed attempts
                $q->where('alerts_history.attempts', '<', 3); // Retry limit
            });
        })
        ->where("alerts.status", 1) // Active alerts only
        ->with(["type"])
        ->get();

        echo "*********** count_alerts : " . count($alerts) . "\n";

        if (count($alerts) > 0) {
            foreach ($alerts as $alert) {
                $alertHistory = AlertHistory::where("alert_id", $alert->id)->first();
                if (isset($alertHistory)) {
                    $alertHistory->increment('attempts');
                    $alertHistory->status = 1; // Set status to "in progress"
                    $alertHistory->save();
                } else {
                    AlertHistory::create([
                        'alert_id' => $alert->id,
                        'iduser' => $alert->iduser,
                        'attempts' => 1,
                        'status' => 1, // Set status to "in progress"
                    ]);
                }

                echo "*********** alert_type : " . $alert->type->slug . "\n";

                if (isset($alert->type->slug) && $alert->type->slug === "expired-stock") {
                    echo "*********** alert expired_stock trigger ID: " . $alert->id . "\n";
                    dispatch(new AlertStock($alert->id))->onQueue('alert-stock')->onConnection('database');
                }
            }
        }
    }
}
