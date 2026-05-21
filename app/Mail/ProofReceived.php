<?php

namespace App\Mail;

use App\Models\PaymentProof;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProofReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PaymentProof $proof) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Admin] Preuve de paiement reçue — ' . $this->proof->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proof-received',
        );
    }
}
