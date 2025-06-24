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
            Log::info('Creating ReportMail instance', [
                'report_id' => $this->report->id
            ]);
            
            // Créer l'instance du mail
            $mail = new ReportMail($this->report);
            
            Log::info('ReportMail instance created successfully', [
                'report_id' => $this->report->id
            ]);
            
            Log::info('Sending email to recipient', [
                'report_id' => $this->report->id,
                'recipient' => $this->recipientEmail
            ]);
            
            // Envoyer l'email
            Mail::to($this->recipientEmail)->send($mail);
            
            Log::info('Email sent successfully', [
                'report_id' => $this->report->id,
                'recipient' => $this->recipientEmail
            ]);
            
            Log::info('Recording success in report history', [
                'report_id' => $this->report->id
            ]);
            
            // Enregistrer le succès dans l'historique
            $this->recordHistory('sent', 1);
            
            Log::info('Success recorded in report history', [
                'report_id' => $this->report->id,
                'status' => 'sent'
            ]);
            
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
            
            Log::info('Recording failure in report history', [
                'report_id' => $this->report->id,
                'attempt' => $this->attempts()
            ]);
            
            // Enregistrer l'échec dans l'historique
            $this->recordHistory('failed', $this->attempts());
            
            Log::info('Failure recorded in report history', [
                'report_id' => $this->report->id,
                'status' => 'failed'
            ]);
            
            throw $e; // Relancer l'exception pour que le job soit marqué comme échoué
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
