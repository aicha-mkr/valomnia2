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
        return $this->subject('Test Email')
                    ->view('content.email.testmail'); // Assure-toi que cette vue existe
    }
}
