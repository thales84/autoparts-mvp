# PHASE-06.md — Panier et passage de commande

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

Créer le panier en session, permettre l’ajout/retrait/mise à jour, puis créer une commande en base avant paiement.

---

## 3. Checklist des tâches

- [ ] Créer `CartService`.
- [ ] Créer `Public\CartController`.
- [ ] Créer `Public\CheckoutController`.
- [ ] Créer `CheckoutRequest`.
- [ ] Créer les routes panier : afficher, ajouter, modifier quantité, retirer, vider.
- [ ] Stocker le panier en session.
- [ ] Recalculer prix et total depuis la base.
- [ ] Créer la page panier.
- [ ] Créer la page checkout.
- [ ] Forcer la connexion pour checkout.
- [ ] Créer une commande `pending/unpaid` depuis le panier.
- [ ] Créer les `order_items` snapshots.
- [ ] Ne pas décrémenter le stock avant paiement confirmé.
- [ ] Rediriger ensuite vers la phase paiement ou page commande créée.

---

## 4. Fichiers à créer ou modifier

- `app/Services/Cart/CartService.php`
- `app/Services/Orders/OrderService.php`
- `app/Http/Controllers/Public/CartController.php`
- `app/Http/Controllers/Public/CheckoutController.php`
- `app/Http/Requests/CheckoutRequest.php`
- `routes/web.php`
- `resources/views/public/cart/index.blade.php`
- `resources/views/public/checkout/show.blade.php`
- `resources/views/public/checkout/pending.blade.php`
- `resources/views/layouts/public.blade.php`

---

## 5. Prompt exact à utiliser en début de session Claude Code

```text
Lis CLAUDE.md et PHASE-06.md puis on commence. Exécute uniquement la phase 06 : panier session, ajout/retrait/mise à jour, checkout connecté, création commande pending/unpaid avec order_items snapshots. Avant de coder, détaille le flux panier → commande.
```

---

## 6. Contraintes techniques de la phase

- Respecter strictement `CLAUDE.md`.
- Ne pas utiliser Node.js, npm, Vite, React ou Vue.
- Utiliser Blade et Bootstrap 5 CDN.
- Utiliser des noms de routes explicites.
- Ajouter des validations côté serveur.
- Ne pas coder les phases futures sauf squelette minimal indispensable.

- Le panier ne doit pas stocker les prix de manière fiable.
- Au moment de créer la commande, vérifier :
  - produit actif ;
  - stock suffisant ;
  - quantité positive.
- Utiliser une transaction DB lors de la création de commande.
- La décrémentation du stock viendra après paiement confirmé.


---

## 7. Tests manuels attendus

- Ajouter un produit au panier.
- Modifier la quantité.
- Supprimer un produit.
- Vider le panier.
- Tenter checkout sans connexion : redirection login.
- Faire checkout connecté.
- Vérifier création `orders` avec `pending/unpaid`.
- Vérifier création `order_items`.
- Vérifier que le stock n’a pas encore baissé.
