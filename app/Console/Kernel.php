<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */


  public function setCommands(array $commands): Kernel
  {
    $this->commands = $commands;
    return $this;
  }

  protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:trigger-alerts')->everyFiveMinutes();
        $schedule->command('alerts:check-in')->everyFiveMinutes();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
