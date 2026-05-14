@extends('layouts.public')

@section('title', 'Accueil — Pièces auto d\'occasion')

@section('content')
<div class="py-5 bg-dark text-white">
    <div class="container text-center">
        <h1 class="display-5 fw-bold"><i class="bi bi-gear-fill me-2"></i>AutoParts</h1>
        <p class="lead mb-4">Pièces détachées automobiles d'occasion — fiables et abordables</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg">
                <i class="bi bi-search me-1"></i>Parcourir le catalogue
            </a>
            <a href="{{ route('part-requests.create') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-question-circle me-1"></i>Je cherche une pièce
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="bi bi-box-seam display-4 text-warning mb-3 d-block"></i>
                    <h5>Catalogue complet</h5>
                    <p class="text-muted">Moteur, freinage, suspension, carrosserie — recherchez par marque, modèle ou référence.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-sm">Voir le catalogue</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="bi bi-shield-check display-4 text-success mb-3 d-block"></i>
                    <h5>Pièces vérifiées</h5>
                    <p class="text-muted">Chaque pièce est inspectée et classée selon son état : bon état, correct, reconditionné.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="bi bi-chat-dots display-4 text-primary mb-3 d-block"></i>
                    <h5>Pièce introuvable ?</h5>
                    <p class="text-muted">Soumettez une demande et nous rechercherons la pièce dont vous avez besoin.</p>
                    <a href="{{ route('part-requests.create') }}" class="btn btn-outline-primary btn-sm">Faire une demande</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
