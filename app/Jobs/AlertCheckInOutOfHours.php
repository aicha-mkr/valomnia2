<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\EmailTemplate;
use App\Mail\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\CheckIn; // Ajout de l'importation correcte
use Throwable;

class AlertCheckInOutOfHours implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $alertId;

  public function __construct($alertId)
  {
    $this->alertId = $alertId;
  }

  public function handle(): void
  {
    Log::info("Démarrage du job AlertCheckInOutOfHours pour l'alerte ID: {$this->alertId}");

    $alert = Alert::with(['user', 'type'])->where("id", $this->alertId)->first();

    if (!$alert) {
      Log::error("Alerte non trouvée: {$this->alertId}");
      return;
    }

    echo "alert Id : {$alert->id}\n";
    echo "user_id : {$alert->user_id}\n";
    echo "organisation : " . ($alert->user->organisation ?? 'N/A') . "\n";

    if ($alert->status != 1 || !$alert->type || $alert->type->slug !== 'checkin-out-of-hours') {
      Log::info("❌ L'alerte ID {$this->alertId} n'est plus active ou n'est pas du type 'checkin-out-of-hours'. Annulation.");
      return;
    }

    Log::info("✅ Alerte ID {$this->alertId} active et de type 'checkin-out-of-hours'");

    // Extract employee reference
    $employeeReference = null;
    if (!empty($alert->parameters)) {
      try {
        $parameters = json_decode($alert->parameters, true);
        Log::info("Contenu de parameters: " . json_encode($parameters));
        if (json_last_error() === JSON_ERROR_NONE && isset($parameters['employee_ref'])) {
          $employeeReference = $parameters['employee_ref'];
          Log::info("✅ Référence employé extraite des paramètres: {$employeeReference}");
        } else {
          Log::warning("⚠️ Format JSON invalide ou clé 'employee_ref' manquante dans parameters: " . $alert->parameters);
        }
      } catch (Exception $e) {
        Log::error("❌ Erreur lors du décodage JSON des paramètres: " . $e->getMessage());
        return;
      }
    }

    if (empty($employeeReference)) {
      Log::error("❌ Référence employé manquante pour l'alerte ID {$this->alertId}. Vérifiez parameters ou la relation employee.");
      return;
    }

    if (!isset($alert->user->cookies) || empty($alert->user->cookies)) {
      Log::error("Cookies de session non disponibles pour l'utilisateur: " . $alert->user_id);
      return;
    }

    // Email template lookup
    Log::info("Recherche du template d'email...");
    $template = null;
    if (!empty($alert->template_id)) {
      $template = EmailTemplate::find($alert->template_id);
      Log::info("Tentative de chargement du template via alert.template_id ({$alert->template_id})");
    }
    if (!$template && isset($alert->type_id)) {
      Log::info("Template non trouvé via alert.template_id, tentative via alert.type_id ({$alert->type_id})");
      $template = EmailTemplate::where('type', 'Alert')
        ->where('alert_id', $alert->type_id)
        ->first();
    }
    if (!$template) {
      Log::info("Template non trouvé via alert.type_id, tentative avec le template 'Alert' par défaut");
      $template = EmailTemplate::where('type', 'Alert')->first();
    }

    if (!$template) {
      Log::error("Aucun template d'email trouvé pour l'alerte: {$this->alertId}");
      return;
    }

    Log::info("Template trouvé: {$template->id} - {$template->title}");

    // Fetch check-ins
    $timezone = config('app.timezone', 'Europe/Paris');
    $today = Carbon::now($timezone)->startOfDay();
    $endOfDay = Carbon::now($timezone)->endOfDay();

    $startDateFilter = $today->copy()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
    $endDateFilter = $endOfDay->copy()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');

    Log::info("Période de filtrage: de {$startDateFilter} à {$endDateFilter}");
    Log::info("Heure actuelle: " . Carbon::now($timezone)->format('Y-m-d H:i:s'));

    try {
      $allCheckIns = [];
      $offset = 0;
      $maxPages = 5;
      $processedOffsets = [0];
      $startTime = time();
      $timeLimit = 30;

      $apiParams = [
        'user_id' => $alert->user_id,
        'organisation' => $alert->user->organisation ?? 'default',
        'cookies' => $alert->user->cookies,
        'employeeReference' => $employeeReference,
        'max' => 5, // Limite à 5 check-ins
        'offset' => $offset,
        'order' => 'desc',
        'startDate' => $startDateFilter,
        'endDate' => $endDateFilter
      ];

      Log::info("Appel à ListCheckIns avec paramètres: " . json_encode($apiParams));
      $api_response = CheckIn::ListCheckIns($apiParams);
      Log::info("Réponse API CheckIn: " . json_encode($api_response));
      if (isset($api_response['data'])) {
        $allCheckIns = $api_response['data'];
      } elseif (isset($api_response)) {
        $allCheckIns = $api_response;
      } else {
        $allCheckIns = [];
        Log::warning("Aucune donnée retournée par l'API CheckIn.");
      }

      Log::info("Nombre total de check-ins récupérés: " . count($allCheckIns));

      if (empty($allCheckIns)) {
        Log::info("ℹ️ Aucun check-in trouvé pour l'employé ref '{$employeeReference}' dans la période spécifiée.");
        return;
      }

      // Take the last 5 check-ins (or all if less than 5)
      $lastCheckIns = array_slice($allCheckIns, -5);
      Log::info("Derniers check-ins préparés pour email: " . json_encode($lastCheckIns));

      // Fetch employee details
      $employeeApiParams = [
        'user_id' => $alert->user_id,
        'organisation' => $alert->user->organisation ?? 'default',
        'cookies' => $alert->user->cookies
      ];
      $employeesResponse = Employee::ListEmployees($employeeApiParams);
      Log::info("Réponse API Employees: " . json_encode($employeesResponse));
      $employee = null;
      if (isset($employeesResponse['data']) && is_array($employeesResponse['data'])) {
        $employee = collect($employeesResponse['data'])->firstWhere('reference', $employeeReference);
      }
      $employeeName = $employee ? trim(($employee['firstName'] ?? '') . ' ' . ($employee['lastName'] ?? '')) : $employeeReference;

      // Build HTML table for top 5 check-ins with employee name and check-in hours
      $checkinTable = '';
      if (!empty($lastCheckIns)) {
        $checkinTable = '<p style="margin-top: 16px; margin-bottom: 16px;">Out of Hours Check-In Detected</p>';
        $checkinTable .= '<table class="stock-table" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">';
        $checkinTable .= '<thead><tr>';
        $checkinTable .= '<th style="padding: 8px; text-align: left; font-weight: bold; border: 1px solid #d3dce0;">Nom de l\'employé</th>';
        $checkinTable .= '<th style="padding: 8px; text-align: left; font-weight: bold; border: 1px solid #d3dce0;">Heure de check-in</th>';
        $checkinTable .= '</tr></thead><tbody>';

        foreach ($lastCheckIns as $checkin) {
          $checkinTime = isset($checkin['startDate']) ? Carbon::parse($checkin['startDate'])->format('H:i') : 'N/A';

          $checkinTable .= '<tr>';
          $checkinTable .= '<td style="padding: 8px; border: 1px solid #d3dce0;">' . htmlspecialchars($employeeName) . '</td>';
          $checkinTable .= '<td style="padding: 8px; border: 1px solid #d3dce0;">' . htmlspecialchars($checkinTime) . '</td>';
          $checkinTable .= '</tr>';
        }

        $checkinTable .= '</tbody></table>';
        $checkinTable .= '<p class="timestamp">Rapport généré le : ' . now()->setTimezone('UTC')->format('d F Y, H:i A T') . '</p>';
      }

      // Prepare email
      Log::info("Préparation de l'envoi d'email avec " . count($lastCheckIns) . " derniers check-ins...");
      $content = $template->content;

      // Remove unwanted phrases more aggressively
      $content = preg_replace('/Employee\s*\[EMPLOYEE_NAME\]\s*checked in at\s*\[CHECKIN_DATETIME\]\./i', '', $content);
      $content = preg_replace('/Aucun produit avec stock bas détecté\./i', '', $content);

      // Ensure no residual unwanted text
      $content = trim($content);

      $content .= $checkinTable; // Append the check-in table to the content

      // Add empty $expired_products to bypass the default message in the template
      $data = [
        'subject' => $template->subject,
        'title' => $template->title,
        'content' => $content,
        'checkins' => $lastCheckIns,
        'expired_products' => [], // Empty array to avoid the default message
        'btn_name' => $template->btn_name ?? null,
        'btn_link' => $template->btn_link ?? null,
      ];

      Log::info("Envoi d'email avec " . count($lastCheckIns) . " check-ins");
      Mail::to('thabtiissam7@gmail.com')->send(new Email($data, 'alert'));
      Log::info("Email envoyé avec succès");

      // Update alert history
      $alertHistory = AlertHistory::where("alert_id", $alert->id)->latest()->first();
      if ($alertHistory) {
        $alertHistory->status = 1;
        $alertHistory->save();
        Log::info("Alerte {$alert->id} marquée comme traitée avec succès");
      } else {
        AlertHistory::create([
          'alert_id' => $alert->id,
          'status' => 1,
        ]);
        Log::info("Nouvelle entrée d'historique créée pour l'alerte ID {$alert->id}.");
      }

      echo "valomnia_api_response\n";
      echo json_encode($api_response) ?? "Aucune réponse";
      echo "\n";

    } catch (Exception $e) {
      Log::error("Erreur lors du traitement de l'alerte: " . $e->getMessage());
      $this->fail($e);
    }
  }
  /**
   * Handle a job failure.
   *
   * @param \Throwable $exception
   * @return void
   */
  public function failed(Throwable $exception): void
  {
    Log::error("Job AlertCheckInOutOfHours failed for alert ID {$this->alertId}: " . $exception->getMessage());
  }
}
