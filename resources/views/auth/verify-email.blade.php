@extends('layouts.public')

@section('title', 'Vérifiez votre e-mail')

@section('content')
<div class="ap-auth-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="ap-auth-card" style="text-align: center;">

                    <a href="{{ route('home') }}" class="auth-logo mb-4 d-block">
                        <i class="bi bi-gear-fill brand-icon me-1"></i>PALERME AUTO PRO
                    </a>

                    <div style="width: 64px; height: 64px; background: #EEF2F8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.6rem; color: var(--ap-primary);">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <h4 class="fw-bold mb-2" style="font-size: 1.1rem;">Vérifiez votre e-mail</h4>
                    <p class="text-muted mb-4" style="font-size: .9rem;">
                        Un lien de confirmation a été envoyé à<br>
                        <strong>{{ auth()->user()->email }}</strong>
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success py-2 mb-3" style="font-size: .88rem;">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                        </div>
                    @endif

                    <p style="font-size: .85rem; color: var(--ap-text-muted);">
                        Vous n'avez pas reçu l'e-mail ? Vérifiez vos spams ou renvoyez un nouveau lien.
                    </p>

                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-ap-primary w-100 mb-3">
                            <i class="bi bi-send me-1"></i>Renvoyer le lien
                        </button>
                    </form>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100"
                                style="font-size: .82rem;">
                            <i class="bi bi-box-arrow-right me-1"></i>Se déconnecter
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
