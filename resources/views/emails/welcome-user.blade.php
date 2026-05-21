@extends('emails.layout')

@section('title', 'Bienvenue sur ' . config('app.name'))

@section('content')
<h2>Bienvenue, {{ $user->name }} !</h2>
<p>
    Votre compte a bien été créé sur <strong>{{ config('app.name') }}</strong>.
    Vous pouvez dès maintenant parcourir notre catalogue de pièces détachées d'occasion.
</p>

<div class="info-box">
    <p><strong>Votre adresse e-mail :</strong> {{ $user->email }}</p>
    @if($user->phone)
    <p><strong>Téléphone :</strong> {{ $user->phone }}</p>
    @endif
</div>

<p style="text-align:center">
    <a class="btn" href="{{ route('home') }}">Accéder au catalogue</a>
</p>

<p>Si vous avez des questions, n'hésitez pas à nous contacter via le site.</p>
<p>Cordialement,<br><strong>L'équipe {{ config('app.name') }}</strong></p>
@endsection
