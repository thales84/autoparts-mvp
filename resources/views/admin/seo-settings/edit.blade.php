@extends('layouts.admin')

@section('title', 'Paramètres SEO')

@section('content')

<form action="{{ route('admin.seo-settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- ══════════════════════════════════════════════
             COLONNE GAUCHE
        ══════════════════════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- ── Méta de base ── --}}
            <div class="ap-card mb-4">
                <div class="ap-card-header">
                    <i class="bi bi-search me-2"></i>Méta de base
                </div>
                <div class="ap-card-body">

                    {{-- Titre SEO --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Titre SEO
                            <span class="text-muted fw-normal" style="font-size:.8rem;">— balise &lt;title&gt;</span>
                        </label>
                        <input type="text"
                               name="seo_title"
                               class="form-control @error('seo_title') is-invalid @enderror"
                               value="{{ old('seo_title', $settings['seo_title'] ?? '') }}"
                               maxlength="70"
                               placeholder="Ex : PALERME AUTO PRO — Pièces auto d'occasion">
                        @error('seo_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Optimal : 50–60 caractères.
                            <span id="seoTitleCount" class="fw-semibold">0</span>/70
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Meta description
                        </label>
                        <textarea name="seo_description"
                                  id="seoDesc"
                                  class="form-control @error('seo_description') is-invalid @enderror"
                                  rows="3"
                                  maxlength="160"
                                  placeholder="Ex : Trouvez des pièces détachées automobiles d'occasion vérifiées et abordables…">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                        @error('seo_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Optimal : 120–160 caractères.
                            <span id="seoDescCount" class="fw-semibold">0</span>/160
                        </div>
                    </div>

                    {{-- Mots-clés --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mots-clés</label>
                        <input type="text"
                               name="seo_keywords"
                               class="form-control @error('seo_keywords') is-invalid @enderror"
                               value="{{ old('seo_keywords', $settings['seo_keywords'] ?? '') }}"
                               maxlength="255"
                               placeholder="pièces auto occasion, pièces détachées, auto d'occasion…">
                        @error('seo_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Séparés par des virgules. Impact limité sur Google, mais utile pour Bing/Yahoo.</div>
                    </div>

                    {{-- Robots --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Indexation robots</label>
                        <select name="seo_robots"
                                class="form-select @error('seo_robots') is-invalid @enderror">
                            @foreach([
                                'index,follow'   => 'index, follow — Indexer et suivre les liens (recommandé)',
                                'index,nofollow' => 'index, nofollow — Indexer mais ne pas suivre les liens',
                                'noindex,follow' => 'noindex, follow — Ne pas indexer mais suivre les liens',
                                'noindex,nofollow' => 'noindex, nofollow — Ne pas indexer (site en développement)',
                            ] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('seo_robots', $settings['seo_robots'] ?? 'index,follow') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('seo_robots')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Open Graph / Réseaux sociaux ── --}}
            <div class="ap-card mb-4">
                <div class="ap-card-header">
                    <i class="bi bi-share-fill me-2"></i>Open Graph — Partage sur les réseaux sociaux
                </div>
                <div class="ap-card-body">

                    <p class="text-muted" style="font-size:.85rem;">
                        Ces champs contrôlent l'aperçu affiché quand un lien est partagé sur Facebook, LinkedIn, WhatsApp, etc.
                        Laissez vide pour utiliser automatiquement le titre et la description SEO.
                    </p>

                    {{-- OG Title --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre Open Graph</label>
                        <input type="text"
                               name="seo_og_title"
                               class="form-control @error('seo_og_title') is-invalid @enderror"
                               value="{{ old('seo_og_title', $settings['seo_og_title'] ?? '') }}"
                               maxlength="95"
                               placeholder="Laissez vide pour utiliser le titre SEO">
                        @error('seo_og_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- OG Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description Open Graph</label>
                        <textarea name="seo_og_description"
                                  class="form-control @error('seo_og_description') is-invalid @enderror"
                                  rows="2"
                                  maxlength="200"
                                  placeholder="Laissez vide pour utiliser la meta description">{{ old('seo_og_description', $settings['seo_og_description'] ?? '') }}</textarea>
                        @error('seo_og_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- OG Image --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold">
                            Image de partage (OG Image)
                        </label>

                        @php $ogImage = $settings['seo_og_image'] ?? null; @endphp

                        @if($ogImage && file_exists(public_path('uploads/seo/' . $ogImage)))
                            <div class="mb-2 d-flex align-items-start gap-3">
                                <img src="{{ asset('uploads/seo/' . $ogImage) }}"
                                     alt="OG Image"
                                     style="max-width: 240px; max-height: 126px; object-fit: cover; border-radius: 8px; border: 1px solid var(--ap-border);">
                                <div>
                                    <div class="text-muted mb-2" style="font-size:.8rem;">Image actuelle</div>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="if(confirm('Supprimer cette image ?')) document.getElementById('deleteOgForm').submit();">
                                        <i class="bi bi-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        @endif

                        <input type="file"
                               name="seo_og_image"
                               class="form-control @error('seo_og_image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @error('seo_og_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Format recommandé : <strong>1200 × 630 px</strong>, JPG/PNG/WebP, max 2 Mo.
                            Apparaît lors du partage sur Facebook, LinkedIn, WhatsApp.
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Outils Google ── --}}
            <div class="ap-card mb-4">
                <div class="ap-card-header">
                    <i class="bi bi-google me-2"></i>Outils Google
                </div>
                <div class="ap-card-body">

                    {{-- Google Analytics --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Google Analytics 4
                            <span class="text-muted fw-normal" style="font-size:.8rem;">— ID de mesure</span>
                        </label>
                        <input type="text"
                               name="seo_google_analytics"
                               class="form-control @error('seo_google_analytics') is-invalid @enderror"
                               value="{{ old('seo_google_analytics', $settings['seo_google_analytics'] ?? '') }}"
                               placeholder="G-XXXXXXXXXX"
                               maxlength="20">
                        @error('seo_google_analytics')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Trouvez votre ID dans
                            <strong>Google Analytics → Administration → Flux de données → ID de mesure</strong>.
                            Format : <code>G-XXXXXXXXXX</code>
                        </div>
                    </div>

                    {{-- Google Search Console --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold">
                            Google Search Console
                            <span class="text-muted fw-normal" style="font-size:.8rem;">— code de vérification</span>
                        </label>
                        <input type="text"
                               name="seo_google_site_verification"
                               class="form-control @error('seo_google_site_verification') is-invalid @enderror"
                               value="{{ old('seo_google_site_verification', $settings['seo_google_site_verification'] ?? '') }}"
                               placeholder="Ex : abc123def456..."
                               maxlength="100">
                        @error('seo_google_site_verification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Dans Google Search Console → Propriété → Vérifier → Balise HTML.
                            Copiez uniquement la valeur du <code>content</code>, pas toute la balise.
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════
             COLONNE DROITE — aperçu + conseils
        ══════════════════════════════════════════════ --}}
        <div class="col-lg-4">

            {{-- Aperçu SERP --}}
            <div class="ap-card mb-4">
                <div class="ap-card-header">
                    <i class="bi bi-eye me-2"></i>Aperçu Google (SERP)
                </div>
                <div class="ap-card-body">
                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; background: #fff; font-family: Arial, sans-serif;">
                        <div style="font-size: .75rem; color: #3c4043; margin-bottom: .2rem; word-break: break-all;">
                            {{ config('app.url') }}
                        </div>
                        <div id="serpTitle"
                             style="font-size: 1.05rem; color: #1a0dab; margin-bottom: .25rem; line-height: 1.3; font-weight: 400; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $settings['seo_title'] ?? config('app.name') . ' — Pièces auto d\'occasion' }}
                        </div>
                        <div id="serpDesc"
                             style="font-size: .82rem; color: #4d5156; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $settings['seo_description'] ?? 'Aucune description configurée.' }}
                        </div>
                    </div>
                    <div class="form-text mt-2">Mis à jour en temps réel.</div>
                </div>
            </div>

            {{-- Conseils SEO --}}
            <div class="ap-card mb-4">
                <div class="ap-card-header">
                    <i class="bi bi-lightbulb-fill me-2"></i>Bonnes pratiques
                </div>
                <div class="ap-card-body" style="font-size: .83rem;">
                    <ul class="mb-0 ps-3" style="line-height: 1.9; color: var(--ap-text-muted);">
                        <li>Titre : <strong>50–60 caractères</strong> max</li>
                        <li>Description : <strong>120–160 caractères</strong> max</li>
                        <li>OG Image : <strong>1200 × 630 px</strong> recommandé</li>
                        <li>Inclure le <strong>nom de la ville ou région</strong> dans les mots-clés pour le SEO local</li>
                        <li>Ne pas répéter les mêmes mots-clés plus de 2 fois dans le titre</li>
                        <li>GA4 se charge <strong>uniquement en production</strong> (APP_ENV=production)</li>
                    </ul>
                </div>
            </div>

            {{-- Aperçu OG --}}
            <div class="ap-card">
                <div class="ap-card-header">
                    <i class="bi bi-facebook me-2"></i>Aperçu partage social
                </div>
                <div class="ap-card-body">
                    <div style="border: 1px solid #dce1e7; border-radius: 8px; overflow: hidden; background: #f0f2f5; font-family: Arial, sans-serif;">
                        @if(($settings['seo_og_image'] ?? null) && file_exists(public_path('uploads/seo/' . $settings['seo_og_image'])))
                            <img src="{{ asset('uploads/seo/' . $settings['seo_og_image']) }}"
                                 alt="OG preview"
                                 style="width: 100%; height: 140px; object-fit: cover; display: block;">
                        @else
                            <div style="width: 100%; height: 140px; background: #ccd0d5; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image" style="font-size: 2.5rem; color: #90949c;"></i>
                            </div>
                        @endif
                        <div style="padding: .6rem .75rem; border-top: 1px solid #dce1e7;">
                            <div style="font-size: .7rem; color: #90949c; text-transform: uppercase; margin-bottom: .15rem;">
                                {{ parse_url(config('app.url'), PHP_URL_HOST) }}
                            </div>
                            <div style="font-size: .9rem; font-weight: 700; color: #1d2129; line-height: 1.3; margin-bottom: .15rem;">
                                {{ ($settings['seo_og_title'] ?? '') ?: ($settings['seo_title'] ?? config('app.name')) }}
                            </div>
                            <div style="font-size: .8rem; color: #606770; line-height: 1.3;">
                                {{ \Illuminate\Support\Str::limit(($settings['seo_og_description'] ?? '') ?: ($settings['seo_description'] ?? ''), 90) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-text mt-2">Aperçu type Facebook/LinkedIn.</div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bouton save --}}
    <div class="d-flex justify-content-end mt-2 mb-4">
        <button type="submit" class="btn btn-ap-accent px-4" style="min-width: 200px;">
            <i class="bi bi-floppy-fill me-2"></i>Enregistrer les paramètres SEO
        </button>
    </div>

</form>

{{-- Form suppression image OG — hors du form principal pour éviter l'imbrication --}}
<form id="deleteOgForm"
      action="{{ route('admin.seo-settings.og-image.delete') }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
(function () {
    // Compteurs de caractères
    function counter(inputId, counterId) {
        const el  = document.querySelector('[name="' + inputId + '"]') || document.getElementById(inputId);
        const cnt = document.getElementById(counterId);
        if (!el || !cnt) return;
        function update() { cnt.textContent = el.value.length; }
        el.addEventListener('input', update);
        update();
    }
    counter('seo_title', 'seoTitleCount');
    counter('seoDesc',   'seoDescCount');

    // Aperçu SERP en temps réel
    const titleInput = document.querySelector('[name="seo_title"]');
    const descInput  = document.getElementById('seoDesc');
    const serpTitle  = document.getElementById('serpTitle');
    const serpDesc   = document.getElementById('serpDesc');

    if (titleInput && serpTitle) {
        titleInput.addEventListener('input', function () {
            serpTitle.textContent = this.value || '{{ config('app.name') }} — Pièces auto d\'occasion';
        });
    }
    if (descInput && serpDesc) {
        descInput.addEventListener('input', function () {
            serpDesc.textContent = this.value || 'Aucune description configurée.';
        });
    }
})();
</script>
@endpush
