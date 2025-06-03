<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\CheckIn;
use App\Models\EmailTemplate;
use App\Models\AlertHistory;
use App\Mail\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Exception;

class AlertCheckInOutOfHours implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $alertId;

  public function __construct($alertId)
  {
    $this->alertId = $alertId;
    Log::info("Initialisation du job AlertCheckInOutOfHours avec alert ID: {$alertId}");
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
    Log::info("Heure limite configurée: {$alert->time}");

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
      }
    }

    if (empty($employeeReference) && $alert->employee) {
      $employeeReference = $alert->employee->reference ?? null;
      Log::info("✅ Référence employé extraite de la relation employee: {$employeeReference}");
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
        'max' => 5,
        'offset' => $offset,
        'sort' => 'startDate',
        'order' => 'desc',
        'startDate_gte' => $startDateFilter,
        'startDate_lte' => $endDateFilter
      ];

      Log::info("Récupération des check-ins...");
      $api_response = CheckIn::ListCheckIns($apiParams);

      if (isset($api_response['data'])) {
        $allCheckIns = $api_response['data'];
        $nextPage = $api_response['paging']['next'] ?? null;

        while ($nextPage && count($processedOffsets) < $maxPages && (time() - $startTime) < $timeLimit) {
          preg_match('/offset=(\d+)/', $nextPage, $matches);
          $offset = isset($matches[1]) ? (int)$matches[1] : 0;

          if (in_array($offset, $processedOffsets)) {
            Log::warning("Offset déjà traité ($offset), arrêt de la pagination");
            break;
          }
          $processedOffsets[] = $offset;

          Log::info("Récupération de la page suivante avec offset: $offset");
          $apiParams['offset'] = $offset;
          $nextPageData = CheckIn::ListCheckIns($apiParams);

          if (isset($nextPageData['data']) && !empty($nextPageData['data'])) {
            $allCheckIns = array_merge($allCheckIns, $nextPageData['data']);
            $nextPage = $nextPageData['paging']['next'] ?? null;
          } else {
            Log::info("Aucune donnée supplémentaire trouvée, fin de la pagination");
            $nextPage = null;
          }
        }

        if (count($processedOffsets) >= $maxPages) {
          Log::warning("Limite de pages atteinte ($maxPages), arrêt de la pagination");
        }
        if ((time() - $startTime) >= $timeLimit) {
          Log::warning("Limite de temps atteinte ($timeLimit secondes), arrêt de la pagination");
        }
      }

      Log::info("Nombre total de check-ins récupérés: " . count($allCheckIns));

      if (empty($allCheckIns)) {
        Log::info("ℹ️ Aucun check-in trouvé pour l'employé ref '{$employeeReference}' dans la période spécifiée.");
        return;
      }

      // Process check-ins
      $outOfHoursCheckIns = [];
      $alertTimeLimit = Carbon::parse($alert->time, $timezone)->format('H:i:s');

      foreach ($allCheckIns as $index => $checkIn) {
        $checkInStartDateStr = $checkIn['startDate'] ?? null;
        if (!$checkInStartDateStr) {
          Log::warning("⚠️ Check-in #$index: Date de début ('startDate') manquante. Check-in ignoré.");
          continue;
        }

        $checkInDateTime = Carbon::parse($checkInStartDateStr)->setTimezone($timezone);
        $checkInTime = $checkInDateTime->format('H:i:s');

        Log::info("Check-in #$index: Date {$checkInDateTime->format('Y-m-d')}, Heure {$checkInTime}");
        Log::info("Comparaison: Heure du check-in ($checkInTime) vs Heure limite ($alertTimeLimit)");

        if ($checkInTime > $alertTimeLimit) {
          Log::notice("🚨 CHECK-IN HORS HEURES DÉTECTÉ: {$checkInTime} > {$alertTimeLimit}");
          $outOfHoursCheckIns[] = [
            'datetime' => $checkInDateTime->format('Y-m-d H:i:s T'),
            'time' => $checkInTime,
            'data' => $checkIn
          ];
        }
      }

      Log::info("Nombre de check-ins hors heures: " . count($outOfHoursCheckIns));

      if (empty($outOfHoursCheckIns)) {
        Log::info("Aucun check-in hors heures détecté.");
        return;
      }

      // Prepare email
      Log::info("Préparation de l'envoi d'email...");
      $content = $template->content;

      if (strpos($content, '[CHECKIN_DATETIME]') === false && strpos($content, '[EMPLOYEE_NAME]') === false) {
        $content .= "<p>Les check-ins suivants ont eu lieu hors des heures autorisées :</p>";
      }

      $data = [
        'subject' => $template->subject,
        'title' => $template->title,
        'content' => $content,
        'checkins' => $outOfHoursCheckIns,
        'btn_name' => $template->btn_name ?? null,
        'btn_link' => $template->btn_link ?? null,
      ];

      Log::info("Envoi d'email avec " . count($outOfHoursCheckIns) . " check-ins hors heures");
      Mail::to('mokhtaraichaa@gmail.com')->send(new Email($data, 'alert'));
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
      Log::error("Trace: " . $e->getTraceAsString());
      $this->fail($e);
    }
  }

  public function failed(Exception $exception): void
  {
    Log::error("Erreur lors du traitement de l'alerte: " . $exception->getMessage());
    Log::error("Trace: " . $exception->getTraceAsString());

    try {
      $alertHistory = AlertHistory::where("alert_id", $this->alertId)->latest()->first();
      if ($alertHistory) {
        $alertHistory->status = -1;
        $alertHistory->save();
        Log::info("Historique d'alerte mis à jour avec statut d'échec");
      } else {
        AlertHistory::create([
          'alert_id' => $this->alertId,
          'status' => -1,
        ]);
        Log::info("Nouvelle entrée d'historique créée avec statut d'échec");
      }
    } catch (Exception $e) {
      Log::error("❌ Erreur lors de la mise à jour de l'historique en cas d'échec du job pour l'alerte ID {$this->alertId}: " . $e->getMessage());
    }
  }
}
?>
