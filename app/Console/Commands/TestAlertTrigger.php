<?php

namespace App\Console\Commands;

use App\Jobs\AlertStock;
use App\Models\Alert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestAlertTrigger extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:test-alert-trigger {alert_id}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Test le déclenchement d\'une alerte spécifique';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $alertId = $this->argument('alert_id');
    $alert = Alert::find($alertId);

    if (!$alert) {
      $this->error("Alerte non trouvée avec l'ID: " . $alertId);
      return 1;
    }

    $this->info("Déclenchement de l'alerte: " . $alert->title);
    Log::info("Test manuel de l'alerte ID: " . $alertId);

    // Dispatch le job immédiatement (sans file d'attente)
    (new AlertStock($alertId))->handle();

    $this->info("Test terminé. Vérifiez les logs pour plus de détails.");

    return 0;
  }
}
