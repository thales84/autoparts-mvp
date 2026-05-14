# ARCHITECTURE.md — MVP e-commerce pièces auto d’occasion

## 1. Décision technique principale

### Stack retenue

- **Framework backend : Laravel 13**
- **Langage : PHP 8.3**
- **Base de données : MySQL**
- **Frontend : Blade + Bootstrap 5 via CDN + JavaScript vanilla**
- **Authentification : sessions Laravel, cookies HTTP-only, CSRF Laravel**
- **Paiement : abstraction `PaymentGatewayInterface`, implémentation prioritaire PayPal Server SDK PHP ; Stripe PHP possible seulement si le compte marchand est réellement éligible**
- **Admin : interface Blade simple, protégée par middleware `admin`**
- **Déploiement : SSH + Git + Composer sur Hostinger mutualisé**

### Pourquoi Laravel malgré l’hébergement mutualisé ?

Laravel reste adapté au MVP parce qu’il apporte immédiatement :

- routage propre ;
- contrôleurs MVC ;
- migrations MySQL ;
- Eloquent ORM ;
- validation robuste ;
- sessions sécurisées ;
- protection CSRF ;
- hashing des mots de passe ;
- structure maintenable ;
- intégration Composer pour SDK PayPal/Stripe.

La contrainte importante : **ne pas utiliser une stack frontend qui dépend de Node.js en production**.  
Donc pas de React, pas de Vue, pas de Vite obligatoire, pas de build npm sur Hostinger.

### Pourquoi Laravel 13 ?

Laravel 13 exige PHP 8.3 minimum. L’hébergement cible annonce PHP 8.3, donc c’est compatible.  
Si Hostinger ou Composer pose problème au moment du déploiement, la solution de repli acceptable est Laravel 12, qui supporte aussi PHP 8.3.

### Ce qu’on évite volontairement

- Pas de Next.js.
- Pas de React/Vue côté production.
- Pas de Docker.
- Pas de Redis.
- Pas de queues obligatoires.
- Pas de WebSockets.
- Pas de panel admin lourd.
- Pas de stockage S3 obligatoire.
- Pas de dépendance Node.js côté serveur.

---

## 2. Organisation générale du projet

Nom recommandé du repo : `autoparts-mvp`

Structure Laravel standard, avec organisation métier claire :

```txt
autoparts-mvp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── PartRequestController.php
│   │   │   │   ├── CartController.php
│   │   │   │   └── CheckoutController.php
│   │   │   ├── Auth/
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   └── LogoutController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── ProductController.php
│   │   │       ├── OrderController.php
│   │   │       └── PartRequestController.php
│   │   ├── Middleware/
│   │   │   └── EnsureUserIsAdmin.php
│   │   └── Requests/
│   │       ├── StoreProductRequest.php
│   │       ├── UpdateProductRequest.php
│   │       ├── StorePartRequestRequest.php
│   │       ├── RegisterUserRequest.php
│   │       └── CheckoutRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── VehicleMake.php
│   │   ├── VehicleModel.php
│   │   ├── Product.php
│   │   ├── ProductImage.php
│   │   ├── ProductCompatibility.php
│   │   ├── PartRequest.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   └── Payment.php
│   └── Services/
│       ├── Cart/
│       │   └── CartService.php
│       ├── Orders/
│       │   └── OrderService.php
│       └── Payments/
│           ├── PaymentGatewayInterface.php
│           ├── PaymentResult.php
│           ├── PaypalPaymentGateway.php
│           └── StripePaymentGateway.php
├── config/
│   ├── filesystems.php
│   └── payments.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── uploads/
│       └── products/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── public.blade.php
│       │   └── admin.blade.php
│       ├── public/
│       │   ├── home.blade.php
│       │   ├── products/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── part-requests/
│       │   │   └── create.blade.php
│       │   ├── cart/
│       │   │   └── index.blade.php
│       │   └── checkout/
│       │       ├── show.blade.php
│       │       ├── success.blade.php
│       │       └── cancel.blade.php
│       ├── auth/
│       │   ├── register.blade.php
│       │   └── login.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── products/
│           ├── orders/
│           └── part-requests/
├── routes/
│   └── web.php
├── storage/
├── bootstrap/
├── vendor/
├── .env
├── composer.json
├── CLAUDE.md
└── PHASE-01.md ... PHASE-08.md
```

