<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Alert;
use App\Models\EmailTemplate;

class AlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;
    public $template;
    public $alertData;

    public function __construct(Alert $alert, EmailTemplate $template, array $alertData)
    {
        $this->alert = $alert;
        $this->template = $template;
        $this->alertData = $alertData;
    }

    public function build()
    {
        return $this->subject($this->template->subject ?: 'Alert Notification')
                    ->view('emails.alert')
                    ->with([
                        'alert' => $this->alert,
                        'template' => $this->template,
                        'alertData' => $this->alertData,
                        'replacePlaceholders' => [$this, 'replacePlaceholders']
                    ]);
    }

    public function replacePlaceholders($content, $data)
    {
        $replacements = [
            '[PRODUCT]' => $data['product_name'] ?? 'Unknown Product',
            '[QUANTITY]' => $data['quantity'] ?? '0',
            '[WAREHOUSE]' => $data['warehouse'] ?? 'Unknown Warehouse',
            '[EMPLOYEE]' => $data['employee'] ?? 'Unknown Employee',
            '[DATE]' => now()->format('Y-m-d'),
            '[TIME]' => now()->format('H:i'),
            '[ALERT_TITLE]' => $data['alert_title'] ?? 'Alert',
            '[DESCRIPTION]' => $data['alert_description'] ?? '',
        ];

        foreach ($replacements as $placeholder => $value) {
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}