<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $templateType; // Type de template

    /**
     * Create a new message instance.
     *
     * @param array $data The data to populate the email
     * @param string $templateType The type of email template
     */
    public function __construct(array $data, string $templateType)
    {
        $this->data = $data;
        $this->templateType = $templateType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    \Log::info('Building email with template type: ' . $this->templateType);

    // Convertir en minuscule pour éviter les problèmes de casse
    $view = match (strtolower($this->templateType)) {
        'alert' => 'emails.alert',
        'rapport' => 'emails.rapport',
        default => throw new \Exception("Unhandled template type: {$this->templateType}"),
    };

    return $this->subject($this->data['subject'])
                ->view($view)
                ->with($this->data);
}


}