<?php

namespace App\Console\Commands;

use App\Jobs\AlertCheckInOutOfHours;
use App\Models\Alert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessCheckInAlerts extends Command
{
  protected $signature = 'alerts:check-in';
  protected $description = 'Process all active check-in alerts to detect after-hours check-ins';

  public function handle()
  {
    Log::info('Démarrage de la commande alerts:check-in');

    $currentDateTime = Carbon::now();
    $alerts = Alert::where('status', 1)
      ->where('type_id', function ($query) {
        $query->select('id')
          ->from('type_alerts')
          ->where('slug', 'check-in-hors-heures');
      })
      ->where(function ($query) use ($currentDateTime) {
        $query->where('every_day', 1)
          ->where('time', '<=', $currentDateTime->format('H:i:s'))
          ->orWhere(function ($q) use ($currentDateTime) {
            $q->where('every_day', 0)
              ->where('date', '<=', $currentDateTime->toDateString())
              ->where('time', '<=', $currentDateTime->format('H:i:s'));
          });
      })
      ->get();

    $count = $alerts->count();
    Log::info("Nombre d'alertes de check-in actives trouvées: {$count}");
    $this->info("Traitement de {$count} alertes de check-in");

    foreach ($alerts as $alert) {
      Log::info("Dispatch du job AlertCheckInOutOfHours pour l'alerte ID: {$alert->id}");
      AlertCheckInOutOfHours::dispatch($alert->id);
      $this->line("Job dispatché pour l'alerte ID: {$alert->id}");
    }

    Log::info('Fin de la commande alerts:check-in');
    $this->info('Traitement des alertes de check-in terminé');
  }
}
