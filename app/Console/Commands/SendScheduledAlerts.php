<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alert;
use App\Jobs\AlertStock;
use Carbon\Carbon;

class SendScheduledAlerts extends Command
{
    protected $signature = 'alerts:send-scheduled';
    protected $description = 'Send scheduled daily alerts';

    public function handle()
    {
        $this->info('🔍 Vérification des alertes programmées...');
        
        // Get all active daily alerts
        $dailyAlerts = Alert::where('status', true)
            ->where('every_day', true)
            ->whereNotNull('time')
            ->get();

        $this->info("📋 Trouvé {$dailyAlerts->count()} alertes quotidiennes à vérifier");

        $alertsSent = 0;
        foreach ($dailyAlerts as $alert) {
            $currentTime = Carbon::now()->format('H:i');
            $alertTime = Carbon::parse($alert->time)->format('H:i');

            if ($this->isTimeToSend($currentTime, $alertTime)) {
                // Use your existing AlertStock job
                AlertStock::dispatch($alert->id);
                
                $this->info("✅ Alerte quotidienne envoyée: {$alert->title} (ID: {$alert->id}) à {$alertTime}");
                $alertsSent++;
            } else {
                $this->line("⏰ Alerte {$alert->title} programmée pour {$alertTime}, heure actuelle: {$currentTime}");
            }
        }

        // Handle one-time alerts for today
        $oneTimeAlerts = Alert::where('status', true)
            ->where('every_day', false)
            ->whereDate('date', Carbon::today())
            ->get();

        $this->info("📅 Trouvé {$oneTimeAlerts->count()} alertes ponctuelles pour aujourd'hui");

        foreach ($oneTimeAlerts as $alert) {
            AlertStock::dispatch($alert->id);
            $this->info("✅ Alerte ponctuelle envoyée: {$alert->title} (ID: {$alert->id})");
            $alertsSent++;
        }
        
        if ($alertsSent > 0) {
            $this->info("🎉 Total d'alertes envoyées: {$alertsSent}");
        } else {
            $this->info("😴 Aucune alerte à envoyer pour le moment");
        }
    }

    private function isTimeToSend($currentTime, $alertTime)
    {
        $current = Carbon::createFromFormat('H:i', $currentTime);
        $scheduled = Carbon::createFromFormat('H:i', $alertTime);
        
        // 5-minute window for sending (±2 minutes)
        return $current->between(
            $scheduled->copy()->subMinutes(2),
            $scheduled->copy()->addMinutes(3)
        );
    }
}