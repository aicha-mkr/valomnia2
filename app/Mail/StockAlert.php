<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($alert)
    {
        $this->alert = $alert;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Alerte de Rupture de Stock')
                    ->view('emails.stock_alert')
                    ->with(['alert' => $this->alert]);
    }
}
