# Guide de Compilation — CashCash Client Lourd

> Application **Electron + PHP built-in server** packagée en installateur natif.
> Ce guide couvre le démarrage en développement, la compilation macOS (`.dmg`) et Windows (`.exe`).

---

## 📁 Structure du projet client lourd

```
clients/client/
├── main.js                 # Point d'entrée Electron
├── php-server.js           # Serveur PHP built-in intégré
├── preload.js              # Script preload Electron (CSP)
├── splash.html             # Écran de chargement
├── error.html              # Page d'erreur
├── package.json            # Dépendances Node / config electron-builder
├── composer.json           # Dépendances PHP (TCPDF)
├── .gitignore              # Exclut node_modules/, vendor/, dist/
│
├── *.php                   # Fichiers PHP de l'application
├── assets/                 # CSS, images, icône
│   ├── style.css
│   └── icon.png
├── components/             # Composants PHP (header, nav)
├── migrations/             # Scripts SQL
└── vendor/                 # ⚠️ Non versionné — installé via Composer
```

---

## ✅ Prérequis

| Outil | Version minimale | Vérification |
|-------|-----------------|--------------|
| Node.js | 18+ | `node --version` |
| npm | 9+ | `npm --version` |
| PHP (XAMPP) | 7.4+ | `/Applications/XAMPP/xamppfiles/bin/php --version` |
| MySQL (XAMPP) | 5.7+ | XAMPP Control Panel |
| Composer | 2+ | `composer --version` |

### Installer Composer (si absent)

```bash
curl -sS https://getcomposer.org/installer | /Applications/XAMPP/xamppfiles/bin/php
sudo mv composer.phar /usr/local/bin/composer
```

---

## 🚀 Étape 1 — Cloner et aller dans le bon dossier

```bash
git clone https://github.com/benjamin-Dassonville/AP.git
cd "AP test 2/clients/client"
```

---

## 📦 Étape 2 — Installer les dépendances PHP (Composer)

> **TCPDF** est géré via Composer. Le dossier `vendor/` n'est pas dans le dépôt Git.
> Il faut le régénérer à chaque clone :

```bash
# Depuis clients/client/
composer install
```

✅ Cela crée `vendor/autoload.php` et installe `tecnickcom/tcpdf`.

---

## 📦 Étape 3 — Installer les dépendances Node

```bash
npm install
```

✅ Cela installe `electron` et `electron-builder`.

---

## 🗄️ Étape 4 — Initialiser la base de données

1. Démarrer **XAMPP** (Apache + MySQL)
2. Ouvrir **phpMyAdmin** : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Créer la base de données `cashcashs` (si elle n'existe pas)
4. Importer le fichier SQL :
   - Aller dans `cashcashs` → onglet **Importer**
   - Sélectionner `migrations/add_users_and_interventions.sql`
   - Cliquer **Exécuter**

> 💡 Ou via terminal XAMPP :
> ```bash
> /Applications/XAMPP/xamppfiles/bin/mysql -u root -p cashcashs < migrations/add_users_and_interventions.sql
> ```

### Comptes de test disponibles

| Rôle | Username | Password |
|------|----------|----------|
| Gestionnaire | gestionnaire | password123 |
| Technicien | technicien1 | password123 |
| Technicien | technicien2 | password123 |

---

## ▶️ Étape 5 — Lancer en mode développement

```bash
npm start
```

L'application Electron démarre, lance un serveur PHP built-in sur un port aléatoire (8000–9000), et affiche l'interface.

> 🛠️ Pour activer les DevTools automatiquement :
> ```bash
> NODE_ENV=development npm start
> ```

---

## 🏗️ Compilation

### macOS — Fichier `.dmg`

```bash
npm run build:mac
```

Fichier généré : `dist/CashCash-1.0.0.dmg`

---

### Windows — Installateur `.exe` (depuis macOS)

> ⚠️ La cross-compilation macOS → Windows peut nécessiter **Wine**.

**Option A — Build direct (si Wine est installé)**

```bash
brew install --cask wine-stable   # une seule fois
npm run build:win
```

Fichier généré : `dist/CashCash Setup 1.0.0.exe`

**Option B — Build dans Docker (recommandé)**

```bash
docker run --rm -ti \
  -v ${PWD}:/project \
  -v ~/.cache/electron:/root/.cache/electron \
  -v ~/.cache/electron-builder:/root/.cache/electron-builder \
  electronuserland/builder:wine \
  /bin/bash -c "cd /project && npm install && npm run build:win"
```

**Option C — Compiler directement sur Windows**

```powershell
# PowerShell sur Windows
cd C:\chemin\vers\ClientLourd
npm install
composer install
npm run build:win
```

---

### Linux — AppImage

```bash
npm run build:linux
```

Fichier généré : `dist/CashCash-1.0.0.AppImage`

---

## 📋 Ce qui est inclus dans l'installateur

| Contenu | Inclus |
|---------|--------|
| Interface Electron | ✅ |
| Fichiers PHP (pages, API) | ✅ (dépaquetés via `asarUnpack`) |
| Assets (CSS, icônes) | ✅ |
| Vendor PHP (TCPDF) | ✅ si `composer install` fait avant build |
| Script SQL de migration | ✅ |
| PHP runtime | ❌ — fourni par XAMPP |
| MySQL | ❌ — fourni par XAMPP |

> ⚠️ **L'utilisateur final doit avoir XAMPP installé** sur sa machine.
> - macOS : `/Applications/XAMPP/xamppfiles/bin/php`
> - Windows : `C:\xampp\php\php.exe`

---

## 🔑 Récapitulatif des commandes

```bash
# 1. Installation (une fois après git clone)
composer install          # dépendances PHP (TCPDF)
npm install               # dépendances Node (Electron)

# 2. Développement
npm start                 # Lance Electron en dev

# 3. Compilation
npm run build:mac         # → dist/*.dmg
npm run build:win         # → dist/*.exe
npm run build:linux       # → dist/*.AppImage
npm run build             # → tous les formats
```

---

## 🐛 Dépannage

### `vendor/autoload.php: No such file or directory`
→ `composer install` n'a pas été exécuté.  
```bash
composer install
```

### `Impossible de démarrer le serveur PHP`
→ XAMPP n'est pas installé ou PHP n'est pas au bon chemin.  
Vérifier : `/Applications/XAMPP/xamppfiles/bin/php --version`

### `Database connection failed`
→ MySQL XAMPP n'est pas démarré.  
Vérifier dans XAMPP Control Panel que MySQL est **Running**.

### L'icône n'apparaît pas dans l'installateur
→ `assets/icon.png` doit exister et faire au minimum **512×512 px**.

### Build Windows échoue sur macOS sans Wine
→ Utiliser l'**Option B (Docker)** ou compiler sur Windows directement.

---

## 🔗 Références

- [Documentation Electron Builder](https://www.electron.build/)
- [TCPDF sur Packagist](https://packagist.org/packages/tecnickcom/tcpdf)
- [XAMPP pour macOS](https://www.apachefriends.org/fr/index.html)
