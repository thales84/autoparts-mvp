# PHASE-05.md — Demande de pièce indisponible

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

Permettre à un visiteur ou client connecté de soumettre une demande lorsqu’une pièce est indisponible ou introuvable.

---

## 3. Checklist des tâches

- [ ] Créer `Public\PartRequestController`.
- [ ] Créer `StorePartRequestRequest`.
- [ ] Créer route `GET /part-requests/create`.
- [ ] Créer route `POST /part-requests`.
- [ ] Permettre le préremplissage via query string : part, reference, product_id.
- [ ] Créer la vue formulaire.
- [ ] Enregistrer la demande dans `part_requests`.
- [ ] Associer `user_id` si l’utilisateur est connecté.
- [ ] Afficher un message de succès.
- [ ] Ajouter lien depuis fiche produit indisponible.
- [ ] Ajouter lien depuis catalogue : “Vous ne trouvez pas votre pièce ?”.

---

## 4. Fichiers à créer ou modifier

- `app/Http/Controllers/Public/PartRequestController.php`
- `app/Http/Requests/StorePartRequestRequest.php`
- `routes/web.php`
- `resources/views/public/part-requests/create.blade.php`
- `resources/views/public/products/index.blade.php`
- `resources/views/public/products/show.blade.php`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-05.md puis on commence. Exécute uniquement la phase 05 : formulaire public de demande de pièce indisponible, validation, enregistrement, préremplissage depuis produit ou recherche. Avant de coder, liste les champs validés et les routes prévues.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Le formulaire doit être accessible sans compte.
- Champs obligatoires minimum :
  - nom de la pièce ;
  - nom du contact ;
  - téléphone ou email.
- Statut initial : `new`.
- Ne pas envoyer d’email obligatoire dans cette phase ; une notification peut être ajoutée plus tard.


---

## 7. Tests manuels attendus

- Depuis une fiche produit stock = 0, cliquer sur “Je recherche cette pièce”.
- Vérifier que le formulaire est prérempli.
- Soumettre sans téléphone/email : erreur.
- Soumettre correctement : demande créée.
- Vérifier en base que `status = new`.
- Vérifier que `user_id` est rempli si connecté et nul si visiteur.
