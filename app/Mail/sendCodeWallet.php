<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendCodeWallet extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($wallet_code)
    {
        $this->code = $wallet_code;
    }

    public function build()
    {
        return $this->markdown('emails.send-code-wallet');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Code Wallet',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
