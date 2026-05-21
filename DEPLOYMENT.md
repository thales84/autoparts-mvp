# Déploiement — Hostinger Mutualisé

Guide basé sur le déploiement réel de `palermeautopro.shop`.

---

## Architecture Hostinger à connaître

Sur Hostinger mutualisé avec plusieurs sites, le document root n'est **pas** `~/public_html/` mais :

```
~/domains/VOTRE-DOMAINE/public_html/
```

> `~/public_html/` correspond au domaine principal du compte, pas aux domaines additionnels. Toujours vérifier dans **hPanel → Websites → Manage** quel dossier est servi.

---

## Prérequis sur hPanel

1. **Base de données MySQL**
   - hPanel → Bases de données → MySQL → Créer une base
   - Créer un utilisateur et l'associer à la base
   - Noter : nom de la base, utilisateur, mot de passe

2. **SSH activé**
   - hPanel → Advanced → SSH Access → Activer
   - Noter : host SSH, port (généralement `65002`), nom d'utilisateur

3. **PHP 8.3 configuré**
   - hPanel → Hosting → Manage → PHP Configuration → sélectionner 8.3

4. **SSL activé** (normalement automatique sur Hostinger)
   - hPanel → SSL → vérifier que le certificat est actif pour le domaine

---

## Déploiement initial (première fois)

### 1. Connexion SSH

```bash
ssh -p 65002 u783045005@ssh.hostinger.com
```

### 2. Cloner dans le bon dossier

```bash
rm -rf ~/domains/palermeautopro.shop/public_html/
git clone https://github.com/thales84/autoparts-mvp.git ~/domains/palermeautopro.shop/public_html
cd ~/domains/palermeautopro.shop/public_html
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
nano .env
```

Valeurs à renseigner :

```env
APP_KEY=                          # laisser vide, généré à l'étape suivante
DB_DATABASE=u783045005_autoparts  # nom exact de votre base Hostinger
DB_USERNAME=u783045005_autoparts  # utilisateur DB Hostinger
DB_PASSWORD=VotreMotDePasseDB
MAIL_PASSWORD=VotreMotDePasseMail
```

`Ctrl+O` → Entrée → `Ctrl+X` pour sauvegarder.

### 4. Installer et initialiser

```bash
composer install --no-dev --optimize-autoloader --no-interaction
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
```

### 5. Optimiser pour la production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

### 6. Vérifier

Ouvrir `https://palermeautopro.shop` dans le navigateur.

---

## Mises à jour (déploiements suivants)

```bash
ssh -p 65002 u783045005@ssh.hostinger.com
cd ~/domains/palermeautopro.shop/public_html
bash deploy.sh
```

Le script `deploy.sh` fait automatiquement : `git pull` → `composer install` → `migrate` → `config:cache` → `route:cache` → `view:cache` → `cache:clear`.

---

## Erreurs rencontrées et solutions

### Le site affiche la page par défaut Hostinger
**Cause :** le projet a été cloné dans `~/public_html/` au lieu de `~/domains/palermeautopro.shop/public_html/`.  
**Solution :** cloner dans le bon dossier (voir étape 2).

### 404 sur tous les fichiers même existants
**Cause :** même raison — le document root ne correspond pas au dossier cloné.  
**Solution :** idem.

### PHP ne s'exécute pas (`php -r` sans sortie)
**Cause :** PHP CLI disponible mais pas dans le `PATH` par défaut de la session SSH.  
**Vérification :** `which php` → `/opt/alt/php83/usr/bin/php`  
**Solution :** utiliser simplement `php` (l'alias fonctionne), ou configurer PHP 8.3 dans hPanel.

### Symlink `public_html` → `palermeautopro/public` ne fonctionne pas
**Cause :** Hostinger configure le document root Apache en dur dans le vhost — les symlinks ne sont pas suivis au niveau du document root.  
**Solution :** ne pas utiliser de symlink, cloner directement dans le bon dossier.

### Erreur 500 après déploiement
**Cause :** permissions `storage/` ou `bootstrap/cache/` incorrectes, ou `.env` mal configuré.  
**Solution :**
```bash
chmod -R 775 storage bootstrap/cache
php artisan config:clear && php artisan config:cache
tail -50 storage/logs/laravel.log
```

---

## Structure sur le serveur

```
/home/u783045005/
├── domains/
│   └── palermeautopro.shop/
│       └── public_html/        ← document root Apache (tout le projet Laravel)
│           ├── .htaccess        ← redirige vers public/
│           ├── public/
│           │   ├── .htaccess   ← routing Laravel + HTTPS + headers sécu
│           │   └── index.php
│           ├── app/
│           ├── storage/
│           └── ...
├── public_html/                 ← domaine principal du compte (pas palermeautopro.shop)
└── ...
```

---

## Commandes utiles en production

```bash
# Voir les logs Laravel
tail -f storage/logs/laravel.log

# Vider tous les caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Mode maintenance
php artisan down
php artisan up

# Vérifier la connexion DB
php artisan db:show
```
