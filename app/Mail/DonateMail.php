<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonateMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): DonateMail
    {
        return $this->from('info@zamarmusicacademy.ca','Zamar Music Academy')
            ->subject('Zamar Music Academy')
            ->view('mail.donate')
            ->with(['data' => $this->data]);
    }
}
