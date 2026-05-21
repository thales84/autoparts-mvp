<?php

namespace App\Mail;

use App\Models\PaymentProof;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProofStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PaymentProof $proof) {}

    public function envelope(): Envelope
    {
        $status = $this->proof->status === 'validated' ? 'validée' : 'rejetée';

        return new Envelope(
            subject: 'Votre preuve de paiement a été ' . $status . ' — ' . $this->proof->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proof-status-changed',
        );
    }
}
