<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\EmailTemplate;
use App\Services\Valomnia\ValomniaService; // Assuming you have a service for Valomnia API calls
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericAlertMail; // A new Mailable or adapt existing one
use Carbon\Carbon;
use Exception;

class ProcessSalesThresholdAlertJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $alertId;

  /**
   * Create a new job instance.
   *
   * @param int $alertId
   */
  public function __construct($alertId)
  {
    $this->alertId = $alertId;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(ValomniaService $valomniaService) // Inject ValomniaService
  {
    Log::info("[ProcessSalesThresholdAlertJob] Processing alert ID: {$this->alertId}");

    try {
      $alert = Alert::with("type", "user", "template")->find($this->alertId);

      if (!$alert) {
        Log::error("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId} not found.");
        return;
      }

      if ($alert->type->slug !== "vente-seuil-depasse-pdv") {
        Log::warning("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId} is not of type 'vente-seuil-depasse-pdv'. Skipping.");
        return;
      }

      $params = json_decode($alert->parameters, true);
      if (empty($params) ||
        !isset($params["customer_reference"]) ||
        !isset($params["responsable_email"]) ||
        !isset($params["periode_moyenne_jours"]) ||
        !isset($params["seuil_pourcentage"])) {
        Log::error("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId} has invalid or missing parameters.", ["params" => $params]);
        $this->recordHistory($alert->id, $alert->user_id, false, "Invalid or missing parameters");
        return;
      }

      $customerReference = $params["customer_reference"];
      $responsableEmail = $params["responsable_email"];
      $periodeJours = (int)$params["periode_moyenne_jours"];
      $seuilPourcentage = (float)$params["seuil_pourcentage"];

      // 1. Fetch historical sales data
      $dateTo = Carbon::now()->subDay(); // Sales up to yesterday
      $dateFrom = $dateTo->copy()->subDays($periodeJours -1); // -1 because subDay already counts for one day

      Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. Fetching historical sales for {$customerReference} from {$dateFrom->toDateString()} to {$dateTo->toDateString()}.");

      // Assuming ValomniaService has a method to get operations
      // You'll need to adapt this to your actual ValomniaService implementation
      $historicalSalesResponse = $valomniaService->getOperations([
        "operationType" => "ORDER",
        "customerReference" => $customerReference,
        "dateCreated_gte" => $dateFrom->format("Y-m-d"), // Valomnia might expect different date format
        "dateCreated_lte" => $dateTo->format("Y-m-d"),
        "max" => 1000, // Adjust as needed, handle pagination if necessary
        // Add other necessary params like user_id, organisation, cookies if your service needs them
        "user_id" => $alert->user->id, // Or the relevant user for API calls
        "organisation" => $alert->user->organisation, // Or the relevant org
        "cookies" => $alert->user->cookies // Or the relevant cookies
      ]);

      if (isset($historicalSalesResponse["error"])) {
        Log::error("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. API error fetching historical sales: ", (array)$historicalSalesResponse["error"]);
        $this->recordHistory($alert->id, $alert->user_id, false, "API error fetching historical sales");
        return;
      }

      $historicalSales = $historicalSalesResponse["data"] ?? [];
      $totalHistoricalAmount = 0;
      $historicalSalesCount = count($historicalSales);

      foreach ($historicalSales as $sale) {
        $totalHistoricalAmount += (float)($sale["total"] ?? 0);
      }

      if ($historicalSalesCount === 0) {
        Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. No historical sales found for {$customerReference} in the period. Skipping threshold check.");
        $this->recordHistory($alert->id, $alert->user_id, true, "No historical sales data for period.");
        return;
      }

      $averageHistoricalSale = $totalHistoricalAmount / $historicalSalesCount;
      Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. Average historical sale for {$customerReference}: {$averageHistoricalSale} (Count: {$historicalSalesCount})");

      // 2. Fetch current sales data (e.g., sales from today or yesterday, depending on job schedule)
      // For this example, let's assume we check sales made "today" (or "yesterday" if the job runs at night)
      $currentSalesDate = Carbon::now(); // Or Carbon::yesterday() if job runs after midnight for previous day's sales

      Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. Fetching current sales for {$customerReference} for date: {$currentSalesDate->toDateString()}.");
      $currentSalesResponse = $valomniaService->getOperations([
        "operationType" => "ORDER",
        "customerReference" => $customerReference,
        "dateCreated_gte" => $currentSalesDate->copy()->startOfDay()->format("Y-m-d H:i:s"), // Valomnia date format
        "dateCreated_lte" => $currentSalesDate->copy()->endOfDay()->format("Y-m-d H:i:s"),
        "max" => 100, // Adjust as needed
        "user_id" => $alert->user->id,
        "organisation" => $alert->user->organisation,
        "cookies" => $alert->user->cookies
      ]);

      if (isset($currentSalesResponse["error"])) {
        Log::error("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. API error fetching current sales: ", (array)$currentSalesResponse["error"]);
        $this->recordHistory($alert->id, $alert->user_id, false, "API error fetching current sales");
        return;
      }

      $currentSales = $currentSalesResponse["data"] ?? [];
      $salesExceedingThreshold = [];

      if (empty($currentSales)) {
        Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. No current sales found for {$customerReference} for date: {$currentSalesDate->toDateString()}. Nothing to compare.");
        $this->recordHistory($alert->id, $alert->user_id, true, "No current sales to compare.");
        return;
      }

      $thresholdMultiplier = $seuilPourcentage / 100;
      $triggerThreshold = $averageHistoricalSale * $thresholdMultiplier;

      foreach ($currentSales as $sale) {
        $currentSaleAmount = (float)($sale["total"] ?? 0);
        if ($currentSaleAmount > $triggerThreshold) {
          $salesExceedingThreshold[] = [
            "id" => $sale["id"],
            "reference" => $sale["reference"],
            "date" => Carbon::parse($sale["dateCreated"])->format("Y-m-d H:i:s"),
            "amount" => $currentSaleAmount,
            "percentage_of_average" => ($averageHistoricalSale > 0) ? round(($currentSaleAmount / $averageHistoricalSale) * 100, 2) : "N/A"
          ];
        }
      }

      if (!empty($salesExceedingThreshold)) {
        Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. Sales exceeding threshold found for {$customerReference}. Count: " . count($salesExceedingThreshold));
        // 3. Send notification
        $emailTemplate = $alert->template;
        if (!$emailTemplate) {
          Log::error("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. Email template not found.");
          $this->recordHistory($alert->id, $alert->user_id, false, "Email template not found");
          return;
        }

        $emailData = [
          "alert_title" => $alert->title,
          "alert_description" => $alert->description,
          "alert_type_slug" => "vente-seuil-depasse-pdv", // For template logic
          "point_de_vente_ref" => $customerReference,
          "moyenne_ventes_historique" => round($averageHistoricalSale, 2),
          "seuil_pourcentage_configure" => $seuilPourcentage,
          "periode_historique_jours" => $periodeJours,
          "liste_ventes_depassement" => $salesExceedingThreshold, // Array of sales
          // Add any other common placeholders your template might need
          "recipient_name" => $alert->user->name, // Or derive from $responsableEmail if possible
        ];

        // Use a generic Mailable or adapt your existing one
        Mail::to($responsableEmail)->send(new GenericAlertMail($emailTemplate->subject, $emailTemplate->content, $emailData));
        Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. Notification email sent to {$responsableEmail}.");
        $this->recordHistory($alert->id, $alert->user_id, true, "Notification sent for sales exceeding threshold.");
      } else {
        Log::info("[ProcessSalesThresholdAlertJob] Alert ID: {$this->alertId}. No sales exceeded the threshold for {$customerReference}.");
        $this->recordHistory($alert->id, $alert->user_id, true, "No sales exceeded threshold.");
      }

    } catch (Exception $e) {
      Log::error("[ProcessSalesThresholdAlertJob] Error processing alert ID {$this->alertId}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      if (isset($alert) && isset($alert->user)) {
        $this->recordHistory($this->alertId, $alert->user_id, false, "Job execution failed: " . $e->getMessage());
      } else {
        $this->recordHistory($this->alertId, null, false, "Job execution failed before alert/user loaded: " . $e->getMessage());
      }
    }
  }

  private function recordHistory($alertId, $userId, $status, $message = null)
  {
    try {
      AlertHistory::create([
        "alert_id" => $alertId,
        "iduser" => $userId, // Can be null if user not loaded
        "status" => $status ? 1 : 0, // 1 for success/processed, 0 for failure
        "attempts" => 1, // Or increment if you have retry logic
        "response" => $message,
        "last_execution_date" => Carbon::now(),
      ]);
    } catch (Exception $e) {
      Log::error("[ProcessSalesThresholdAlertJob] Failed to record alert history for alert ID {$alertId}: " . $e->getMessage());
    }
  }
}

