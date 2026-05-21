# Prompt — Demander à un agent IA de mettre en place le pipeline CI/CD

Copier-coller ce prompt dans Claude Code (ou tout autre agent IA) au début d'un nouveau projet.
Adapter les valeurs entre `< >`.

---

## Prompt complet

```
Je veux mettre en place un pipeline CI/CD GitHub Actions pour déployer automatiquement
mon projet Laravel sur Hostinger mutualisé à chaque push sur main.

## Stack technique
- PHP 8.3 + Laravel 13
- MySQL en production, SQLite :memory: pour les tests CI
- Blade + Bootstrap CDN (pas de Vite, pas de Node.js)
- Hébergement : Hostinger mutualisé (SSH restreint, pas de root, pas de Docker)

## Comportement attendu du pipeline (3 jobs dans l'ordre)

1. **Tests** (job : test)
   - Runner : ubuntu-latest
   - PHP 8.3 via shivammathur/setup-php@v2
   - Extensions : mbstring, pdo, pdo_sqlite, gd, xml, zip, intl
   - composer install --optimize-autoloader --no-interaction --ignore-platform-reqs
   - Copier .env.example → .env, ajouter APP_ENV=testing, DB_CONNECTION=sqlite,
     DB_DATABASE=:memory:, générer APP_KEY via php artisan key:generate
   - php artisan migrate --force
   - php artisan test --stop-on-failure
   - Si ce job échoue → bloquer le déploiement

2. **Déploiement** (job : deploy, needs: test)
   - Se connecter à Hostinger via SSH avec appleboy/ssh-action@v1.0.3
   - Secrets à utiliser : SSH_HOST, SSH_PORT, SSH_USER, SSH_PRIVATE_KEY
   - Script SSH à exécuter :
     set -e
     cd ~/domains/<MON-DOMAINE.com>/public_html
     git pull origin main
     composer install --no-dev --optimize-autoloader --no-interaction
     php artisan migrate --force
     php artisan config:cache && php artisan route:cache && php artisan view:cache
     php artisan cache:clear
     chmod -R 775 storage bootstrap/cache

3. **Notification mail** (job : notify, needs: [test, deploy], if: failure())
   - Utiliser dawidd6/action-send-mail@v3
   - SMTP Gmail : smtp.gmail.com, port 465, secure: true
   - Secrets : MAIL_USERNAME, MAIL_PASSWORD
   - Destinataire : <mon-email@gmail.com>
   - Expéditeur hardcodé (ne pas utiliser ${{ secrets.MAIL_USERNAME }} dans le champ from)
   - Body : inclure github.repository, github.ref_name, github.sha, github.actor,
     github.event.head_commit.message, lien vers les logs du run

## Fichier à créer
.github/workflows/deploy.yml

## Ce que je veux en plus du fichier YAML

1. La liste exacte des 6 secrets GitHub à créer (nom + description de la valeur)
2. Les commandes pour générer la paire de clés SSH ed25519 sur Windows PowerShell
   (sans -N "" car ça plante sur PowerShell — laisser passphrase vide en appuyant Entrée)
3. Les commandes pour le setup initial sur le serveur (git clone dans le bon dossier,
   composer install, key:generate, migrate, seed, config:cache, chmod)
4. Les erreurs fréquentes et leurs solutions (platform reqs en CI, from field email,
   SSH Permission denied, git pull not a git repository, mauvais document root Hostinger)

## Informations sur mon projet
- Domaine : <MON-DOMAINE.com>
- Chemin serveur : ~/domains/<MON-DOMAINE.com>/public_html
- Host SSH Hostinger : ssh.hostinger.com (port 65002)
- Username Hostinger : <u123456789>
- Email de notification : <mon-email@gmail.com>
- Dépôt GitHub : <https://github.com/MON-USERNAME/MON-REPO>

## Contrainte importante sur Hostinger
Sur Hostinger avec plusieurs sites, le document root d'un domaine additionnel est
~/domains/DOMAINE/public_html/ — JAMAIS ~/public_html/ (qui est le domaine principal du compte).
Le pipeline doit utiliser le bon chemin.
```

---

## Comment utiliser ce prompt

1. Ouvrir un nouveau projet dans Claude Code
2. S'assurer que le projet Laravel est initialisé et a un `.env.example` complet
3. Copier le prompt ci-dessus
4. Remplacer toutes les valeurs entre `< >` par les vraies valeurs du projet
5. Envoyer — l'agent crée le workflow, explique les secrets, donne les commandes

---

## Checklist avant de lancer le pipeline pour la première fois

- [ ] `.env.example` présent et complet (DB_, MAIL_, APP_ configurés)
- [ ] Paire de clés SSH générée (`id_ed25519_deploy` + `id_ed25519_deploy.pub`)
- [ ] Clé publique ajoutée dans hPanel → SSH Keys
- [ ] 6 secrets ajoutés dans GitHub → Settings → Secrets → Actions
- [ ] Setup initial fait sur le serveur (git clone dans le bon dossier, .env configuré)
- [ ] Premier push sur `main` déclenché
- [ ] GitHub → Actions → vérifier que les 3 jobs passent (test ✅, deploy ✅, notify ⬜)
