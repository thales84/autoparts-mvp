@extends('emails.layout')

@section('title', 'Vérifiez votre adresse e-mail')

@section('content')
<h2>Bienvenue, {{ $user->name }} !</h2>
<p>
    Votre compte a bien été créé sur <strong>{{ config('app.name') }}</strong>.
    Pour activer votre compte et commencer vos achats, veuillez confirmer votre adresse e-mail.
</p>

<p style="text-align:center">
    <a class="btn" href="{{ $url }}">Confirmer mon adresse e-mail</a>
</p>

<p style="font-size: 13px; color: #888;">
    Ce lien expire dans 60 minutes. Si vous n'avez pas créé de compte, ignorez cet e-mail.
</p>
<p>Cordialement,<br><strong>L'équipe {{ config('app.name') }}</strong></p>
@endsection