---

## 3. Séparation logique frontend / backend

Le projet reste monolithique Laravel, mais la séparation doit être nette.

### Frontend public

Responsable de :

- page d’accueil ;
- liste des pièces ;
- recherche ;
- fiche produit ;
- panier ;
- checkout ;
- formulaires d’inscription et de connexion ;
- formulaire “Je recherche cette pièce”.

Dossiers :

```txt
resources/views/layouts/public.blade.php
resources/views/public/
resources/views/auth/
```

### Backend applicatif

Responsable de :

- règles métier ;
- contrôleurs ;
- validation ;
- panier session ;
- commande ;
- paiement ;
- upload média ;
- administration.

Dossiers :

```txt
app/Http/Controllers/
app/Http/Requests/
app/Models/
app/Services/
```

### Interface admin

Responsable de :

- créer/modifier/supprimer des produits ;
- gérer les images ;
- consulter les commandes ;
- changer les statuts de commande ;
- consulter les demandes de pièces indisponibles ;
- changer les statuts des demandes.

Dossiers :

```txt
resources/views/layouts/admin.blade.php
resources/views/admin/
app/Http/Controllers/Admin/
```

---

## 4. Authentification

### Stratégie

Utiliser l’authentification Laravel basée sur les sessions.

- Table `users`.
- Connexion par email + mot de passe.
- Hash via `Hash::make()`.
- Session régénérée après connexion.
- CSRF actif sur tous les formulaires.
- Middleware `auth` pour panier/commande/admin selon besoin.
- Middleware `admin` pour `/admin/*`.

### Rôles

Champ `role` dans `users` :

- `customer`
- `admin`

Pour le MVP, pas besoin de table `roles`.

### Routes principales

```php
GET  /register
POST /register
GET  /login
POST /login
POST /logout
```

---

## 5. Gestion des médias

### Choix retenu

Stockage local dans :

```txt
public/uploads/products/
```

Pourquoi ce choix ?

- compatible mutualisé ;
- pas besoin de symlink si Hostinger bloque `storage:link` ;
- pas besoin de S3 ;
- simple pour un MVP.

### Sécurité minimale

Créer :

```txt
public/uploads/.htaccess
```

Contenu recommandé :

```apache
Options -Indexes

<FilesMatch "\.(php|php3|php4|php5|phtml|phar)$">
    Require all denied
</FilesMatch>
```

Règles applicatives :

- accepter uniquement `jpg`, `jpeg`, `png`, `webp` ;
- taille max : 2 à 4 Mo par image ;
- nom de fichier généré par UUID ;
- jamais utiliser le nom original comme nom de stockage ;
- stocker uniquement le chemin relatif en base ;
- image principale dans `products.main_image_path` ;
- galerie dans `product_images`.

---

## 6. Routes et `.htaccess`

### Option recommandée : projet hors `public_html`

Structure cible :

```txt
/home/uXXXX/apps/autoparts-mvp/        # projet Laravel complet
/home/uXXXX/public_html/               # uniquement point d’entrée web
```

Dans `public_html/index.php`, adapter les chemins :

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../apps/autoparts-mvp/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../apps/autoparts-mvp/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

Dans `public_html/.htaccess` :

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^uploads/(.*)$ uploads/$1 [L]

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>

Options -Indexes

<FilesMatch "^(\.env|composer\.(json|lock)|artisan|server\.php)$">
    Require all denied
</FilesMatch>
```

Puis créer un dossier :

```txt
public_html/uploads/products/
```

Et configurer Laravel pour stocker les images vers ce dossier public.

### Option de repli : projet dans `public_html`

Si Hostinger empêche de cloner hors `public_html`, utiliser :

```txt
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── .htaccess
```

Dans `public_html/.htaccess` :

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^(app|bootstrap|config|database|resources|routes|storage|vendor)(/.*)?$ - [F,L]
    RewriteRule ^(\.env|composer\.(json|lock)|artisan)$ - [F,L]

    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

Options -Indexes
```

