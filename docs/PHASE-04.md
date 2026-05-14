# PHASE-04.md — Catalogue, fiche produit et recherche de pièces

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

Créer le catalogue public consultable sans compte, avec liste paginée, recherche par nom/référence/marque/modèle, et fiche produit détaillée.

---

## 3. Checklist des tâches

- [ ] Créer `Public\ProductController`.
- [ ] Créer route `GET /products` nommée `products.index`.
- [ ] Créer route `GET /products/{product:slug}` nommée `products.show`.
- [ ] Créer la vue liste produits avec filtres simples.
- [ ] Créer la fiche produit avec image, description, état, prix, stock.
- [ ] Afficher les compatibilités véhicule.
- [ ] Afficher bouton Ajouter au panier si stock > 0.
- [ ] Afficher bouton Je recherche cette pièce si stock = 0.
- [ ] Ajouter pagination Laravel.
- [ ] Ajouter badges d’état et disponibilité.
- [ ] Prévoir image placeholder si aucune image.

---

## 4. Fichiers à créer ou modifier

- `app/Http/Controllers/Public/ProductController.php`
- `routes/web.php`
- `resources/views/public/products/index.blade.php`
- `resources/views/public/products/show.blade.php`
- `resources/views/components/product-card.blade.php`
- `resources/views/layouts/public.blade.php`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-04.md puis on commence. Exécute uniquement la phase 04 : catalogue public, recherche nom/référence/marque/modèle, fiche produit, affichage stock, bouton panier ou demande de pièce. Avant de coder, résume les routes et les requêtes Eloquent prévues.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Le catalogue doit être consultable sans compte.
- Recherche attendue :
  - `q` cherche dans `products.name`, `products.sku`, `products.oem_reference`, `products.description`.
  - `make` filtre par marque via `product_compatibilities`.
  - `model` filtre par modèle via `product_compatibilities`.
  - `category` filtre par catégorie.
- Ne pas ajouter de moteur de recherche externe.
- La fiche produit ne doit afficher que les produits `status = active`.


---

## 7. Tests manuels attendus

- Ouvrir `/products`.
- Rechercher par nom.
- Rechercher par SKU.
- Rechercher par référence constructeur.
- Filtrer par marque.
- Filtrer par modèle.
- Ouvrir une fiche produit active.
- Vérifier que le bouton panier apparaît si stock > 0.
- Vérifier que le bouton demande de pièce apparaît si stock = 0.
