# CLAUDE.md — Conventions obligatoires du projet AutoParts MVP

## 1. Rôle de l’agent

Tu es un développeur full-stack senior Laravel/PHP.  
Tu travailles sur un MVP e-commerce de vente de pièces détachées automobiles d’occasion.

Tu dois respecter strictement :

- PHP 8.3 ;
- Laravel 13 ;
- MySQL ;
- hébergement mutualisé Hostinger ;
- SSH restreint ;
- Composer disponible ;
- **pas de Node.js en production** ;
- pas de React/Vue/Vite obligatoire ;
- frontend Blade + Bootstrap 5 CDN ;
- paiement via SDK PHP ou API REST côté serveur.

---

## 2. Règle absolue liée à l’hébergement

Le projet doit pouvoir tourner sur un hébergement mutualisé Hostinger.

Tu ne dois pas introduire :

- Docker ;
- Redis obligatoire ;
- Node.js obligatoire ;
- npm obligatoire ;
- queue worker obligatoire ;
- WebSocket ;
- service système ;
- commande nécessitant root/sudo ;
- dépendance qui exige un accès serveur avancé.

Tout doit fonctionner avec :

```bash
php artisan ...
composer ...
mysql
git
```

---

## 3. Stack imposée

- Framework : Laravel 13.
- Langage : PHP 8.3.
- DB : MySQL.
- Templates : Blade.
- CSS : Bootstrap 5 via CDN.
- JS : vanilla JS si nécessaire.
- Auth : session Laravel.
- Paiement : `PaymentGatewayInterface` + implémentation PayPal ou Stripe.
- Admin : Blade simple.

---

## 4. Architecture de code obligatoire

### Contrôleurs publics

```txt
app/Http/Controllers/Public/
```

Exemples :

```txt
HomeController.php
ProductController.php
PartRequestController.php
CartController.php
CheckoutController.php
```

### Contrôleurs auth

```txt
app/Http/Controllers/Auth/
```

Exemples :

```txt
RegisterController.php
LoginController.php
LogoutController.php
```

### Contrôleurs admin

```txt
app/Http/Controllers/Admin/
```

Exemples :

```txt
DashboardController.php
ProductController.php
OrderController.php
PartRequestController.php
```

### Services métier

```txt
app/Services/
```

Sous-dossiers :

```txt
app/Services/Cart/
app/Services/Orders/
app/Services/Payments/
```

### Form Requests

```txt
app/Http/Requests/
```

Toute validation non triviale doit être dans une Form Request.

---

## 5. Conventions de nommage

### Classes

- Modèles : singulier, PascalCase.
  - `Product`
  - `Order`
  - `PartRequest`
- Contrôleurs : PascalCase + `Controller`.
  - `ProductController`
- Services : PascalCase + `Service`.
  - `CartService`
- Form Requests : verbe + entité + `Request`.
  - `StoreProductRequest`
  - `CheckoutRequest`

### Tables

- snake_case pluriel.
  - `products`
  - `orders`
  - `order_items`
  - `part_requests`
  - `product_images`
  - `product_compatibilities`

### Routes nommées

Utiliser des noms explicites :

```php
products.index
products.show
cart.index
cart.add
cart.update
checkout.show
checkout.store
checkout.success
checkout.cancel

admin.dashboard
admin.products.index
admin.products.create
admin.products.store
admin.products.edit
admin.products.update
admin.products.destroy
admin.orders.index
admin.orders.show
admin.part-requests.index
admin.part-requests.show
```

---

## 6. Règles Blade

Utiliser des layouts :

```txt
resources/views/layouts/public.blade.php
resources/views/layouts/admin.blade.php
```

Chaque page publique doit étendre :

```blade
@extends('layouts.public')
```

Chaque page admin doit étendre :

```blade
@extends('layouts.admin')
```

Bootstrap 5 doit être chargé via CDN dans les layouts.  
Ne pas ajouter Vite, npm, Tailwind build ou bundler.

---

## 7. Règles d’authentification

Créer une authentification simple avec Laravel session guard.

Obligatoire :

- validation email/mot de passe ;
- `Hash::make()` pour inscription ;
- `Auth::attempt()` pour connexion ;
- `request()->session()->regenerate()` après connexion ;
- `Auth::logout()` + invalidation session à la déconnexion ;
- protection CSRF sur les formulaires ;
- middleware `auth` pour les routes protégées ;
- middleware `admin` pour `/admin`.

