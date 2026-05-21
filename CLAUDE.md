# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commandes

```bash
# Développement local
php artisan serve

# Migrations + seed
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Setup complet (première installation)
composer run setup

# Tests
composer run test
# ou directement :
php artisan config:clear && php artisan test
php artisan test --filter NomDuTest

# Formatage (Laravel Pint)
./vendor/bin/pint
```

## Contraintes hébergement — NON NÉGOCIABLES

Cible : **hébergement mutualisé Hostinger** (PHP 8.3, MySQL, SSH restreint, pas de root).

Interdits absolus :
- Docker, Redis obligatoire, Node.js en production, npm build, queue worker, WebSocket
- Laravel Breeze / Jetstream / Fortify / Sanctum
- SPA (React, Vue, Next.js)
- Packages nécessitant un accès serveur avancé

Tout doit fonctionner avec `php artisan`, `composer`, `mysql`, `git` uniquement.

## Architecture

### Stack
- **PHP 8.3 + Laravel 13** — backend et templates
- **MySQL** — base de données, prix stockés en `decimal(12,2)`
- **Blade + Bootstrap 5 CDN** — frontend, pas de Vite/bundler
- **Session Laravel** — authentification et panier
- **PayPal / Stripe PHP SDK** — paiements côté serveur uniquement

### Layouts
```
layouts/public.blade.php   → toutes les pages publiques
layouts/admin.blade.php    → toutes les pages admin
```

### Namespaces contrôleurs
```
App\Http\Controllers\Public\   → pages publiques
App\Http\Controllers\Auth\     → login, register, logout
App\Http\Controllers\Admin\    → back-office (middleware admin)
```

### Services métier
```
app/Services/Cart/           → CartService (session uniquement, pas de table carts)
app/Services/Orders/         → OrderService (création commande + confirmation paiement)
app/Services/Payments/       → PaymentGatewayInterface + PaypalPaymentGateway + StripePaymentGateway
```

Le contrôleur appelle toujours le service. Aucune logique PayPal/Stripe dans les contrôleurs.

### Panier (session)
- Stocke `[product_id => quantity]` en session
- Prix **toujours recalculés depuis la DB** à l'affichage et au checkout — ne jamais faire confiance à un prix en session ou en POST

### Commandes / Paiement
- À la création : `status = pending`, `payment_status = unpaid`
- `order_items` = snapshot (nom, SKU, prix, quantité au moment de la commande)
- Stock décrémenté **uniquement après** `OrderService::confirmPayment()`
- Utiliser une transaction DB pour la création commande + items

### Images produits
- Stockées dans `public/uploads/products/`
- `public/uploads/.htaccess` bloque l'exécution PHP dans ce dossier
- Nom de fichier généré aléatoirement (jamais le nom original)
- Extensions acceptées : jpg, jpeg, png, webp

## Conventions de nommage

| Élément | Format | Exemple |
|---------|--------|---------|
| Modèles | PascalCase singulier | `PartRequest`, `OrderItem` |
| Tables | snake_case pluriel | `part_requests`, `order_items` |
| Contrôleurs | PascalCase + Controller | `ProductController` |
| Services | PascalCase + Service | `CartService` |
| Form Requests | Verbe + Entité + Request | `StoreProductRequest`, `CheckoutRequest` |
| Routes | dot.notation | `admin.products.index`, `cart.add` |

## Workflow par phase

Avant de coder :
1. Lire ce fichier + le `docs/PHASE-0X.md` concerné
2. Résumer en 5–10 lignes ce qui sera fait
3. Lister les fichiers à créer/modifier
4. Coder uniquement la phase demandée (pas d'anticipation)

Après modification, fournir : fichiers modifiés, commandes à exécuter, tests manuels à faire.
