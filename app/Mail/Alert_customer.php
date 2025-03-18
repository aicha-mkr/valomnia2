<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Alert_customer extends Mailable
{
    use Queueable, SerializesModels;
    protected $customer_name;
    protected $hour;
    /**
     * Create a new message instance.
     */
    public function __construct($customer_name,$hour)
    {
        $this->customer_name= $customer_name;
        $this->hour = $hour;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Customer alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.alert_customer',
            with:['customer_name' =>$this->customer_name,'hour' => $this->hour]
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