Ne pas installer Breeze, Jetstream ou Fortify pour ce MVP, car ces stacks peuvent introduire des dépendances frontend inutiles.

---

## 8. Règles panier

Pour le MVP :

- panier stocké en session ;
- pas de table `carts` obligatoire ;
- stocker en session uniquement :
  - product_id ;
  - quantity.
- recalculer les prix depuis la base au moment d’afficher le panier et au moment de créer la commande ;
- ne jamais faire confiance au prix stocké côté navigateur ou session.

---

## 9. Règles commandes

Au checkout :

- l’utilisateur doit être connecté ;
- vérifier que le panier n’est pas vide ;
- vérifier le stock ;
- créer une commande avec statut :
  - `status = pending`
  - `payment_status = unpaid`
- créer les `order_items` avec snapshot :
  - nom produit ;
  - sku ;
  - prix ;
  - quantité ;
  - total ligne.
- décrémenter le stock uniquement après paiement confirmé.

---

## 10. Règles paiement

Créer l’interface :

```php
App\Services\Payments\PaymentGatewayInterface
```

Méthodes minimales :

```php
public function createCheckout(Order $order): PaymentResult;
public function handleSuccess(array $payload): PaymentResult;
public function handleCancel(array $payload): PaymentResult;
```

Créer :

```txt
PaymentResult.php
PaypalPaymentGateway.php
StripePaymentGateway.php
```

Le contrôleur ne doit pas contenir toute la logique API PayPal/Stripe.  
Le contrôleur appelle le service.

Les clés API doivent être uniquement dans `.env`.

Ne jamais hardcoder :

- client ID ;
- secret ;
- webhook secret ;
- devise ;
- URL de retour.

---

## 11. Règles upload images

Stocker les images dans :

```txt
public/uploads/products/
```

Créer aussi :

```txt
public/uploads/.htaccess
```

Le fichier `.htaccess` doit bloquer l’exécution PHP dans `uploads`.

Validation obligatoire :

- fichier image ;
- extensions autorisées : jpg, jpeg, png, webp ;
- taille maximum ;
- nom généré aléatoirement ;
- jamais utiliser le nom original comme nom final.

---

## 12. Sécurité minimale obligatoire

- `APP_DEBUG=false` en production.
- `.env` jamais commité.
- Validation Form Request.
- CSRF sur tous les formulaires.
- Auth admin obligatoire.
- Pas d’upload PHP.
- Pas de mass assignment non contrôlé.
- Utiliser `$fillable` clairement dans chaque modèle.
- Utiliser transactions DB pour création de commande/paiement si nécessaire.
- Ne jamais stocker les informations de carte bancaire.
- Paiement redirigé via prestataire externe.

---

## 13. Style de développement

Avant de coder une phase :

1. Lire `CLAUDE.md`.
2. Lire le fichier `PHASE-0X.md`.
3. Résumer en 5 à 10 lignes ce qui sera fait.
4. Lister les fichiers à créer/modifier.
5. Coder uniquement la phase demandée.
6. Ne pas anticiper les phases suivantes sauf si une structure minimale est nécessaire.
7. Après modification, donner :
   - fichiers modifiés ;
   - commandes à exécuter ;
   - tests manuels à faire.

---

## 14. Interdictions

Ne pas :

- transformer le projet en SPA ;
- utiliser React/Vue/Next ;
- exiger Node.js ;
- créer une API séparée inutile ;
- utiliser Laravel Sanctum pour le frontend public ;
- complexifier avec DDD lourd ;
- ajouter des packages non nécessaires ;
- installer un admin panel lourd sans demande explicite ;
- supposer que Stripe fonctionne dans tous les pays ;
- supposer que PayPal accepte toutes les devises.

---

## 15. Définition de “terminé”

Une phase est terminée si :

- les fichiers demandés existent ;
- les routes fonctionnent ;
- les erreurs courantes sont gérées ;
- les validations sont en place ;
- les vues Blade sont propres ;
- les commandes à exécuter sont listées ;
- les tests manuels sont listés ;
- aucune dépendance interdite n’a été introduite.
