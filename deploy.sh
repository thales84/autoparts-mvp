#!/bin/bash
# deploy.sh — Déploiement Hostinger via SSH
# Usage : bash deploy.sh
# Prérequis : .env configuré sur le serveur, clé SSH ajoutée dans hPanel

set -e

echo "=== PALERME AUTO PRO — Déploiement ==="

# 1. Récupérer les dernières modifications
echo ">> git pull..."
git pull origin main

# 2. Installer les dépendances sans dev
echo ">> composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Migrations
echo ">> migrations..."
php artisan migrate --force

# 4. Mettre en cache configuration, routes, vues
echo ">> cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Vider le cache application
php artisan cache:clear

echo ""
echo "=== Déploiement terminé ==="
echo "    URL : $(grep APP_URL .env | cut -d= -f2)"
