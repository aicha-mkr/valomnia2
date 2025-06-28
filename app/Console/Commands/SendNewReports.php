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
    protected $signature = 'send:new-reports {report_id?}';

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
        
        $reportId = $this->argument('report_id');
        if ($reportId) {
            $report = Report::with('user')->find($reportId);
            if (!$report) {
                $this->error("No report found with ID: {$reportId}");
                return;
            }
            $reportsToSend = collect([$report]);
        } else {
            $reportsToSend = Report::with('user')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();
            if ($reportsToSend->isEmpty()) {
                $this->info('No new reports found in the last 24 hours.');
                return;
            }
        }

        foreach ($reportsToSend as $report) {
            // Récupérer les emails depuis la colonne users_email
            $emails = [];
            if ($report->users_email) {
                // Si users_email est un JSON (ex: '[{"value":"a@b.com"},{"value":"c@d.com"}]')
                $decoded = json_decode($report->users_email, true);
                if (is_array($decoded)) {
                    // Format JSON
                    foreach ($decoded as $entry) {
                        if (isset($entry['value'])) {
                            $emails[] = $entry['value'];
                        }
                    }
                } else {
                    // Sinon, on suppose que c'est une liste séparée par des virgules
                    $emails = array_map('trim', explode(',', $report->users_email));
                }
            } else if ($report->user && $report->user->email) {
                $emails = [$report->user->email];
            }

            foreach ($emails as $emailToSend) {
                if (filter_var($emailToSend, FILTER_VALIDATE_EMAIL)) {
                    SendReportEmail::dispatch($report, $emailToSend);
                    $this->info("Dispatched email for report ID: {$report->id} to {$emailToSend}");
                } else {
                    $this->warn("Adresse email invalide: {$emailToSend} pour le report ID: {$report->id}");
                }
            }
        }

        $this->info('Finished sending new reports.');
    }
}
