# AP Test - Application Client Lourd

Application de bureau native pour la gestion des interventions IT, basée sur Electron et PHP.

## 🚀 Caractéristiques

- ✅ Application de bureau native (Windows, macOS, Linux)
- ✅ Serveur PHP intégré (démarrage automatique)
- ✅ Interface native avec menus système
- ✅ Pas besoin de navigateur web
- ✅ Icône dans la barre des tâches
- ✅ Raccourcis clavier

## 📋 Prérequis

### Pour le développement
- Node.js (version 16 ou supérieure)
- npm ou yarn
- PHP (XAMPP recommandé)

### Pour l'utilisation
- PHP installé sur le système OU utiliser la version packagée avec PHP embarqué

## 🛠️ Installation

```bash
# Installer les dépendances Node.js
npm install
```

## 🎯 Utilisation

### Mode développement

```bash
# Démarrer l'application en mode développement
npm start
```

L'application va :
1. Démarrer automatiquement un serveur PHP local
2. Ouvrir une fenêtre native
3. Charger l'application

### Build de production

```bash
# Build pour toutes les plateformes
npm run build

# Build pour Windows uniquement
npm run build:win

# Build pour macOS uniquement
npm run build:mac

# Build pour Linux uniquement
npm run build:linux
```

Les fichiers de build seront dans le dossier `dist/`.

## 📁 Structure

```
ClientLourd/
├── main.js              # Point d'entrée Electron
├── php-server.js        # Gestionnaire du serveur PHP
├── preload.js           # Script de préchargement sécurisé
├── package.json         # Configuration Node.js
├── *.php                # Fichiers PHP de l'application
├── assets/              # Ressources (CSS, JS, images)
├── components/          # Composants réutilisables
└── api/                 # Endpoints API
```

## ⌨️ Raccourcis clavier

- **Ctrl+R** / **Cmd+R** : Actualiser l'application
- **Ctrl+Q** / **Cmd+Q** : Quitter l'application
- **F11** : Basculer en plein écran
- **F12** : Ouvrir les outils de développement
- **Ctrl+Plus/Minus** : Zoom avant/arrière
- **Ctrl+0** : Réinitialiser le zoom

## 🔧 Configuration

### Modifier le port PHP par défaut

Éditez `php-server.js` :
```javascript
const [freePort] = await findFreePort(8000, 9000); // Modifier la plage
```

### Modifier la taille de la fenêtre

Éditez `main.js` :
```javascript
mainWindow = new BrowserWindow({
  width: 1400,  // Modifier la largeur
  height: 900,  // Modifier la hauteur
  // ...
});
```

## 🐛 Dépannage

### Le serveur PHP ne démarre pas

1. Vérifiez que PHP est installé :
   ```bash
   php --version
   ```

2. Sur macOS avec XAMPP, vérifiez le chemin dans `php-server.js` :
   ```javascript
   return '/Applications/XAMPP/xamppfiles/bin/php';
   ```

3. Sur Windows avec XAMPP, vérifiez :
   ```javascript
   return 'C:\\xampp\\php\\php.exe';
   ```

### L'application ne se lance pas

1. Vérifiez les dépendances :
   ```bash
   npm install
   ```

2. Vérifiez les logs dans la console

3. Essayez de supprimer `node_modules` et réinstaller :
   ```bash
   rm -rf node_modules
   npm install
   ```

### Erreur lors du build

1. Vérifiez que `electron-builder` est installé :
   ```bash
   npm install electron-builder --save-dev
   ```

2. Sur macOS, vous pourriez avoir besoin de :
   ```bash
   export NODE_OPTIONS=--max-old-space-size=4096
   npm run build
   ```

## 📦 Distribution

Après le build, vous obtiendrez :

- **Windows** : Un installeur `.exe` dans `dist/`
- **macOS** : Une image disque `.dmg` dans `dist/`
- **Linux** : Un fichier `.AppImage` dans `dist/`

Ces fichiers peuvent être distribués aux utilisateurs finaux.

## 🔒 Sécurité

- Le serveur PHP tourne uniquement en local (localhost)
- Pas d'exposition sur le réseau
- Context isolation activé dans Electron
- Node integration désactivé pour plus de sécurité

## 📝 Licence

ISC

## 👨‍💻 Auteur

AP Test

---

**Note** : Cette application utilise Electron pour créer une interface native et embarque votre application PHP existante avec un serveur built-in PHP.
