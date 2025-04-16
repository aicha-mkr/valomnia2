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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AlertCheckInOutOfHours implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $alert_id;

  public function __construct($alert_id)
  {
    $this->alert_id = $alert_id;
  }

  public function handle(): void
  {
    Log::info("Starting AlertCheckInOutOfHours job for alert ID: " . $this->alert_id);

    $alert = Alert::with(["user", "type"])->where("id", $this->alert_id)->first();
    if (!$alert || $alert->type->slug !== 'check-in-hors-heures') {
      Log::error("Alert not found or invalid type: " . $this->alert_id);
      return;
    }

    $template = $this->getEmailTemplate($alert);
    if (!$template) {
      Log::error("No email template found for alert: " . $alert->id);
      $this->updateAlertHistory($alert, 2);
      return;
    }

    $startTime = Carbon::createFromFormat('H:i', $alert->start_hour ?? '08:00');
    $endTime = Carbon::createFromFormat('H:i', $alert->end_hour ?? '22:00');
    Log::info("Checking check-ins outside hours: {$startTime->toTimeString()} to {$endTime->toTimeString()}");

    $checkIns = $this->fetchRecentCheckIns($alert->user_id);
    if (empty($checkIns)) {
      Log::info("No check-ins found for user ID: " . $alert->user_id);
      $this->updateAlertHistory($alert, 0);
      return;
    }

    $emailsSent = false;
    foreach ($checkIns as $checkIn) {
      $checkInTime = Carbon::parse($checkIn['startDate']);
      $checkInOnlyTime = Carbon::createFromFormat('H:i', $checkInTime->format('H:i'));

      $isOutsideHours = $startTime->gt($endTime) // Overnight shift
        ? ($checkInOnlyTime->gte($endTime) && $checkInOnlyTime->lte($startTime))
        : ($checkInOnlyTime->lt($startTime) || $checkInOnlyTime->gt($endTime));

      if (!$isOutsideHours) {
        Log::info("Check-in at {$checkInTime->toDateTimeString()} within hours.");
        continue;
      }

      Log::info("Out-of-hours check-in detected at {$checkInTime->toDateTimeString()}");

      $data = [
        'subject' => $template->subject ?? 'Out-of-Hours Check-In Alert',
        'title' => $template->title ?? 'Check-In Notification',
        'content' => str_replace(
          ['[CHECKIN_TIME]', '[START_HOUR]', '[END_HOUR]'],
          [$checkInTime->toDateTimeString(), $startTime->toTimeString(), $endTime->toTimeString()],
          $template->content
        ),
        'btn_name' => $template->btn_name,
        'btn_link' => $template->btn_link,
      ];

      try {
        Mail::to($alert->user->email ?? 'mokhtaraichaa@gmail.com')->send(new Email($data, 'alert'));
        Log::info("Email sent for check-in at {$checkInTime->toDateTimeString()}");
        $emailsSent = true;
      } catch (\Exception $e) {
        Log::error("Email failed: " . $e->getMessage());
      }
    }

    $this->updateAlertHistory($alert, $emailsSent ? 1 : 0);
  }

  private function fetchRecentCheckIns($userId)
  {
    $baseUrl = env('API_BASE_URL', 'https://api.valomnia.com'); // Replace with your API URL
    $version = env('API_VERSION', 'v1'); // Replace with your API version
    $apiKey = env('API_KEY'); // Ensure this is set in .env

    Log::info("Fetching check-ins for user ID: {$userId}");
    Log::info("API URL: {$baseUrl}/api/{$version}/check-ins");
    Log::info("API Key: " . ($apiKey ? 'Set' : 'Not set'));

    $startDateGte = Carbon::now()->subDay()->toIso8601String();

    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . $apiKey,
      'Accept' => 'application/json',
    ])->get("{$baseUrl}/api/{$version}/check-ins", [
      'startDate_gte' => $startDateGte,
      'max' => 50,
      'sort' => 'startDate',
      'order' => 'desc',
      'user_id' => $userId, // Add if your API supports filtering by user_id
    ]);

    if ($response->failed()) {
      Log::error("Failed to fetch check-ins: " . $response->status() . " - " . $response->body());
      return [];
    }

    $data = $response->json();
    Log::info("Check-in API response: " . json_encode($data));
    return $data['data'] ?? [];
  }

  private function getEmailTemplate($alert)
  {
    if (!empty($alert->template_id)) {
      return EmailTemplate::find($alert->template_id);
    }
    if (isset($alert->type_id)) {
      return EmailTemplate::where('type', 'Alert')
        ->where('alert_id', $alert->type_id)
        ->first();
    }
    return EmailTemplate::where('type', 'Alert')->first();
  }

  private function updateAlertHistory($alert, $status)
  {
    $alertHistory = AlertHistory::where("alert_id", $alert->id)->latest()->first();
    if ($alertHistory) {
      $alertHistory->status = $status;
      $alertHistory->increment('attempts');
      $alertHistory->save();
    } else {
      AlertHistory::create([
        'alert_id' => $alert->id,
        'iduser' => $alert->user_id,
        'attempts' => 1,
        'status' => $status,
      ]);
    }
    Log::info("Alert history updated for ID {$alert->id} with status: $status");
  }
}
