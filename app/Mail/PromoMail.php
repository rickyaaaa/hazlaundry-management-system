<?php

namespace App\Mail;

use App\Models\Promo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $promo;

    public function __construct(Promo $promo)
    {
        $this->promo = $promo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->promo->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.promo',
        );
    }
}
