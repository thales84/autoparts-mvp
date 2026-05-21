# Guide — Remplir les Paramètres SEO

Référence complète pour configurer Admin → Configuration → SEO sur `palermeautopro.shop`.

---

## 1. Méta de base

### Titre SEO *(50–60 caractères max)*

Le titre apparaît dans l'onglet du navigateur et dans les résultats Google.

**Formule recommandée :** `Mot-clé principal — Nom de marque`

```
PALERME AUTO PRO — Pièces auto d'occasion à Palermo
```

> ✅ 52 caractères — optimal  
> ❌ Éviter : "Bienvenue sur notre site de vente de pièces automobiles d'occasion" (trop long, pas de marque)

---

### Meta description *(120–160 caractères max)*

Texte affiché sous le titre dans Google. N'affecte pas le classement directement mais **impacte le taux de clic**.

**Formule recommandée :** Avantage principal + appel à l'action + différenciation

```
Pièces détachées automobiles d'occasion vérifiées et garanties. Moteurs, boîtes, carrosserie. Livraison rapide. Commandez en ligne.
```

> ✅ 138 caractères — optimal  
> Inclure : ce qu'on vend, pourquoi choisir ce site, action attendue  
> ❌ Éviter : répéter le titre mot pour mot, phrases sans verbe

---

### Mots-clés *(séparés par des virgules)*

Impact faible sur Google mais utile pour Bing et Yahoo. Rester dans la thématique.

```
pièces auto occasion, pièces détachées automobiles, moteur occasion, boîte de vitesse occasion, carrosserie occasion, pièces auto vérifiées, acheter pièces auto
```

> Maximum 8–10 mots-clés. Pas de répétition inutile.

---

### Indexation robots

| Option | Quand l'utiliser |
|---|---|
| `index, follow` ✅ | **Par défaut** — site actif, prêt à être indexé |
| `index, nofollow` | Pages indexées mais liens non suivis (rare) |
| `noindex, follow` | Pages en cours de construction |
| `noindex, nofollow` | Site en développement / maintenance |

→ **Choisir `index, follow`** pour un site en production.

---

## 2. Open Graph — Réseaux sociaux

Ces champs contrôlent l'aperçu quand un lien est partagé sur **Facebook, LinkedIn, WhatsApp, Messenger**.

### Titre Open Graph *(optionnel — max 95 caractères)*

Si vide → utilise automatiquement le titre SEO. Peut être légèrement différent, plus "accrocheur" pour les réseaux.

```
🔧 Pièces auto d'occasion vérifiées — PALERME AUTO PRO
```

> Sur les réseaux, un emoji en début attire l'œil. Sur Google, éviter les emojis.

---

### Description Open Graph *(optionnel — max 200 caractères)*

Si vide → utilise la meta description. Peut être plus commerciale, plus directe.

```
Trouvez la pièce qu'il vous faut parmi notre catalogue de pièces automobiles d'occasion. Qualité vérifiée, prix abordables, livraison rapide.
```

---

### Image de partage OG *(1200 × 630 px)*

Image affichée lors du partage sur les réseaux sociaux.

**Spécifications techniques :**
- Taille : **1200 × 630 pixels** (ratio 1.91:1)
- Format : JPG recommandé (plus léger) ou PNG
- Poids : **< 1 Mo** idéalement
- Texte dans l'image : lisible en petite taille (l'image s'affiche à ~500px sur mobile)

**Contenu recommandé :**
- Fond aux couleurs de la marque (`#1B3A6B` bleu)
- Nom de la boutique bien visible
- Photo de pièces automobiles ou d'un atelier
- Pas de texte trop petit

**Outil gratuit pour redimensionner :** [squoosh.app](https://squoosh.app)

---

## 3. Outils Google

### Google Analytics 4 — ID de mesure

Permet de suivre les visiteurs, les pages vues, les conversions.

**Où trouver l'ID :**
1. Aller sur [analytics.google.com](https://analytics.google.com)
2. Si pas de compte → créer une propriété GA4 pour `palermeautopro.shop`
3. Administration → Flux de données → Flux web → copier l'**ID de mesure**

**Format :** `G-XXXXXXXXXX` (ex : `G-A1B2C3D4E5`)

> ⚠️ GA4 ne se charge qu'en production (`APP_ENV=production`). En local, il est ignoré automatiquement.

**Étapes pour créer un compte GA4 si inexistant :**
1. [analytics.google.com](https://analytics.google.com) → Commencer
2. Nom du compte : `Palerme Auto Pro`
3. Nom de la propriété : `palermeautopro.shop`
4. Secteur : Commerce et achats en ligne
5. Taille : Petite entreprise
6. Flux web → URL : `https://palermeautopro.shop`
7. Copier l'ID de mesure → coller ici

---

### Google Search Console — Code de vérification

Permet à Google de confirmer que vous êtes propriétaire du site.
Donne accès aux statistiques de recherche (mots-clés, impressions, clics).

**Où trouver le code :**
1. Aller sur [search.google.com/search-console](https://search.google.com/search-console)
2. Ajouter une propriété → URL de préfixe → `https://palermeautopro.shop`
3. Méthode de vérification → **Balise HTML**
4. Copier **uniquement la valeur** du champ `content`

**Exemple :** si Google affiche :
```html
<meta name="google-site-verification" content="abc123XYZ456def789">
```
→ Coller uniquement : `abc123XYZ456def789`

> Après avoir sauvegardé dans l'admin, cliquer sur **Vérifier** dans Search Console.

---

## 4. Exemple complet à copier-adapter

```
TITRE SEO :
PALERME AUTO PRO — Pièces auto d'occasion vérifiées

META DESCRIPTION :
Achetez vos pièces automobiles d'occasion vérifiées au meilleur prix. Large catalogue : moteurs, boîtes de vitesse, carrosserie. Commande en ligne, livraison rapide.

MOTS-CLÉS :
pièces auto occasion, pièces détachées, moteur occasion, boîte de vitesse occasion, carrosserie auto, acheter pièces auto pas cher

ROBOTS : index, follow

TITRE OG :
🔧 PALERME AUTO PRO — Pièces auto d'occasion

DESCRIPTION OG :
Large choix de pièces automobiles d'occasion contrôlées et garanties. Commandez facilement en ligne.

GA4 : G-XXXXXXXXXX (à remplacer par votre vrai ID)
SEARCH CONSOLE : (coller votre code après vérification)
```

---

## 5. Après avoir sauvegardé

1. **Vider le cache** pour que les nouvelles balises soient actives :
   ```bash
   php artisan config:cache && php artisan view:cache
   ```
   *(ou via le pipeline CI/CD en faisant un push)*

2. **Tester les balises OG :** [developers.facebook.com/tools/debug](https://developers.facebook.com/tools/debug) — coller l'URL et cliquer "Scrape Again"

3. **Tester l'aperçu Google :** [search.google.com/test/rich-results](https://search.google.com/test/rich-results)

4. **Tester les meta tags :** [metatags.io](https://metatags.io) — aperçu Google + Twitter + Facebook simultané

5. **Soumettre le sitemap** dans Search Console une fois vérifié :
   - URL du sitemap : `https://palermeautopro.shop/sitemap.xml` *(si généré)*
