# PHASE-08.md — Interface admin

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

Créer une interface admin simple pour gérer le catalogue, consulter les commandes et traiter les demandes de pièces.

---

## 3. Checklist des tâches

- [ ] Créer `Admin\DashboardController`.
- [ ] Créer `Admin\ProductController` CRUD.
- [ ] Créer `Admin\OrderController` lecture + changement statut.
- [ ] Créer `Admin\PartRequestController` lecture + changement statut.
- [ ] Créer les routes admin protégées par `auth` et `admin`.
- [ ] Créer dashboard avec compteurs : produits, commandes, demandes.
- [ ] Créer liste produits admin.
- [ ] Créer formulaire création produit avec upload image.
- [ ] Créer formulaire édition produit avec gestion stock/statut.
- [ ] Créer suppression produit avec prudence.
- [ ] Créer liste commandes.
- [ ] Créer détail commande.
- [ ] Créer changement statut commande.
- [ ] Créer liste demandes de pièces.
- [ ] Créer détail demande et notes admin.
- [ ] Ajouter navigation admin claire.

---

## 4. Fichiers à créer ou modifier

- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/ProductController.php`
- `app/Http/Controllers/Admin/OrderController.php`
- `app/Http/Controllers/Admin/PartRequestController.php`
- `app/Http/Requests/StoreProductRequest.php`
- `app/Http/Requests/UpdateProductRequest.php`
- `routes/web.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/products/index.blade.php`
- `resources/views/admin/products/create.blade.php`
- `resources/views/admin/products/edit.blade.php`
- `resources/views/admin/orders/index.blade.php`
- `resources/views/admin/orders/show.blade.php`
- `resources/views/admin/part-requests/index.blade.php`
- `resources/views/admin/part-requests/show.blade.php`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-08.md puis on commence. Exécute uniquement la phase 08 : interface admin Blade protégée par auth/admin, dashboard, CRUD produits avec upload image, consultation commandes, changement statut, consultation demandes de pièces et notes admin. Avant de coder, liste les routes admin prévues.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Toutes les routes `/admin/*` doivent être protégées.
- L’upload doit respecter les règles de sécurité de `CLAUDE.md`.
- Le CRUD produit doit gérer :
  - nom ;
  - SKU ;
  - référence constructeur ;
  - catégorie ;
  - description ;
  - état ;
  - prix ;
  - stock ;
  - statut ;
  - image principale.
- La suppression produit doit être prudente :
  - préférer passer `status = inactive` si le produit a déjà été commandé.


---

## 7. Tests manuels attendus

- Accéder `/admin` avec admin.
- Vérifier compteurs dashboard.
- Créer un produit avec image.
- Modifier un produit.
- Désactiver un produit.
- Vérifier que le produit actif apparaît dans le catalogue public.
- Vérifier qu’un produit inactif n’apparaît pas.
- Consulter une commande.
- Modifier statut commande.
- Consulter une demande de pièce.
- Ajouter note admin et changer statut.
