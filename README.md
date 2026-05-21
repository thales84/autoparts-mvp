# PALERME AUTO PRO — Pièces détachées automobiles d'occasion

Plateforme e-commerce de vente de pièces détachées automobiles d'occasion, construite avec Laravel 13 et pensée pour un hébergement mutualisé standard (Hostinger, PHP 8.3, MySQL).

---

## Fonctionnalités

### Côté client
- Catalogue de produits avec recherche (nom, SKU, référence OEM, description) et filtres (catégorie, marque, modèle)
- Fiche produit : galerie photos, compatibilités véhicule, état, stock en temps réel
- Panier session avec modal AJAX (ajout sans rechargement)
- Commande en ligne avec récapitulatif et informations de livraison
- Paiement par virement bancaire SEPA : coordonnées IBAN/BIC affichées, copie en un clic
- Soumission de preuve de paiement (image ou PDF)
- Suivi de commande avec barre de progression du paiement
- Téléchargement de documents PDF : devis, bon de commande, facture, reçu de versement
- Demande de recherche de pièce introuvable (formulaire avec préfill depuis la fiche produit)
- Espace compte : historique des commandes, modification du profil

### Côté admin (back-office)
- Dashboard avec statistiques : commandes, chiffre d'affaires, pièces faibles en stock, demandes en attente
- CRUD produits : photos multiples, SKU, référence OEM, état, catégorie, compatibilités véhicule
- Gestion des catégories
- Suivi et mise à jour du statut des commandes
- Validation / rejet des preuves de paiement (avec notes)
- Gestion des demandes de pièces
- Paramètres de paiement : coordonnées entreprise, mentions légales (SIRET, RCS, APE, TVA), IBAN/BIC, régime TVA
- Génération PDF admin pour chaque commande
- Modification du profil administrateur

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Backend | PHP 8.3 + Laravel 13 |
| Base de données | MySQL (prix en `decimal(12,2)`) |
| Frontend | Blade + Bootstrap 5 (CDN) |
| Authentification | Session Laravel (pas de Breeze/Jetstream) |
| Panier | Session Laravel (`[product_id => quantity]`) |
| PDF | `barryvdh/laravel-dompdf` |
| Hébergement cible | Hostinger mutualisé (PHP 8.3, MySQL, SSH limité) |

> **Contrainte hébergement :** pas de Docker, Redis, Node.js en production, npm build, queue worker ou WebSocket. Tout fonctionne avec `php artisan`, `composer`, `mysql` et `git` uniquement.

---

## Prérequis

- PHP 8.3+
- Composer
- MySQL 5.7+ ou 8.x
- Extension PHP : `pdo_mysql`, `gd` (pour les images), `mbstring`, `openssl`, `xml`

---

## Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/thales84/autoparts-mvp.git
cd autoparts-mvp

# 2. Installer les dépendances
composer install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate
```

Éditer `.env` :
```env
DB_DATABASE=autoparts_mvp
DB_USERNAME=root
DB_PASSWORD=

APP_LOCALE=fr
APP_TIMEZONE=Europe/Paris
```

```bash
# 4. Migrations + données de démonstration
php artisan migrate --seed

# 5. Lancer le serveur local
php artisan serve
```

L'application est accessible sur `http://localhost:8000`.

### Commande rapide (tout en une)

```bash
composer run setup
```

---

## Comptes par défaut (après seed)

| Rôle | E-mail | Mot de passe |
|------|--------|--------------|
| Administrateur | `admin@autoparts.local` | `Admin1234!` |
| Client test | `client@test.local` | `Client1234!` |

---

