@extends('emails.layout')

@section('title', 'Confirmation de commande ' . $order->order_number)

@section('content')
<h2>Merci pour votre commande !</h2>
<p>Bonjour <strong>{{ $order->customer_name }}</strong>,</p>
<p>
    Votre commande a bien été reçue et est en cours de traitement.
    Vous trouverez le récapitulatif ci-dessous.
</p>

<div class="info-box">
    <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
    <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
    <p><strong>Statut :</strong> {{ $order->statusLabel() }}</p>
    <p><strong>Paiement :</strong> {{ $order->paymentStatusLabel() }}</p>
</div>

<table class="items">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Qté</th>
            <th style="text-align:right">Prix unitaire</th>
            <th style="text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}<br><small style="color:#888">{{ $item->product_sku }}</small></td>
            <td>{{ $item->quantity }}</td>
            <td style="text-align:right">{{ number_format($item->unit_price, 2, ',', ' ') }} {{ $order->currency }}</td>
            <td style="text-align:right">{{ number_format($item->line_total, 2, ',', ' ') }} {{ $order->currency }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align:right">Total</td>
            <td style="text-align:right">{{ number_format($order->total, 2, ',', ' ') }} {{ $order->currency }}</td>
        </tr>
    </tbody>
</table>

@if($order->delivery_address)
<div class="info-box">
    <p><strong>Adresse de livraison :</strong></p>
    <p>{{ $order->delivery_address }}</p>
</div>
@endif

<p>
    Pour suivre votre commande ou soumettre une preuve de paiement, connectez-vous à votre espace client :
</p>
<p style="text-align:center">
    <a class="btn" href="{{ route('account.orders.show', $order) }}">Voir ma commande</a>
</p>

<p>Pour toute question, répondez à cet e-mail ou contactez-nous via le site.</p>
<p>Cordialement,<br><strong>L'équipe {{ config('app.name') }}</strong></p>
@endsection
