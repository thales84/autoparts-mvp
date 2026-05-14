# PHASE-07.md — Paiement en ligne via SDK PHP

## 1. Contexte obligatoire

Projet : MVP e-commerce de vente de pièces détachées automobiles d’occasion.

Contraintes non négociables :

- Hébergement mutualisé Hostinger.
- PHP 8.3.
- MySQL.
- Composer disponible.
- SSH/Git.
- Pas de root.
- Pas de Node.js en production.
- Pas de React/Vue/Vite obligatoire.
- Laravel 13 + Blade + Bootstrap 5 CDN.
- Authentification par sessions Laravel.
- Paiement via SDK PHP ou API REST côté serveur.

Avant de commencer cette phase, lire impérativement `CLAUDE.md`.

---

## 2. Objectif de la phase

Intégrer un paiement redirigé côté serveur avec SDK PHP, sans Node.js, en gardant une architecture remplaçable entre PayPal et Stripe.

---

## 3. Checklist des tâches

- [ ] Créer `config/payments.php`.
- [ ] Créer `PaymentGatewayInterface`.
- [ ] Créer `PaymentResult`.
- [ ] Créer `PaypalPaymentGateway` avec PayPal Server SDK PHP.
- [ ] Créer `StripePaymentGateway` comme alternative si les clés Stripe sont configurées.
- [ ] Créer un service resolver selon `PAYMENT_PROVIDER`.
- [ ] Adapter `CheckoutController` pour créer la session/commande paiement.
- [ ] Créer routes succès et annulation.
- [ ] À succès, vérifier/capturer le paiement côté serveur.
- [ ] Mettre à jour `orders.payment_status` et `orders.status`.
- [ ] Créer ou mettre à jour `payments`.
- [ ] Décrémenter le stock après paiement confirmé.
- [ ] Gérer les erreurs de paiement proprement.
- [ ] Documenter les variables `.env`.

---

## 4. Fichiers à créer ou modifier

- `config/payments.php`
- `app/Services/Payments/PaymentGatewayInterface.php`
- `app/Services/Payments/PaymentResult.php`
- `app/Services/Payments/PaypalPaymentGateway.php`
- `app/Services/Payments/StripePaymentGateway.php`
- `app/Services/Payments/PaymentGatewayResolver.php`
- `app/Http/Controllers/Public/CheckoutController.php`
- `routes/web.php`
- `resources/views/public/checkout/success.blade.php`
- `resources/views/public/checkout/cancel.blade.php`
- `.env.example`
- `composer.json`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-07.md puis on commence. Exécute uniquement la phase 07 : paiement en ligne via SDK PHP, priorité PayPal Server SDK, Stripe optionnel, sans Node.js. Crée une abstraction PaymentGatewayInterface, routes succès/annulation, mise à jour commande/paiement et décrémentation stock après paiement confirmé. Avant de coder, précise quel provider est actif et les variables `.env` nécessaires.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Installer prioritairement :
  - `composer require "paypal/paypal-server-sdk:^2.2"`
- Stripe optionnel :
  - `composer require stripe/stripe-php`
- Ne jamais stocker les données carte bancaire.
- Le paiement doit être redirigé vers le prestataire.
- Ne jamais marquer une commande payée sans vérification/capture côté serveur.
- Prévoir `sandbox` par défaut.
- Pour le Cameroun ou toute autre juridiction, vérifier l’éligibilité réelle du compte marchand avant production.


---

## 7. Tests manuels attendus

- Configurer PayPal sandbox.
- Créer une commande depuis le panier.
- Être redirigé vers PayPal sandbox.
- Annuler le paiement : vérifier page cancel et statut non payé.
- Réussir le paiement : vérifier page success.
- Vérifier `orders.payment_status = paid`.
- Vérifier `orders.status = confirmed`.
- Vérifier ligne `payments`.
- Vérifier que le stock baisse seulement après paiement confirmé.
- Tester un retour succès invalide : ne pas marquer payé.
