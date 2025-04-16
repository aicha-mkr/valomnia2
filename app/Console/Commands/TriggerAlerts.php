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
  // app/Console/Commands/TriggerAlerts.php
  public function handle()
  {
    $currentDateTime = Carbon::now();
    echo "time : " . $currentDateTime->format('H:i') . "\n";
    echo "Date : " . $currentDateTime->toDateString() . "\n";

    $alerts = Alert::where(function ($query) use ($currentDateTime) {
      $query->where('alerts.time', '<=', $currentDateTime->format('H:i'))
        ->where('alerts.every_day', 1);
    })
      ->orWhere('alerts.date', '<=', $currentDateTime->toDateString())
      ->join('alerts_history', function ($join) {
        $join->on('alerts.id', '=', 'alerts_history.alert_id')
          ->where('alerts_history.status', 0)
          ->orWhere(function ($q) {
            $q->where('alerts_history.status', 2)
              ->where('alerts_history.attempts', '<', 3);
          });
      })
      ->where("alerts.status", 1)
      ->with(["type"])
      ->get();

    echo "*********** count_alerts : " . count($alerts) . "\n";

    if (count($alerts) > 0) {
      foreach ($alerts as $alert) {
        $alertHistory = AlertHistory::where("alert_id", $alert->id)->first();
        if (isset($alertHistory)) {
          $alertHistory->increment('attempts');
          $alertHistory->status = 1;
          $alertHistory->save();
        } else {
          AlertHistory::create([
            'alert_id' => $alert->id,
            'iduser' => $alert->user_id,
            'attempts' => 1,
            'status' => 1,
          ]);
        }

        echo "*********** alert_type : " . $alert->type->slug . "\n";

        if (isset($alert->type->slug)) {
          if ($alert->type->slug === "expired-stock") {
            echo "*********** alert expired_stock trigger ID: " . $alert->id . "\n";
            dispatch(new AlertStock($alert->id))->onQueue('alert-stock')->onConnection('database');
          } elseif ($alert->type->slug === "checkin-out-of-hours") {
            echo "*********** alert checkin-out-of-hours trigger ID: " . $alert->id . "\n";
            dispatch(new AlertCheckInOutOfHours($alert->id))->onQueue('alert-checkin')->onConnection('database');
          }
        }
      }
    }
  }
}
