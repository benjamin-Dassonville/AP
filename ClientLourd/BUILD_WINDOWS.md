# Build Windows pour CashCash Client Lourd

## 🎯 Objectif

Créer un installateur Windows (`.exe`) pour l'application CashCash, similaire au `.dmg` sur macOS.

## 📋 Prérequis

### Sur votre Mac (pour compiler)

1. **Node.js et npm** (déjà installé ✓)
2. **Dépendances du projet** (déjà installées ✓)

### Sur Windows (pour l'utilisateur final)

1. **Windows 10 ou supérieur** (64-bit)
2. **XAMPP pour Windows** installé avec :
   - PHP 7.4 ou supérieur
   - MySQL
   - Chemin par défaut : `C:\xampp\`

## 🚀 Comment compiler pour Windows depuis macOS

### Étape 1 : Préparer l'environnement

```bash
cd "/Applications/XAMPP/xamppfiles/htdocs/AP test 2/ClientLourd"
```

### Étape 2 : Lancer le build Windows

```bash
npm run build:win
```

Cette commande va :
- Créer un installateur Windows (`.exe`) dans le dossier `dist/`
- Compiler pour architecture x64 (Windows 64-bit)
- Inclure tous les fichiers PHP, assets, et dépendances

### Étape 3 : Récupérer l'installateur

Le fichier sera créé dans :
```
dist/CashCash Setup 1.0.0.exe
```

## 📦 Ce qui est inclus dans l'installateur

- ✅ Application Electron avec interface graphique
- ✅ Tous les fichiers PHP (application web)
- ✅ Base de données SQL
- ✅ Configuration et assets
- ✅ Raccourcis Bureau et Menu Démarrer

## ❌ Ce qui N'EST PAS inclus (requis sur Windows)

- ❌ PHP (fourni par XAMPP)
- ❌ MySQL (fourni par XAMPP)

L'utilisateur Windows **doit avoir XAMPP installé** avant d'utiliser l'application.

## 🔧 Installation sur Windows

### Pour l'utilisateur final :

1. **Installer XAMPP** (si pas déjà fait)
   - Télécharger depuis https://www.apachefriends.org/
   - Installer dans `C:\xampp\` (chemin par défaut)
   - S'assurer que MySQL fonctionne

2. **Lancer l'installateur CashCash**
   - Double-cliquer sur `CashCash Setup 1.0.0.exe`
   - Suivre l'assistant d'installation
   - Choisir le dossier d'installation (par défaut : `C:\Program Files\CashCash\`)
   - Créer les raccourcis

3. **Lancer l'application**
   - Double-cliquer sur l'icône CashCash sur le Bureau
   - OU : Menu Démarrer > CashCash

## ⚠️ Limitations de la cross-compilation

**Important :** Compiler pour Windows depuis macOS peut parfois rencontrer des limitations :

- Wine peut être nécessaire pour certaines fonctionnalités
- L'installateur NSIS peut ne pas se créer correctement
- Si le build échoue, voir les solutions alternatives ci-dessous

## 🆘 Solutions alternatives si le build échoue

### Option 1 : Utiliser Docker

```bash
docker run --rm -ti \
  --env-file <(env | grep -iE 'DEBUG|NODE_|ELECTRON_|YARN_|NPM_|CI|CIRCLE|TRAVIS_TAG|TRAVIS|TRAVIS_REPO_|TRAVIS_BUILD_|TRAVIS_BRANCH|TRAVIS_PULL_REQUEST_|APPVEYOR_|CSC_|GH_|GITHUB_|BT_|AWS_|STRIP|BUILD_') \
  --env ELECTRON_CACHE="/root/.cache/electron" \
  --env ELECTRON_BUILDER_CACHE="/root/.cache/electron-builder" \
  -v ${PWD}:/project \
  -v ~/.cache/electron:/root/.cache/electron \
  -v ~/.cache/electron-builder:/root/.cache/electron-builder \
  electronuserland/builder:wine \
  /bin/bash -c "cd /project && npm run build:win"
```

### Option 2 : Compiler sur Windows directement

1. Copier le dossier du projet sur une machine Windows
2. Installer Node.js sur Windows
3. Ouvrir PowerShell et exécuter :
```bash
cd "C:\chemin\vers\ClientLourd"
npm install
npm run build:win
```

### Option 3 : Utiliser GitHub Actions (CI/CD)

Créer un workflow automatique qui compile sur Windows dans le cloud (recommandé pour la production).

## 📝 Notes importantes

### Configuration MySQL

L'application cherche MySQL sur :
- **Mac** : `/Applications/XAMPP/xamppfiles/bin/mysql`
- **Windows** : `C:\xampp\mysql\bin\mysql.exe`

Assurez-vous que ces chemins sont corrects dans `config.php`.

### Ports utilisés

L'application utilise un port aléatoire entre 8000-9000 pour éviter les conflits.

### Première utilisation

Au premier lancement sur Windows, l'application :
1. Démarre un serveur PHP local
2. Se connecte à MySQL (XAMPP)
3. Charge l'interface web dans Electron

## 🐛 Dépannage

### "Impossible de démarrer le serveur PHP"

- Vérifier que XAMPP est installé
- Vérifier que PHP est dans `C:\xampp\php\php.exe`
- Redémarrer XAMPP

### "Impossible de se connecter à la base de données"

- Démarrer MySQL dans XAMPP Control Panel
- Vérifier les credentials dans `config.php`
- Importer `BDD.sql` dans phpMyAdmin

### "Application déjà en cours d'exécution"

- Fermer toutes les instances de CashCash
- Vérifier dans le Gestionnaire des tâches
- Redémarrer l'application

## ✅ Checklist de distribution

Avant de distribuer l'installateur Windows :

- [ ] Tester l'installateur sur Windows 10 propre
- [ ] Vérifier que MySQL démarre correctement
- [ ] Tester toutes les fonctionnalités principales
- [ ] Vérifier la désinstallation propre
- [ ] Documenter les prérequis (XAMPP)
- [ ] Fournir le fichier `BDD.sql` séparément
- [ ] Créer un guide d'installation pour l'utilisateur final

## 📞 Support

Pour toute question sur le build ou le déploiement, se référer à :
- `README_DESKTOP.md` - Documentation générale
- `DEPLOYMENT.md` - Guide de déploiement
