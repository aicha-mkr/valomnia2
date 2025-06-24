<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Jobs\SendReportEmail;
use Carbon\Carbon;

class SendNewReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:new-reports {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds reports created in the last 24 hours and sends them to the associated user or a specified email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Searching for new reports to send...');
        
        $recipientEmail = $this->argument('email');

        $newReports = Report::with('user')
                            ->where('created_at', '>=', Carbon::now()->subDay())
                            ->get();

        if ($newReports->isEmpty()) {
            $this->info('No new reports found in the last 24 hours.');
            return;
        }

        foreach ($newReports as $report) {
            $emailToSend = $recipientEmail ?? ($report->user ? $report->user->email : null);
            
            if ($emailToSend) {
                SendReportEmail::dispatch($report, $emailToSend);
                $this->info("Dispatched email for report ID: {$report->id} to {$emailToSend}");
            } else {
                $this->warn("Report ID: {$report->id} is missing a user or user email. Skipping.");
            }
        }

        $this->info('Finished sending new reports.');
    }
}