Cette option est moins propre que l’option hors `public_html`, mais elle reste possible pour un MVP si les règles Apache sont bien testées.

---

## 7. Modèle de données MySQL

### `users`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| name | varchar(120) | Nom complet |
| email | varchar(190) unique | Identifiant |
| phone | varchar(40) nullable | Téléphone |
| password | varchar(255) | Hash |
| role | enum/customer/admin | Défaut `customer` |
| status | enum/active/blocked | Défaut `active` |
| email_verified_at | timestamp nullable | Optionnel |
| remember_token | varchar nullable | Laravel |
| created_at / updated_at | timestamps |  |

### `categories`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| name | varchar(120) | Ex : Moteur, Freinage |
| slug | varchar(160) unique | SEO |
| description | text nullable |  |
| is_active | boolean |  |

### `vehicle_makes`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| name | varchar(120) unique | Toyota, Mercedes, BMW |
| slug | varchar(160) unique |  |

### `vehicle_models`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| vehicle_make_id | FK | Marque |
| name | varchar(120) | Corolla, W204, X5 |
| slug | varchar(160) |  |

### `products`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| category_id | FK nullable | Catégorie |
| sku | varchar(80) unique | Référence interne |
| oem_reference | varchar(120) nullable | Référence constructeur |
| name | varchar(190) | Nom |
| slug | varchar(220) unique | SEO |
| description | text | Description |
| condition | enum | `used_good`, `used_fair`, `refurbished`, `for_parts` |
| price | decimal(12,2) | Prix |
| currency | char(3) | Défaut `XAF` ou devise choisie |
| stock_quantity | int unsigned | Stock |
| status | enum | `draft`, `active`, `inactive` |
| main_image_path | varchar nullable | Image principale |
| location | varchar nullable | Lieu physique |
| created_at / updated_at | timestamps |  |

### `product_images`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| product_id | FK | Produit |
| path | varchar(255) | Chemin relatif |
| alt_text | varchar nullable | SEO/accessibilité |
| sort_order | int | Ordre |
| created_at / updated_at | timestamps |  |

### `product_compatibilities`

Permet de dire qu’une pièce est compatible avec plusieurs véhicules.

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| product_id | FK | Produit |
| vehicle_make_id | FK nullable | Marque |
| vehicle_model_id | FK nullable | Modèle |
| year_from | smallint nullable | Année début |
| year_to | smallint nullable | Année fin |
| notes | varchar nullable | Ex : moteur 2.0 diesel |

### `part_requests`

Demandes pour pièces indisponibles.

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| user_id | FK nullable | Client connecté ou visiteur |
| requested_part_name | varchar(190) | Pièce recherchée |
| reference | varchar(120) nullable | Référence connue |
| vehicle_make | varchar(120) nullable | Saisie libre |
| vehicle_model | varchar(120) nullable | Saisie libre |
| vehicle_year | smallint nullable |  |
| description | text nullable | Détails |
| contact_name | varchar(120) |  |
| contact_email | varchar(190) nullable |  |
| contact_phone | varchar(40) nullable |  |
| status | enum | `new`, `in_progress`, `found`, `closed` |
| admin_notes | text nullable | Notes internes |
| created_at / updated_at | timestamps |  |

### `orders`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| order_number | varchar(40) unique | Ex : CMD-2026-000001 |
| user_id | FK nullable | Client |
| status | enum | `pending`, `confirmed`, `processing`, `shipped`, `delivered`, `cancelled` |
| payment_status | enum | `unpaid`, `pending`, `paid`, `failed`, `refunded` |
| subtotal | decimal(12,2) |  |
| delivery_fee | decimal(12,2) |  |
| tax_amount | decimal(12,2) | MVP : 0 |
| total | decimal(12,2) |  |
| currency | char(3) | Devise |
| customer_name | varchar(120) | Snapshot |
| customer_email | varchar(190) | Snapshot |
| customer_phone | varchar(40) | Snapshot |
| delivery_address | text nullable |  |
| notes | text nullable |  |
| payment_provider | varchar nullable | `paypal`, `stripe` |
| payment_reference | varchar nullable | ID externe |
| created_at / updated_at | timestamps |  |

