<?php

namespace App\Console\Commands;

use App\Jobs\AlertStock;
use App\Models\Alert;
use App\Models\AlertHistory;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
    public function handle(): void
    {
        // Récupère toutes les alertes actives de type 'expired-stock'
        $alerts = \App\Models\Alert::where('status', 1)
            ->whereHas('type', function($q) {
                $q->where('slug', 'expired-stock');
            })
            ->get();

        foreach ($alerts as $alert) {
            Log::info("Démarrage du job AlertStock pour l'alerte ID: " . $alert->id);
            // Exécution directe au lieu d'envoyer dans la queue
            (new \App\Jobs\AlertStock($alert->id))->handle();
            $this->info("Alerte {$alert->id} envoyée !");
        }
    }
}

