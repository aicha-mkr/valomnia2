<?php

namespace App\Console\Commands;

use App\Jobs\AlertCheckInOutOfHours;
use App\Models\Alert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessCheckInAlerts extends Command
{
  protected $signature = 'alerts:check-in {alert_id? : The ID of a specific alert to process} {--test : Test mode for specific alert}';
  protected $description = 'Process active check-in alerts to detect out-of-hours check-ins, optionally for a specific alert ID';

  public function handle()
  {
    Log::info('Démarrage de la commande alerts:check-in');

    $alertId = $this->argument('alert_id');
    $isTest = $this->option('test');
    $currentDateTime = Carbon::now(config('app.timezone'));

    if ($alertId) {
      // Traiter une alerte spécifique
      $alert = Alert::where('id', $alertId)
        ->where('status', 1)
        ->whereHas('type', function ($query) {
          // Filtrer par le slug correct
          $query->where('slug', 'checkin-out-of-hours');
        })
        ->first();

      if (!$alert) {
        Log::warning("Aucune alerte active trouvée pour l'ID {$alertId} ou type incorrect (checkin-out-of-hours).");
        $this->error("Aucune alerte active trouvée pour l'ID {$alertId} ou le type n'est pas 'checkin-out-of-hours'.");
        return 1; // Code d'erreur
      }

      // En mode test, ignorer les vérifications de date
      if ($isTest) {
        Log::info("Mode test activé - Exécution de l'alerte ID {$alertId} sans vérification de date");
        $shouldDispatch = true;
      } else {
      // Vérifier si l'alerte est applicable aujourd'hui (sans vérifier l'heure)
      $shouldDispatch = false;
      if ($alert->every_day) {
        // Si tous les jours, toujours applicable
        $shouldDispatch = true;
        Log::info("L'alerte ID {$alertId} est applicable car configurée pour tous les jours");
      } else {
        // Si jour spécifique, vérifier uniquement la date (pas l'heure)
        if ($currentDateTime->toDateString() == $alert->date) {
          $shouldDispatch = true;
          Log::info("L'alerte ID {$alertId} est applicable car la date actuelle ({$currentDateTime->toDateString()}) correspond à la date configurée ({$alert->date})");
        } else {
          Log::info("L'alerte ID {$alertId} n'est pas applicable car la date actuelle ({$currentDateTime->toDateString()}) ne correspond pas à la date configurée ({$alert->date})");
          }
        }
      }

      if ($shouldDispatch) {
        Log::info("Exécution du job AlertCheckInOutOfHours pour l'alerte ID: {$alert->id} avec heure limite: {$alert->time}");
        // Exécuter le job de manière synchrone
        $job = new AlertCheckInOutOfHours($alert->id);
        $job->handle();
        $this->line("Job exécuté pour l'alerte ID: {$alert->id}");
      } else {
        $this->info("L'alerte ID {$alertId} n'est pas applicable pour la date actuelle ({$currentDateTime->toDateString()}).");
      }

    } else {
      // Traiter toutes les alertes actives
      $currentDate = $currentDateTime->toDateString();

      $alerts = Alert::where('status', 1)
        ->whereHas('type', function ($query) {
          // Filtrer par le slug correct
          $query->where('slug', 'checkin-out-of-hours');
        })
        ->where(function ($query) use ($currentDate) {
          // Condition pour les alertes quotidiennes ou celles dont la date correspond à aujourd'hui
          $query->where('every_day', 1)
            ->orWhere(function ($q) use ($currentDate) {
              $q->where('every_day', 0)
                ->where('date', $currentDate);
            });
        })
        ->get();

      $count = $alerts->count();
      Log::info("Nombre d'alertes 'checkin-out-of-hours' actives et applicables trouvées: {$count}");
      $this->info("Traitement de {$count} alertes 'checkin-out-of-hours'");

      foreach ($alerts as $alert) {
        Log::info("Exécution du job AlertCheckInOutOfHours pour l'alerte ID: {$alert->id} avec heure limite: {$alert->time}");
        // Exécuter le job de manière synchrone
        $job = new AlertCheckInOutOfHours($alert->id);
        $job->handle();
        $this->line("Job exécuté pour l'alerte ID: {$alert->id}");
      }
    }

    Log::info('Fin de la commande alerts:check-in');
    $this->info('Traitement des alertes de check-in terminé');
    return 0; // Succès
  }
}
