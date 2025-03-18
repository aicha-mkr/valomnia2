<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Alert_Stock200 extends Mailable
{
    use Queueable, SerializesModels;
    protected $warehouse_name;
    protected $quantity;
    /**
     * Create a new message instance.
     */
    public function __construct($warehouse_name,$quantity)
    {
        $this->warehouse_name = $warehouse_name;
        $this->quantity = $quantity;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A stock Overtaking',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.Alert_Stock200',
            with:['warehouse_name' =>$this->warehouse_name
                ,'quantity' => $this->quantity],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
