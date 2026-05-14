# PHASE-01.md — Setup Laravel + configuration Hostinger

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

Créer la base Laravel compatible Hostinger mutualisé, configurer l’environnement, préparer la structure de déploiement, les layouts Blade et les fichiers `.htaccess` nécessaires.

---

## 3. Checklist des tâches

- [ ] Créer un nouveau projet Laravel 13 avec Composer.
- [ ] Configurer `.env.example` pour MySQL, sessions fichier, cache fichier, queue sync, timezone Africa/Douala.
- [ ] Supprimer toute dépendance frontend nécessitant Node.js si elle est présente.
- [ ] Créer les layouts Blade publics/admin avec Bootstrap 5 CDN.
- [ ] Créer une page d’accueil simple.
- [ ] Configurer les routes initiales dans `routes/web.php`.
- [ ] Préparer `.htaccess` pour Hostinger.
- [ ] Préparer le dossier `public/uploads/products`.
- [ ] Créer `public/uploads/.htaccess` pour bloquer l’exécution PHP.
- [ ] Documenter les commandes de déploiement SSH/Git dans un bloc de commentaires ou dans `ARCHITECTURE.md` si nécessaire.

---

## 4. Fichiers à créer ou modifier

- `composer.json`
- `.env.example`
- `routes/web.php`
- `resources/views/layouts/public.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/public/home.blade.php`
- `public/.htaccess`
- `public/uploads/.htaccess`
- `public/uploads/products/.gitkeep`
- `README.md`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-01.md puis on commence. Exécute uniquement la phase 01 : setup Laravel 13 compatible Hostinger mutualisé, sans Node.js, avec Blade + Bootstrap CDN, configuration `.env.example`, routes initiales, layouts et `.htaccess`. Avant de coder, résume ton plan et liste les fichiers à créer ou modifier.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Le layout public doit contenir une navbar avec : Accueil, Catalogue, Panier, Connexion.
- Le layout admin doit contenir une navigation simple vers : Dashboard, Produits, Commandes, Demandes.
- Les CDN Bootstrap doivent être dans les layouts.
- Le dossier `public/uploads/products` doit être versionné via `.gitkeep`.
- Le `.htaccess` public ne doit pas exposer `.env` ni les fichiers sensibles.


---

## 7. Tests manuels attendus

- Lancer `php artisan serve` en local.
- Ouvrir `/` et vérifier que la page d’accueil s’affiche.
- Vérifier que Bootstrap est chargé via CDN.
- Vérifier que `public/uploads/.htaccess` existe.
- Vérifier que `npm` n’est pas nécessaire pour afficher l’interface.
- Vérifier que `.env` n’est pas commité.
