<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ValomniaService;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklySummary;

class SendWeeklySummary extends Command
{
    protected $signature = 'email:send-weekly-summary {email} {name}';
    protected $description = 'Send weekly summary emails';

    protected $valomniaService;

    public function __construct(ValomniaService $valomniaService)
    {
        parent::__construct();
        $this->valomniaService = $valomniaService;
    }

    public function handle()
    {
        $recipientEmail = $this->argument('email');
        $recipientName = $this->argument('name'); // Get the recipient name

        if (empty($recipientEmail)) {
            $this->error('No recipient email provided.');
            return;
        }

        $this->info('Sending email to: ' . $recipientEmail);

        try {
            $recap = $this->valomniaService->calculateKPI();
            // Pass the recipient name to the Mailable
            Mail::to($recipientEmail)->send(new WeeklySummary($recap, $recipientName));
            $this->info('Weekly summary email sent successfully to ' . $recipientEmail);
        } catch (\Exception $e) {
            $this->error('Failed to send weekly summary email: ' . $e->getMessage());
        }
    }
}