### `order_items`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| order_id | FK | Commande |
| product_id | FK nullable | Produit |
| product_name | varchar(190) | Snapshot |
| product_sku | varchar(80) | Snapshot |
| unit_price | decimal(12,2) | Snapshot |
| quantity | int unsigned |  |
| line_total | decimal(12,2) |  |

### `payments`

| Champ | Type | Note |
|---|---|---|
| id | bigint unsigned | PK |
| order_id | FK | Commande |
| provider | varchar(40) | `paypal` ou `stripe` |
| provider_session_id | varchar nullable | Checkout/session/order ID |
| provider_payment_id | varchar nullable | Capture/charge ID |
| amount | decimal(12,2) |  |
| currency | char(3) |  |
| status | enum | `created`, `pending`, `paid`, `failed`, `cancelled`, `refunded` |
| raw_payload | json nullable | Réponse API utile pour audit |
| paid_at | timestamp nullable |  |
| created_at / updated_at | timestamps |  |

---

## 8. Relations Eloquent

```txt
User hasMany Order
User hasMany PartRequest

Category hasMany Product

VehicleMake hasMany VehicleModel
VehicleMake hasMany ProductCompatibility
VehicleModel hasMany ProductCompatibility

Product belongsTo Category
Product hasMany ProductImage
Product hasMany ProductCompatibility
Product hasMany OrderItem

Order belongsTo User
Order hasMany OrderItem
Order hasOne/many Payment

OrderItem belongsTo Order
OrderItem belongsTo Product nullable

PartRequest belongsTo User nullable
Payment belongsTo Order
```

---

## 9. Flux métier principal

### Catalogue

1. Le visiteur arrive sur `/products`.
2. Il filtre par :
   - nom ;
   - référence interne `sku` ;
   - référence constructeur ;
   - marque ;
   - modèle ;
   - catégorie.
3. Il ouvre `/products/{slug}`.
4. Si stock > 0 : bouton “Ajouter au panier”.
5. Si stock = 0 : bouton “Je recherche cette pièce”.

### Demande de pièce indisponible

1. Le visiteur clique sur “Je recherche cette pièce”.
2. Le formulaire préremplit le nom ou la référence si possible.
3. La demande est enregistrée dans `part_requests`.
4. L’admin voit la demande dans `/admin/part-requests`.

### Commande

1. Le client ajoute un ou plusieurs produits au panier.
2. Le panier est stocké en session.
3. Au checkout, le client doit être connecté.
4. Laravel crée une commande `pending/unpaid`.
5. Redirection vers PayPal ou Stripe.
6. Retour succès : capture/vérification du paiement.
7. La commande devient `confirmed/paid`.
8. Le stock est décrémenté.
9. Retour annulation : commande reste `pending/unpaid` ou passe `cancelled`.

---

## 10. Paiement

### Recommandation MVP

Priorité : **PayPal Server SDK PHP**, parce qu’il existe un SDK PHP installable via Composer et ne nécessite pas Node.js.

Commande Composer :

```bash
composer require "paypal/paypal-server-sdk:^2.2"
```

Variables `.env` :

```env
PAYMENT_PROVIDER=paypal
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_CURRENCY=USD
```

### Stripe comme option

Stripe PHP fonctionne aussi sans Node.js :

```bash
composer require stripe/stripe-php
```

Variables `.env` :

```env
PAYMENT_PROVIDER=stripe
STRIPE_SECRET_KEY=
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=usd
```

Mais il faut vérifier l’éligibilité du compte marchand selon le pays d’enregistrement de l’entreprise. Pour le Cameroun, ne pas supposer que Stripe sera disponible sans structure enregistrée dans un pays supporté.

