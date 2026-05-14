# PHASE-03.md — Authentification inscription / connexion / sessions / middleware

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

Créer une authentification simple et robuste avec sessions Laravel, sans starter kit, adaptée au MVP et à l’hébergement mutualisé.

---

## 3. Checklist des tâches

- [ ] Créer les contrôleurs RegisterController, LoginController, LogoutController.
- [ ] Créer les Form Requests RegisterUserRequest et LoginRequest.
- [ ] Créer les vues Blade register/login.
- [ ] Créer les routes register/login/logout.
- [ ] Utiliser `Hash::make()` pour les mots de passe.
- [ ] Utiliser `Auth::attempt()` pour la connexion.
- [ ] Régénérer la session après connexion.
- [ ] Invalider la session après logout.
- [ ] Créer le middleware `EnsureUserIsAdmin`.
- [ ] Enregistrer l’alias middleware `admin`.
- [ ] Protéger les routes `/admin/*` avec `auth` + `admin`.
- [ ] Adapter la navbar selon utilisateur connecté/non connecté.

---

## 4. Fichiers à créer ou modifier

- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/LogoutController.php`
- `app/Http/Requests/RegisterUserRequest.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `bootstrap/app.php`
- `routes/web.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/layouts/public.blade.php`
- `resources/views/layouts/admin.blade.php`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-03.md puis on commence. Exécute uniquement la phase 03 : authentification manuelle Laravel par sessions, inscription, connexion, déconnexion, middleware admin, vues Blade. Avant de coder, explique brièvement le flux auth et liste les fichiers à modifier.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Ne pas installer Breeze, Jetstream, Fortify ou Sanctum.
- Les routes admin doivent rediriger vers `/login` si l’utilisateur n’est pas connecté.
- Si l’utilisateur connecté n’est pas admin, retourner 403.
- Les erreurs de validation doivent s’afficher dans les vues.


---

## 7. Tests manuels attendus

- Créer un compte client depuis `/register`.
- Se connecter depuis `/login`.
- Vérifier que la session est active.
- Se déconnecter via `POST /logout`.
- Tenter d’accéder à `/admin` sans être connecté : redirection login.
- Tenter d’accéder à `/admin` avec compte client : erreur 403.
- Accéder à `/admin` avec compte admin : accès autorisé.
