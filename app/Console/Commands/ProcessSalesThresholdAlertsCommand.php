<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\TypeAlert;
use App\Jobs\ProcessSalesThresholdAlertJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessSalesThresholdAlertsCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = "alerts:process-sales-threshold {alert_id?}";

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = "Process active sales threshold alerts. Optionally process a specific alert by ID.";

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    Log::info("[ProcessSalesThresholdAlertsCommand] Starting command.");
    $specificAlertId = $this->argument("alert_id");

    if ($specificAlertId) {
      $alert = Alert::with("type")->find($specificAlertId);
      if ($alert && $alert->type && $alert->type->slug === "vente-seuil-depasse-pdv") {
        if ($alert->status) {
          Log::info("[ProcessSalesThresholdAlertsCommand] Dispatching job for specific alert ID: {$alert->id}");
          ProcessSalesThresholdAlertJob::dispatch($alert->id);
          $this->line("Job dispatched for specific alert ID: {$alert->id}");
        } else {
          Log::warning("[ProcessSalesThresholdAlertsCommand] Specific alert ID: {$specificAlertId} is inactive. Skipping.");
          $this->warn("Specific alert ID: {$specificAlertId} is inactive. Skipping.");
        }
      } else {
        Log::warning("[ProcessSalesThresholdAlertsCommand] Specific alert ID: {$specificAlertId} not found or not a sales threshold alert. Skipping.");
        $this->error("Specific alert ID: {$specificAlertId} not found or not a sales threshold alert. Skipping.");
      }
    } else {
      $currentDateTime = Carbon::now();
      $alerts = Alert::where("status", 1)
        ->whereHas("type", function ($query) {
          $query->where("slug", "vente-seuil-depasse-pdv");
        })
        ->where(function ($query) use ($currentDateTime) {
          $query->where("every_day", 1)
            ->where("time", "<=", $currentDateTime->format("H:i:s"));
        })
        ->orWhere(function ($query) use ($currentDateTime) {
          $query->where("every_day", 0)
            ->where("date", "<=", $currentDateTime->toDateString())
            ->where("time", "<=", $currentDateTime->format("H:i:s"))
            ->whereHas("type", function ($q) {
              $q->where("slug", "vente-seuil-depasse-pdv"); // Ensure type is correct for this part of OR too
            })
            ->where("status", 1); // Ensure status is active for this part of OR too
        })
        ->get();

      $count = $alerts->count();
      Log::info("[ProcessSalesThresholdAlertsCommand] Found {$count} active and due sales threshold alerts to process.");
      $this->info("Processing {$count} sales threshold alerts.");

      foreach ($alerts as $alert) {
        Log::info("[ProcessSalesThresholdAlertsCommand] Dispatching job for alert ID: {$alert->id}");
        ProcessSalesThresholdAlertJob::dispatch($alert->id);
        $this->line("Job dispatched for alert ID: {$alert->id}");
      }
    }

    Log::info("[ProcessSalesThresholdAlertsCommand] Command finished.");
    $this->info("Sales threshold alert processing finished.");
    return Command::SUCCESS;
  }
}