## Structure du projet

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Back-office (dashboard, produits, commandes, profil…)
│   │   ├── Auth/           # Login, register, logout
│   │   └── Public/         # Catalogue, panier, compte, checkout
│   ├── Middleware/
│   │   └── EnsureUserIsAdmin.php
│   └── Requests/           # Form Requests (StoreProductRequest, CheckoutRequest…)
├── Models/                 # Eloquent : User, Product, Order, OrderItem, PaymentProof, Setting…
├── Services/
│   ├── Cart/               # CartService — panier en session
│   ├── Orders/             # OrderService — création commande, confirmation paiement
│   └── Payments/           # PaymentGatewayInterface + implémentations
resources/
├── views/
│   ├── layouts/
│   │   ├── public.blade.php    # Layout toutes pages publiques
│   │   └── admin.blade.php     # Layout back-office avec sidebar
│   ├── admin/              # Vues back-office
│   ├── public/             # Vues publiques (home, catalogue, panier, compte…)
│   ├── auth/               # Login, register
│   ├── components/         # Composants Blade (product-card…)
│   └── pdf/                # Templates PDF (devis, bon_commande, facture, reçu…)
public/
└── uploads/
    ├── products/           # Photos produits (noms aléatoires, .htaccess anti-PHP)
    └── proofs/             # Preuves de paiement soumises par les clients
```

---

## Routes principales

### Public
| Méthode | URI | Description |
|---------|-----|-------------|
| GET | `/` | Page d'accueil |
| GET | `/products` | Catalogue (avec filtres) |
| GET | `/products/{slug}` | Fiche produit |
| GET | `/part-requests/create` | Formulaire demande de pièce |
| GET | `/cart` | Panier |
| POST | `/cart/add` | Ajouter au panier (JSON ou redirect) |

### Compte (authentifié)
| Méthode | URI | Description |
|---------|-----|-------------|
| GET | `/checkout` | Page de commande |
| GET | `/account/profile` | Modifier son profil |
| GET | `/account/orders` | Historique des commandes |
| GET | `/account/orders/{id}` | Détail commande + paiement |
| POST | `/account/orders/{id}/preuves` | Soumettre une preuve de paiement |
| GET | `/account/orders/{id}/devis` | Télécharger le devis PDF |

### Admin (`/admin`, middleware `admin`)
| URI | Description |
|-----|-------------|
| `/admin` | Dashboard |
| `/admin/products` | CRUD produits |
| `/admin/categories` | CRUD catégories |
| `/admin/orders` | Suivi commandes |
| `/admin/payment-proofs` | Validation preuves paiement |
| `/admin/payment-settings` | Paramètres de paiement / mentions légales |
| `/admin/profile` | Modifier le profil admin |

---

## Commandes utiles

```bash
# Développement
php artisan serve
php artisan migrate:fresh --seed    # Reset complet avec données de démo

# Tests
composer run test
php artisan test --filter NomDuTest

# Formatage code (Laravel Pint)
./vendor/bin/pint

# Cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Modèles de données clés

| Table | Description |
|-------|-------------|
| `users` | Clients et admins (`role: admin\|customer`, `status: active\|blocked`) |
| `products` | Pièces : SKU, prix, stock, état, catégorie, images, compatibilités |
| `orders` | Commandes : statut, total, adresse livraison |
| `order_items` | Snapshot immuable (nom, SKU, prix au moment de la commande) |
| `payment_proofs` | Preuves de virement soumises par les clients |
| `settings` | Paramètres clé-valeur (coordonnées, IBAN, TVA, mentions légales) |
| `categories` | Catégories produits |
| `vehicle_makes` | Marques de véhicules |
| `vehicle_models` | Modèles de véhicules |
| `product_compatibilities` | Liaison produit ↔ marque/modèle/années |

---

## Sécurité

- Fichiers uploadés dans `public/uploads/` avec `.htaccess` bloquant l'exécution PHP
- Noms de fichiers générés aléatoirement (`bin2hex(random_bytes(8))`) — jamais le nom original
- Mot de passe actuel exigé pour toute modification de profil
- Routes admin protégées par middleware `EnsureUserIsAdmin`
- Prix toujours recalculés depuis la base — jamais depuis la session ou les champs POST
- Stock décrémenté uniquement après confirmation de paiement validée

---

## Déploiement Hostinger

1. Uploader les fichiers via FTP ou `git pull` en SSH
2. Pointer le document root sur le dossier `public/`
3. Configurer `.env` avec les identifiants MySQL de production
4. Lancer `composer install --no-dev --optimize-autoloader`
5. Lancer `php artisan migrate --force`
6. Lancer `php artisan config:cache && php artisan route:cache && php artisan view:cache`

---

## Licence

Projet privé — tous droits réservés.