### Devise

Pour un site au Cameroun, le catalogue peut afficher `XAF`, mais les paiements PayPal/Stripe peuvent imposer une devise différente selon le compte marchand et les moyens de paiement disponibles.

Approche MVP :

- stocker les prix en `XAF` si c’est le marché cible ;
- prévoir une config `payments.currency`;
- si le prestataire n’accepte pas `XAF`, convertir côté checkout via un taux défini manuellement dans `.env` ou en back-office ;
- afficher clairement la devise payée.

---

## 11. Instructions de déploiement Hostinger via SSH/Git

### Préparation locale

```bash
composer create-project laravel/laravel:^13.0 autoparts-mvp
cd autoparts-mvp
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Ne pas utiliser :

```bash
npm install
npm run build
```

Pour ce projet, le CSS vient de Bootstrap CDN dans les layouts Blade.

### Préparation Git

```bash
git init
git add .
git commit -m "Initial Laravel autoparts MVP"
git branch -M main
git remote add origin git@github.com:USER/autoparts-mvp.git
git push -u origin main
```

### Déploiement recommandé par SSH

```bash
ssh user@server
mkdir -p ~/apps
cd ~/apps
git clone git@github.com:USER/autoparts-mvp.git
cd autoparts-mvp
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Configurer `.env` avec les identifiants MySQL Hostinger.

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Permissions

Laravel doit pouvoir écrire dans :

```txt
storage/
bootstrap/cache/
```

Commandes possibles selon permissions Hostinger :

```bash
chmod -R 775 storage bootstrap/cache
```

Si `775` est refusé, utiliser le gestionnaire de fichiers Hostinger pour vérifier les permissions.

### Point d’entrée web

Configurer `public_html/index.php` pour pointer vers l’app Laravel dans `~/apps/autoparts-mvp`.

Voir la section `.htaccess` de ce document.

### Après chaque déploiement

```bash
cd ~/apps/autoparts-mvp
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 12. Variables `.env` minimales

```env
APP_NAME="AutoParts MVP"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://example.com

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_TIMEZONE=Africa/Douala

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

PAYMENT_PROVIDER=paypal
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_CURRENCY=USD

STRIPE_SECRET_KEY=
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=usd
```

---

## 13. Phases de développement

1. **PHASE-01** : setup Laravel + configuration Hostinger.
2. **PHASE-02** : base de données, migrations et modèles Eloquent.
3. **PHASE-03** : authentification.
4. **PHASE-04** : catalogue, fiche produit, recherche.
5. **PHASE-05** : demandes de pièces indisponibles.
6. **PHASE-06** : panier et commandes.
7. **PHASE-07** : paiement PayPal ou Stripe via SDK PHP.
8. **PHASE-08** : interface admin.

---

## 14. Critères de réussite MVP

Le MVP est validé lorsque :

- un visiteur peut consulter le catalogue ;
- la recherche fonctionne par nom, référence, marque, modèle ;
- une fiche produit affiche photo, description, état, prix, stock ;
- un produit indisponible permet de créer une demande ;
- un client peut créer un compte et se connecter ;
- un client connecté peut commander ;
- une commande est enregistrée ;
- un paiement sandbox peut être initié et confirmé ;
- l’admin peut gérer les produits ;
- l’admin peut voir les commandes ;
- l’admin peut voir les demandes de pièces.

---

## 15. Références techniques à vérifier régulièrement

- Documentation Laravel : https://laravel.com/docs
- Déploiement Laravel : https://laravel.com/docs/deployment
- Hostinger Laravel : https://www.hostinger.com/support/6152127-how-to-deploy-laravel-8-at-hostinger/
- Hostinger Git : https://www.hostinger.com/support/1583302-how-to-deploy-a-git-repository-in-hostinger/
- Stripe PHP : https://docs.stripe.com/get-started/development-environment?lang=php
- PayPal PHP Server SDK : https://github.com/paypal/PayPal-PHP-Server-SDK
