@extends('emails.layout')

@section('title', '[Admin] Nouvelle commande ' . $order->order_number)

@section('content')
<h2>Nouvelle commande reçue</h2>
<p>Une nouvelle commande vient d'être passée sur le site.</p>

<div class="info-box">
    <p><strong>Numéro :</strong> {{ $order->order_number }}</p>
    <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
    <p><strong>Client :</strong> {{ $order->customer_name }}</p>
    <p><strong>Email :</strong> {{ $order->customer_email }}</p>
    @if($order->customer_phone)
    <p><strong>Téléphone :</strong> {{ $order->customer_phone }}</p>
    @endif
    @if($order->delivery_address)
    <p><strong>Adresse :</strong> {{ $order->delivery_address }}</p>
    @endif
    <p><strong>Total :</strong> {{ number_format($order->total, 2, ',', ' ') }} {{ $order->currency }}</p>
</div>

<table class="items">
    <thead>
        <tr>
            <th>Produit</th>
            <th>SKU</th>
            <th>Qté</th>
            <th style="text-align:right">Total ligne</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->product_sku }}</td>
            <td>{{ $item->quantity }}</td>
            <td style="text-align:right">{{ number_format($item->line_total, 2, ',', ' ') }} {{ $order->currency }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align:right">Total</td>
            <td style="text-align:right">{{ number_format($order->total, 2, ',', ' ') }} {{ $order->currency }}</td>
        </tr>
    </tbody>
</table>

@if($order->notes)
<div class="info-box">
    <p><strong>Notes client :</strong></p>
    <p>{{ $order->notes }}</p>
</div>
@endif

<p style="text-align:center">
    <a class="btn" href="{{ route('admin.orders.show', $order) }}">Gérer cette commande</a>
</p>
@endsection
