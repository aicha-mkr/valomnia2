<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Mail\ReportMail;
use Illuminate\Support\Facades\Http;

class SendReportEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $report;
    protected $recipientEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(Report $report, string $recipientEmail)
    {
        Log::info('SendReportEmail job constructed', [
            'report_id' => $report->id,
            'recipient' => $recipientEmail
        ]);
        
        $this->report = $report;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SendReportEmail job started', [
            'report_id' => $this->report->id,
            'recipient' => $this->recipientEmail,
            'attempt' => $this->attempts()
        ]);

        try {
            // 1. Fetch data from the API
            $url = env('URL_API', 'https://agro.valomnia.com/api/v2.1/report');
            $response = Http::get($url, [
                'user_id' => $this->report->user_id,
                'startDate' => $this->report->startDate,
                'endDate' => $this->report->endDate,
            ]);
            if ($response->ok()) {
                $data = $response->json();
                $apiReport = $data['data'][0] ?? null;
                if ($apiReport) {
                    foreach ($this->report->getFillable() as $field) {
                        if (isset($apiReport[$field])) {
                            $this->report->$field = $apiReport[$field];
                        }
                    }
                }
            } else {
                Log::error('API report fetch failed', ['body' => $response->body()]);
            }

            // 2. Create and send the email
            $mail = new ReportMail($this->report);
            Mail::to($this->recipientEmail)->send($mail);

            // 3. Record success in history
            $this->recordHistory('sent', 1);

            Log::info('Report email job completed successfully', [
                'report_id' => $this->report->id,
                'recipient' => $this->recipientEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Exception occurred in SendReportEmail job', [
                'report_id' => $this->report->id,
                'recipient' => $this->recipientEmail,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'attempt' => $this->attempts()
            ]);
            $this->recordHistory('failed', $this->attempts());
            throw $e;
        }
    }

    /**
     * Enregistrer l'historique d'envoi
     */
    private function recordHistory(string $status, int $attempts): void
    {
        Log::info('Creating ReportHistory record', [
            'report_id' => $this->report->id,
            'user_id' => $this->report->user_id,
            'status' => $status,
            'attempts' => $attempts
        ]);
        
        try {
            $history = ReportHistory::create([
                'report_id' => $this->report->id,
                'user_id' => $this->report->user_id,
                'status' => $status,
                'attempts' => $attempts,
            ]);
            
            Log::info('ReportHistory record created successfully', [
                'history_id' => $history->id,
                'report_id' => $this->report->id,
                'status' => $status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create ReportHistory record', [
                'report_id' => $this->report->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
