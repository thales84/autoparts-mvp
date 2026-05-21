# CI/CD Pipeline — GitHub Actions + Hostinger Mutualisé

Pipeline automatique : push → tests → déploiement SSH → notification mail en cas d'erreur.

---

## Vue d'ensemble

```
git push origin main
       │
       ▼
┌─────────────────┐
│   Job 1 : TEST  │  PHPUnit sur SQLite :memory:
└────────┬────────┘
         │ OK
         ▼
┌─────────────────┐
│  Job 2 : DEPLOY │  SSH → git pull + composer + migrate + cache
└────────┬────────┘
         │ ERREUR (n'importe quel job)
         ▼
┌─────────────────┐
│ Job 3 : NOTIFY  │  Email Gmail → toi
└─────────────────┘
```

Durée totale : **2 à 4 minutes** selon la taille du projet.

---

## Prérequis

| Élément | Détail |
|---|---|
| Dépôt GitHub | public ou privé, peu importe |
| Hébergement | Hostinger mutualisé avec SSH activé |
| PHP | 8.3 (configuré dans hPanel) |
| Compte Gmail | avec **App Password** (mot de passe d'application) |
| Machine locale | PowerShell ou terminal Unix |

---

## Étape 1 — Activer SSH sur Hostinger

1. **hPanel → Advanced → SSH Access → Activer**
2. Noter :
   - **Host** : quelque chose comme `ssh.hostinger.com`
   - **Port** : généralement `65002`
   - **Username** : quelque chose comme `u783045005`

---

## Étape 2 — Générer une paire de clés SSH

Cette clé servira à GitHub Actions pour se connecter à Hostinger **sans mot de passe**.

### Sur Windows (PowerShell)

```powershell
ssh-keygen -t ed25519 -C "github-actions-deploy"
# Chemin proposé : C:\Users\TON_USER\.ssh\id_ed25519_deploy
# Passphrase : laisser vide (appuyer Entrée deux fois)
```

> ⚠️ Ne pas utiliser `-N ""` dans PowerShell — ça plante. Laisser vide en appuyant Entrée.

### Sur Linux/Mac

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -N "" -f ~/.ssh/id_ed25519_deploy
```

Deux fichiers générés :
- `id_ed25519_deploy` → **clé privée** (pour GitHub Secrets)
- `id_ed25519_deploy.pub` → **clé publique** (pour Hostinger)

---

## Étape 3 — Ajouter la clé publique sur Hostinger

**hPanel → Advanced → SSH Access → SSH Keys → Add SSH Key**

Copier le contenu de `id_ed25519_deploy.pub` :

```powershell
# Windows
Get-Content C:\Users\TON_USER\.ssh\id_ed25519_deploy.pub
```

```bash
# Linux/Mac
cat ~/.ssh/id_ed25519_deploy.pub
```

Coller dans le champ et valider.

### Vérification (optionnel)

```bash
ssh -p 65002 -i ~/.ssh/id_ed25519_deploy u783045005@ssh.hostinger.com
# Doit se connecter sans demander de mot de passe
```

---

## Étape 4 — Créer un App Password Gmail

Pour que GitHub Actions puisse envoyer des mails via ton compte Gmail :

1. **Google Account → Sécurité → Validation en 2 étapes** (doit être activée)
2. **Sécurité → Mots de passe des applications**
3. Nom de l'application : `GitHub Actions`
4. Générer → noter le mot de passe **16 caractères** (ex: `abcd efgh ijkl mnop`)

> ⚠️ Ne jamais utiliser le vrai mot de passe Gmail — toujours un App Password.

---

## Étape 5 — Ajouter les secrets dans GitHub

**Dépôt GitHub → Settings → Secrets and variables → Actions → New repository secret**

| Nom du secret | Valeur |
|---|---|
| `SSH_HOST` | `ssh.hostinger.com` (le host SSH Hostinger) |
| `SSH_PORT` | `65002` |
| `SSH_USER` | `u783045005` (ton username Hostinger) |
| `SSH_PRIVATE_KEY` | Contenu complet de `id_ed25519_deploy` (clé privée) |
| `MAIL_USERNAME` | `ton-email@gmail.com` |
| `MAIL_PASSWORD` | App Password Gmail (16 caractères, sans espaces) |

Pour copier la clé privée :

```powershell
# Windows
Get-Content C:\Users\TON_USER\.ssh\id_ed25519_deploy
```

```bash
# Linux/Mac
cat ~/.ssh/id_ed25519_deploy
```

Copier **tout** le contenu, y compris les lignes `-----BEGIN...-----` et `-----END...-----`.

---

## Étape 6 — Créer le workflow GitHub Actions

Créer le fichier `.github/workflows/deploy.yml` à la racine du projet :

```yaml
name: Test & Deploy — TON-DOMAINE.com

on:
  push:
    branches: [main]

jobs:

  # ─────────────────────────────────────────
  # 1. TESTS — bloque le déploiement si échec
  # ─────────────────────────────────────────
  test:
    name: Tests PHP 8.3
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP 8.3
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring, pdo, pdo_sqlite, gd, xml, zip, intl
          coverage: none

      - name: Installer les dépendances
        run: composer install --optimize-autoloader --no-interaction --ignore-platform-reqs

      - name: Configurer l'environnement de test
        run: |
          cp .env.example .env
          echo "APP_ENV=testing" >> .env
          echo "APP_KEY=" >> .env
          echo "DB_CONNECTION=sqlite" >> .env
          echo "DB_DATABASE=:memory:" >> .env
          php artisan key:generate

      - name: Migrations en mémoire
        run: php artisan migrate --force

      - name: Lancer les tests
        run: php artisan test --stop-on-failure

  # ─────────────────────────────────────────
  # 2. DÉPLOIEMENT — uniquement si tests OK
  # ─────────────────────────────────────────
  deploy:
    name: Déploiement Hostinger
    runs-on: ubuntu-latest
    needs: test

    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          port: ${{ secrets.SSH_PORT }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            set -e
            cd ~/domains/TON-DOMAINE.com/public_html

            echo ">> git pull"
            git pull origin main

            echo ">> composer"
            composer install --no-dev --optimize-autoloader --no-interaction

            echo ">> migrate"
            php artisan migrate --force

            echo ">> cache"
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            php artisan cache:clear

            echo ">> permissions"
            chmod -R 775 storage bootstrap/cache

            echo ">> done"

  # ─────────────────────────────────────────
  # 3. NOTIFICATION — email si erreur
  # ─────────────────────────────────────────
  notify:
    name: Notification erreur
    runs-on: ubuntu-latest
    needs: [test, deploy]
    if: failure()

    steps:
      - name: Envoyer email d'erreur
        uses: dawidd6/action-send-mail@v3
        with:
          server_address: smtp.gmail.com
          server_port: 465
          secure: true
          username: ${{ secrets.MAIL_USERNAME }}
          password: ${{ secrets.MAIL_PASSWORD }}
          subject: "Erreur déploiement — TON-DOMAINE.com"
          to: ton-email@gmail.com
          from: ton-email@gmail.com
          body: |
            Une erreur s'est produite lors du pipeline CI/CD.

            Repo    : ${{ github.repository }}
            Branche : ${{ github.ref_name }}
            Commit  : ${{ github.sha }}
            Auteur  : ${{ github.actor }}
            Message : ${{ github.event.head_commit.message }}

            Voir les logs complets :
            ${{ github.server_url }}/${{ github.repository }}/actions/runs/${{ github.run_id }}
```

### Remplacer dans le fichier

| Placeholder | Remplacer par |
|---|---|
| `TON-DOMAINE.com` | ton vrai domaine (`palermeautopro.shop`) |
| `ton-email@gmail.com` | ton adresse Gmail |

---

## Étape 7 — Premier déploiement (setup initial sur le serveur)

Avant que le pipeline puisse faire des `git pull`, le projet doit déjà être cloné sur le serveur.

```bash
# Connexion SSH
ssh -p 65002 u783045005@ssh.hostinger.com

# Cloner dans le bon dossier (PAS ~/public_html/ !)
cd ~/domains/TON-DOMAINE.com/
rm -rf public_html/
git clone https://github.com/TON-USERNAME/TON-REPO.git public_html
cd public_html

# Configurer l'environnement
cp .env.example .env
nano .env
# Renseigner : APP_KEY (vide), DB_DATABASE, DB_USERNAME, DB_PASSWORD, MAIL_PASSWORD

# Initialiser
composer install --no-dev --optimize-autoloader --no-interaction
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force

# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

> ⚠️ Sur Hostinger avec plusieurs sites, le document root d'un domaine additionnel est **toujours** `~/domains/DOMAINE/public_html/` — jamais `~/public_html/` (qui correspond au domaine principal du compte).

---

## Étape 8 — Déclencher le pipeline

```bash
# En local — modifier un fichier quelconque, puis :
git add .
git commit -m "test: premier déploiement automatique"
git push origin main
```

Suivre l'exécution : **GitHub → Actions** (onglet en haut du dépôt).

3 jobs apparaissent :
- `Tests PHP 8.3` → doit passer au vert
- `Déploiement Hostinger` → doit passer au vert
- `Notification erreur` → doit être skippé (gris) si tout va bien

---

## Structure complète des fichiers ajoutés

```
projet/
├── .github/
│   └── workflows/
│       └── deploy.yml     ← pipeline CI/CD
├── .env.example           ← doit exister et être complet
└── deploy.sh              ← script optionnel pour déploiement manuel SSH
```

---

## Erreurs fréquentes et solutions

### `composer install` échoue en CI — platform requirements

```
symfony/clock is locked to version v8.0.8 but that does not satisfy
your minimum-stability.
```

**Solution :** ajouter `--ignore-platform-reqs` dans la step composer du job `test`.

---

### L'email de notification ne part pas — `Input required: from`

**Cause :** utiliser `${{ secrets.MAIL_USERNAME }}` dans le champ `from` ne fonctionne pas toujours.  
**Solution :** hardcoder l'adresse email dans `to:` et `from:` directement dans le YAML.

---

### SSH échoue — `Permission denied (publickey)`

Vérifications dans l'ordre :
1. La clé publique est bien ajoutée dans **hPanel → SSH Keys**
2. La clé privée dans le secret `SSH_PRIVATE_KEY` est complète (avec `-----BEGIN...-----`)
3. Host et port corrects dans les secrets

---

### `git pull` échoue — `not a git repository`

Le setup initial n'a pas été fait (Étape 7). Le pipeline ne peut pas cloner — il peut seulement `pull`.

---

### Le site affiche la page par défaut Hostinger après déploiement

**Cause :** projet cloné dans `~/public_html/` au lieu de `~/domains/DOMAINE/public_html/`.  
**Solution :** toujours vérifier le bon chemin dans `ls ~/domains/`.

---

## Commandes utiles en production

```bash
# Connexion SSH
ssh -p 65002 u783045005@ssh.hostinger.com

# Logs Laravel en temps réel
tail -f ~/domains/TON-DOMAINE.com/public_html/storage/logs/laravel.log

# Vider tous les caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Mode maintenance (pendant une migration lourde)
php artisan down
php artisan up

# Voir la version PHP active
php -v
```

---

## Récapitulatif des secrets GitHub à configurer

| Secret | Description |
|---|---|
| `SSH_HOST` | Hostname SSH Hostinger |
| `SSH_PORT` | Port SSH (généralement 65002) |
| `SSH_USER` | Username Hostinger |
| `SSH_PRIVATE_KEY` | Clé privée ed25519 complète |
| `MAIL_USERNAME` | Adresse Gmail |
| `MAIL_PASSWORD` | App Password Gmail (16 chars) |
