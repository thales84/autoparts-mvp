@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size:.88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.payment-settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Tabs nav --}}
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-identite-btn" data-bs-toggle="tab"
                    data-bs-target="#tab-identite" type="button" role="tab">
                <i class="bi bi-building me-1"></i>Identité
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-legal-btn" data-bs-toggle="tab"
                    data-bs-target="#tab-legal" type="button" role="tab">
                <i class="bi bi-file-text me-1"></i>Mentions légales
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-paiement-btn" data-bs-toggle="tab"
                    data-bs-target="#tab-paiement" type="button" role="tab">
                <i class="bi bi-bank me-1"></i>Paiement
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-documents-btn" data-bs-toggle="tab"
                    data-bs-target="#tab-documents" type="button" role="tab">
                <i class="bi bi-file-pdf me-1"></i>Documents
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ==================== ONGLET 1 : IDENTITÉ ==================== --}}
        <div class="tab-pane fade show active" id="tab-identite" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-7">

                    {{-- Logo --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-image me-2"></i>Logo
                        </div>

                        @if(!empty($settings['company_logo']) && file_exists(public_path('uploads/logo/' . $settings['company_logo'])))
                            <div class="mb-3">
                                <img src="{{ asset('uploads/logo/' . $settings['company_logo']) }}"
                                     alt="Logo actuel" style="max-height:80px;max-width:200px;border:1px solid var(--ap-border);border-radius:6px;padding:6px;">
                                <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.3rem;">Logo actuel — remplacez-le en uploadant un nouveau fichier.</div>
                            </div>
                        @endif

                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Fichier logo</label>
                            <input type="file" name="company_logo" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">JPG, PNG ou WebP · max 2 Mo · Si absent, le nom de la société s'affichera en texte sur les PDF.</div>
                        </div>
                    </div>

                    {{-- Infos principales --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-building me-2"></i>Informations société
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nom de la société</label>
                            <input type="text" name="company_name" class="form-control"
                                   value="{{ $settings['company_name'] ?? '' }}"
                                   placeholder="Ex : AUTO PIÈCES LYON SAS">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Slogan / Activité</label>
                            <input type="text" name="company_tagline" class="form-control"
                                   value="{{ $settings['company_tagline'] ?? '' }}"
                                   placeholder="Ex : Spécialiste pièces détachées automobiles d'occasion">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Adresse</label>
                            <input type="text" name="company_address" class="form-control"
                                   value="{{ $settings['company_address'] ?? '' }}"
                                   placeholder="Ex : 12 rue des Artisans, ZAC de la Vallée, 69800 Saint-Priest">
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Téléphone</label>
                                <input type="text" name="company_phone" class="form-control font-monospace"
                                       value="{{ $settings['company_phone'] ?? '' }}"
                                       placeholder="Ex : +33 4 78 XX XX XX">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Email</label>
                                <input type="email" name="contact_email" class="form-control"
                                       value="{{ $settings['contact_email'] ?? '' }}"
                                       placeholder="contact@auto-pieces-lyon.fr">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-5">

                    {{-- Contact client --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-chat me-2"></i>Contact client
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Numéro WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text" style="font-size:.85rem;">+</span>
                                <input type="text" name="contact_whatsapp" class="form-control font-monospace"
                                       value="{{ $settings['contact_whatsapp'] ?? '' }}"
                                       placeholder="33 6 XX XX XX XX">
                            </div>
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Sans espaces ni +. Ex : 33612345678</div>
                        </div>
                    </div>

                    {{-- Bouton --}}
                    <button type="submit" class="btn btn-ap-accent w-100">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>

                </div>
            </div>
        </div>

        {{-- ==================== ONGLET 2 : MENTIONS LÉGALES ==================== --}}
        <div class="tab-pane fade" id="tab-legal" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-7">

                    {{-- Identification légale France --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-card-text me-2"></i>Identification légale
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Forme juridique</label>
                                <input type="text" name="company_legal_form" class="form-control"
                                       value="{{ $settings['company_legal_form'] ?? '' }}"
                                       placeholder="Ex : SARL, SAS, EI, EURL">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Capital social</label>
                                <div class="input-group">
                                    <input type="text" name="company_capital" class="form-control"
                                           value="{{ $settings['company_capital'] ?? '' }}"
                                           placeholder="Ex : 10 000">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">
                                    SIRET
                                    <span style="font-weight:400;color:var(--ap-text-muted);">(14 chiffres)</span>
                                </label>
                                <input type="text" name="company_siret" class="form-control font-monospace"
                                       value="{{ $settings['company_siret'] ?? '' }}"
                                       placeholder="Ex : 123 456 789 00012"
                                       maxlength="20">
                                <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Obligatoire sur les documents commerciaux (art. R123-237 Code de commerce).</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">RCS</label>
                                <input type="text" name="company_rcs" class="form-control font-monospace"
                                       value="{{ $settings['company_rcs'] ?? '' }}"
                                       placeholder="Ex : RCS Lyon B 123 456 789">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">
                                    Code APE / NAF
                                </label>
                                <input type="text" name="company_ape" class="form-control font-monospace"
                                       value="{{ $settings['company_ape'] ?? '' }}"
                                       placeholder="Ex : 4531Z"
                                       maxlength="10">
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">
                                    N° TVA intracommunautaire
                                    <span style="font-weight:400;color:var(--ap-text-muted);">(si assujetti)</span>
                                </label>
                                <input type="text" name="company_vat" class="form-control font-monospace"
                                       value="{{ $settings['company_vat'] ?? '' }}"
                                       placeholder="Ex : FR 06 123456789">
                                <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Format FR + 2 chiffres/lettres + 9 chiffres SIREN. Laisser vide si régime de la franchise en base (art. 293B CGI).</div>
                            </div>
                        </div>
                    </div>

                    {{-- Régime TVA --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-percent me-2"></i>Régime TVA
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Régime applicable</label>
                            <select name="vat_regime" class="form-select" id="vatRegimeSelect">
                                @php $vatRegime = $settings['vat_regime'] ?? 'marge'; @endphp
                                <option value="marge"   {{ $vatRegime === 'marge'    ? 'selected' : '' }}>Régime de la marge — pièces d'occasion (art. 297A CGI)</option>
                                <option value="standard" {{ $vatRegime === 'standard' ? 'selected' : '' }}>TVA sur le prix total (régime normal)</option>
                                <option value="exempt"  {{ $vatRegime === 'exempt'   ? 'selected' : '' }}>Exonéré — franchise en base (art. 293B CGI)</option>
                            </select>
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">
                                Les vendeurs de pièces d'occasion appliquent généralement le <strong>régime de la marge</strong> : la TVA est calculée sur la marge bénéficiaire, pas sur le prix de vente total.
                            </div>
                        </div>
                        <div id="vatRateField" style="{{ ($vatRegime === 'exempt') ? 'display:none;' : '' }}">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Taux de TVA <span style="font-weight:400;color:var(--ap-text-muted);">(%)</span></label>
                            <div class="input-group" style="max-width:160px;">
                                <input type="number" name="vat_rate" class="form-control font-monospace"
                                       value="{{ $settings['vat_rate'] ?? '20' }}"
                                       min="0" max="30" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Taux normal : 20 %. Utilisé uniquement si régime standard.</div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-5">

                    {{-- Pied de page PDF --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-justify-left me-2"></i>Pied de page PDF
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Mentions légales</label>
                            <textarea name="company_footer_legal" class="form-control" rows="6"
                                      placeholder="Ex : Conformément à l'art. L211-4 du Code de la consommation, toute pièce vendue bénéficie d'une garantie légale de conformité de 2 ans. Vices cachés : art. 1641 Code civil. Tout litige relève des tribunaux compétents du siège social.">{{ $settings['company_footer_legal'] ?? '' }}</textarea>
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Apparaît en pied de page sur tous les documents PDF. Champ libre — mentionnez garantie légale, juridiction compétente, etc.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ap-accent w-100">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>

                </div>
            </div>
        </div>

        {{-- ==================== ONGLET 3 : PAIEMENT ==================== --}}
        <div class="tab-pane fade" id="tab-paiement" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-7">

                    {{-- Virement bancaire --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-bank me-2"></i>Virement bancaire (SEPA)
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nom de la banque</label>
                            <input type="text" name="payment_bank_name" class="form-control"
                                   value="{{ $settings['payment_bank_name'] ?? '' }}"
                                   placeholder="Ex : Crédit Agricole, BNP Paribas, Société Générale">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Titulaire du compte</label>
                            <input type="text" name="payment_bank_holder" class="form-control"
                                   value="{{ $settings['payment_bank_holder'] ?? '' }}"
                                   placeholder="Ex : AUTO PIÈCES LYON SAS">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">IBAN</label>
                            <input type="text" name="payment_bank_iban" class="form-control font-monospace"
                                   value="{{ $settings['payment_bank_iban'] ?? '' }}"
                                   placeholder="Ex : FR76 3000 4028 3700 0100 0794 462"
                                   maxlength="40">
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">34 caractères pour un IBAN français (FR + 2 chiffres clé + 23 chiffres RIB).</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">BIC / SWIFT</label>
                            <input type="text" name="payment_bank_bic" class="form-control font-monospace"
                                   value="{{ $settings['payment_bank_bic'] ?? '' }}"
                                   placeholder="Ex : BNPAFRPPXXX"
                                   maxlength="15">
                        </div>
                    </div>

                </div>
                <div class="col-lg-5">

                    {{-- Paiement en tranches --}}
                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-calendar-check me-2"></i>Paiement en tranches
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="payment_allow_installments"
                                   id="installments" value="1"
                                   {{ ($settings['payment_allow_installments'] ?? '0') === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="installments" style="font-size:.87rem;">
                                Autoriser les clients à payer en plusieurs tranches
                            </label>
                        </div>
                        <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.5rem;">
                            Si activé, le client peut verser un acompte lors de la commande et régler le solde avant livraison.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ap-accent w-100">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>

                </div>
            </div>
        </div>

        {{-- ==================== ONGLET 4 : DOCUMENTS ==================== --}}
        <div class="tab-pane fade" id="tab-documents" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-6">

                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-sliders me-2"></i>Paramètres généraux
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Devise</label>
                                <input type="text" name="doc_currency" class="form-control font-monospace"
                                       value="{{ $settings['doc_currency'] ?? '€' }}"
                                       placeholder="Ex : €, EUR">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold" style="font-size:.85rem;">Validité devis <span style="font-weight:400;color:var(--ap-text-muted);">(jours)</span></label>
                                <input type="number" name="doc_quote_validity" class="form-control"
                                       value="{{ $settings['doc_quote_validity'] ?? '30' }}"
                                       min="1" max="365">
                            </div>
                        </div>
                    </div>

                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-shield-check me-2"></i>Texte de garantie
                        </div>
                        <div class="mb-0">
                            <textarea name="doc_guarantee_text" class="form-control" rows="5"
                                      placeholder="Ex : Nos pièces d'occasion sont garanties 3 mois contre les vices cachés (art. 1641 Code civil). La garantie légale de conformité s'applique pendant 2 ans (art. L211-4 Code de la consommation). La garantie couvre la pièce uniquement, sans frais de main-d'œuvre ni de pose.">{{ $settings['doc_guarantee_text'] ?? '' }}</textarea>
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Apparaît dans le devis et le bon de commande.</div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">

                    <div style="background:#fff;border:1px solid var(--ap-border);border-radius:var(--ap-radius);padding:1.5rem;margin-bottom:1.25rem;">
                        <div class="fw-bold mb-4" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.6px;color:var(--ap-text-muted);">
                            <i class="bi bi-file-earmark-text me-2"></i>Conditions générales de vente
                        </div>
                        <div class="mb-0">
                            <textarea name="doc_terms_text" class="form-control" rows="5"
                                      placeholder="Ex : Le paiement intégral est exigé avant expédition. Toute commande validée ne peut être annulée. Les retours sont acceptés uniquement en cas de vice caché constaté sous 48h après réception. Conformément à la loi française, le client dispose d'un droit de rétractation de 14 jours (pièces non montées uniquement).">{{ $settings['doc_terms_text'] ?? '' }}</textarea>
                            <div style="font-size:.72rem;color:var(--ap-text-muted);margin-top:.25rem;">Apparaît dans la facture et le bon de commande.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ap-accent w-100">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>

                </div>
            </div>
        </div>

    </div>{{-- /tab-content --}}
</form>

<script>
document.getElementById('vatRegimeSelect').addEventListener('change', function () {
    document.getElementById('vatRateField').style.display = this.value === 'exempt' ? 'none' : '';
});
</script>

@endsection
