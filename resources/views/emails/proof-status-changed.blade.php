@extends('emails.layout')

@section('title', 'Votre preuve de paiement — ' . $proof->order->order_number)

@section('content')
<h2>Mise à jour de votre preuve de paiement</h2>
<p>Bonjour <strong>{{ $proof->order->customer_name }}</strong>,</p>

@if($proof->status === 'validated')
    <p>
        Votre preuve de paiement de
        <strong>{{ number_format($proof->amount, 2, ',', ' ') }} {{ $proof->order->currency }}</strong>
        pour la commande <strong>{{ $proof->order->order_number }}</strong> a été
        <span class="badge-success">validée</span>.
    </p>

    @if($proof->order->isFullyPaid())
        <p>
            Votre commande est désormais <strong>entièrement payée</strong>.
            Nous allons la traiter dans les meilleurs délais.
        </p>
    @else
        <p>
            Votre versement partiel est bien enregistré.
            Restant dû : <strong>{{ number_format($proof->order->amountRemaining(), 2, ',', ' ') }} {{ $proof->order->currency }}</strong>.
        </p>
    @endif
@else
    <p>
        Votre preuve de paiement pour la commande <strong>{{ $proof->order->order_number }}</strong>
        a été <span class="badge-danger">rejetée</span>.
    </p>

    @if($proof->admin_notes)
    <div class="info-box">
        <p><strong>Motif :</strong></p>
        <p>{{ $proof->admin_notes }}</p>
    </div>
    @endif

    <p>
        Veuillez soumettre une nouvelle preuve de paiement depuis votre espace client.
    </p>
@endif

<div class="info-box">
    <p><strong>Commande :</strong> {{ $proof->order->order_number }}</p>
    <p><strong>Montant versé :</strong> {{ number_format($proof->amount, 2, ',', ' ') }} {{ $proof->order->currency }}</p>
    <p><strong>Statut commande :</strong> {{ $proof->order->statusLabel() }}</p>
    <p><strong>Statut paiement :</strong> {{ $proof->order->paymentStatusLabel() }}</p>
</div>

<p style="text-align:center">
    <a class="btn" href="{{ route('account.orders.show', $proof->order) }}">Voir ma commande</a>
</p>

<p>Pour toute question, contactez-nous via le site.</p>
<p>Cordialement,<br><strong>L'équipe {{ config('app.name') }}</strong></p>
@endsection
