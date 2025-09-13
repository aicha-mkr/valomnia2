<?php

namespace App\Jobs;

use App\Mail\Email;
use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DailyAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $alert_id;

    public function __construct($alert_id)
    {
        $this->alert_id = $alert_id;
    }

    public function handle(): void
    {
        Log::info("Starting DailyAlert job for alert ID: " . $this->alert_id);

        $alert = Alert::with(["user", "type"])->where("id", $this->alert_id)->first();

        if (!$alert) {
            Log::error("Alert not found: " . $this->alert_id);
            return;
        }

        // Find email template
        $template = null;
        if (!empty($alert->template_id)) {
            $template = EmailTemplate::find($alert->template_id);
        }
        if (!$template && isset($alert->type_id)) {
            $template = EmailTemplate::where('type', 'Daily')
                ->where('alert_id', $alert->type_id)
                ->first();
        }
        if (!$template) {
            $template = EmailTemplate::where('type', 'Daily')->first();
        }

        if (!$template) {
            Log::error("No email template found for alert: " . $alert->id);
            return;
        }

        try {
            // Prepare email data
            $data = [
                'subject' => $template->subject,
                'title' => $template->title,
                'content' => $template->content,
                'date' => now()->format('Y-m-d'),
                'btn_name' => $template->btn_name ?? null,
                'btn_link' => $template->btn_link ?? null,
            ];

            // Get recipient emails
            $emails = [];
            if (!empty($alert->users_email)) {
                $emailsArray = json_decode($alert->users_email, true);
                if (is_array($emailsArray)) {
                    foreach ($emailsArray as $entry) {
                        if (isset($entry['value'])) {
                            $emails[] = trim($entry['value']);
                        }
                    }
                }
            }

            // Send email if we have recipients
            if (!empty($emails)) {
                Mail::to($emails)->send(new Email($data, 'daily'));
                Log::info("Daily alert email sent to " . count($emails) . " recipients");
            }

            // Update alert history
            $this->updateAlertHistory($alert->id, true);

        } catch (\Exception $e) {
            Log::error("Error processing daily alert: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            
            $this->updateAlertHistory($alert->id, false);
        }
    }

    private function updateAlertHistory($alert_id, $success)
    {
        $alertHistory = AlertHistory::where("alert_id", $alert_id)->latest()->first();
        $status = $success ? 1 : 2;

        if (!$alertHistory) {
            $alertHistory = new AlertHistory();
            $alertHistory->alert_id = $alert_id;
            $alertHistory->status = $status;
            $alertHistory->attempts = 1;
        } else {
            $alertHistory->status = $status;
            $alertHistory->attempts = ($alertHistory->attempts ?? 0) + 1;
        }
        
        $alertHistory->save();
    }
}