# PHASE-02.md — Base de données, migrations et modèles Eloquent

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

Créer le modèle de données MySQL du MVP avec migrations, modèles Eloquent, relations, factories/seeders minimaux et données initiales utiles.

---

## 3. Checklist des tâches

- [ ] Créer les migrations pour users, categories, vehicle_makes, vehicle_models.
- [ ] Créer les migrations pour products, product_images, product_compatibilities.
- [ ] Créer les migrations pour part_requests, orders, order_items, payments.
- [ ] Ajouter les index nécessaires pour la recherche catalogue.
- [ ] Définir les clés étrangères avec comportement cohérent.
- [ ] Créer les modèles Eloquent avec `$fillable`, casts et relations.
- [ ] Créer un seeder admin initial.
- [ ] Créer des seeders de catégories, marques et modèles de test.
- [ ] Créer quelques produits de démonstration sans images réelles.
- [ ] Tester `php artisan migrate:fresh --seed`.

---

## 4. Fichiers à créer ou modifier

- `database/migrations/*_create_categories_table.php`
- `database/migrations/*_create_vehicle_makes_table.php`
- `database/migrations/*_create_vehicle_models_table.php`
- `database/migrations/*_create_products_table.php`
- `database/migrations/*_create_product_images_table.php`
- `database/migrations/*_create_product_compatibilities_table.php`
- `database/migrations/*_create_part_requests_table.php`
- `database/migrations/*_create_orders_table.php`
- `database/migrations/*_create_order_items_table.php`
- `database/migrations/*_create_payments_table.php`
- `app/Models/User.php`
- `app/Models/Category.php`
- `app/Models/VehicleMake.php`
- `app/Models/VehicleModel.php`
- `app/Models/Product.php`
- `app/Models/ProductImage.php`
- `app/Models/ProductCompatibility.php`
- `app/Models/PartRequest.php`
- `app/Models/Order.php`
- `app/Models/OrderItem.php`
- `app/Models/Payment.php`
- `database/seeders/AdminUserSeeder.php`
- `database/seeders/CatalogSeeder.php`
- `database/seeders/DatabaseSeeder.php`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-02.md puis on commence. Exécute uniquement la phase 02 : migrations MySQL, modèles Eloquent, relations, casts, fillable, seeders admin/catalogue. Avant de coder, résume le modèle de données et liste les fichiers à créer ou modifier.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Utiliser `decimal(12, 2)` pour les montants.
- Utiliser `string('currency', 3)`.
- Ajouter des index sur `products.name`, `products.sku`, `products.oem_reference`, `products.slug`, `products.status`.
- Pour les recherches multi-champs, prévoir des requêtes `LIKE` simples pour le MVP.
- Ne pas utiliser Scout/Meilisearch/Algolia.
- Créer un admin initial avec email configurable dans le seeder ou documenté.


---

## 7. Tests manuels attendus

- Exécuter `php artisan migrate:fresh --seed`.
- Vérifier qu’un utilisateur admin existe.
- Vérifier que des catégories, marques, modèles et produits de test existent.
- Vérifier les relations dans Tinker :
  - `Product::first()->category`
  - `Product::first()->images`
  - `Product::first()->compatibilities`
- Vérifier que les contraintes FK ne provoquent pas d’erreurs.
