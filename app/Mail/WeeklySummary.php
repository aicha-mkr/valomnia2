<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklySummary extends Mailable
{
    use Queueable, SerializesModels;

    public $recapData;
    public $recipientName;

    public function __construct($recapData, $recipientName)
    {
        $this->recapData = $recapData;
        $this->recipientName = $recipientName;
    }
    
    public function build()
    {
        return $this->view('content.email.send_summary')
                    ->with([
                        'recapData' => $this->recapData,
                        'recipientName' => $this->recipientName,
                    ]);
    }
}