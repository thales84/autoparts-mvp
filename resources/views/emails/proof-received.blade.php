@extends('emails.layout')

@section('title', '[Admin] Preuve de paiement reçue — ' . $proof->order->order_number)

@section('content')
<h2>Nouvelle preuve de paiement</h2>
<p>Un client vient de soumettre une preuve de paiement à valider.</p>

<div class="info-box">
    <p><strong>Commande :</strong> {{ $proof->order->order_number }}</p>
    <p><strong>Client :</strong> {{ $proof->order->customer_name }} ({{ $proof->order->customer_email }})</p>
    <p><strong>Montant versé :</strong> {{ number_format($proof->amount, 2, ',', ' ') }} {{ $proof->order->currency }}</p>
    <p><strong>Total commande :</strong> {{ number_format($proof->order->total, 2, ',', ' ') }} {{ $proof->order->currency }}</p>
    <p><strong>Restant dû :</strong> {{ number_format($proof->order->amountRemaining(), 2, ',', ' ') }} {{ $proof->order->currency }}</p>
    <p><strong>Soumis le :</strong> {{ $proof->created_at->format('d/m/Y à H:i') }}</p>
</div>

<p style="text-align:center">
    <a class="btn" href="{{ route('admin.payment-proofs.index') }}">Valider / Rejeter</a>
</p>
@endsection
