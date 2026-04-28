# 📋 CashCash — Documentation Complète de la Refonte MVC

> **Version :** 2.0  
> **Date :** 28/04/2026  
> **Auteur :** Refonte automatisée  

---

## 📁 Structure Finale du Projet

```
client-new/
├── config.php                  # Autoloader PSR-4 + connexion PDO
├── main.js                     # Point d'entrée Electron
├── php-server.js               # Serveur PHP embarqué pour Electron
├── preload.js                  # Preload script Electron
├── splash.html                 # Écran de chargement Electron
├── package.json                # Config npm + Electron Builder
├── composer.json               # Dépendances PHP (TCPDF)
├── BDD.sql                     # Schéma complet de la base de données
├── LICENSE.txt
├── DOC_DEPLOIEMENT.md
│
├── public/                     # 🌐 DOCUMENT ROOT (accessible par le navigateur)
│   ├── assets/
│   │   └── style.css           # Feuille de style globale
│   ├── index.php               # Page d'accueil (sélection du rôle)
│   ├── login.php               # Formulaire de connexion
│   ├── logout.php              # Déconnexion
│   ├── dashboard_gestionnaire.php
│   ├── dashboard_technicien.php
│   ├── fiche_intervention.php  # CRUD complet des interventions
│   ├── statistiques.php        # Graphiques Chart.js
│   ├── add_equipment.php       # Association matériel ↔ contrat
│   ├── generate_xml.php        # Export XML par client
│   ├── generate_pdf.php        # PDF relance contrats
│   └── generer_pdf_intervention.php  # PDF fiche intervention
│
├── src/                        # 🔒 CODE MÉTIER (NON accessible par URL)
│   ├── Core/
│   │   ├── Database.php        # Singleton PDO
│   │   ├── AuthManager.php     # Gestion sessions & rôles
│   │   ├── auth.php            # Fonctions wrapper (rétro-compatibilité)
│   │   └── utils.php           # Fonctions utilitaires (XML, etc.)
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── InterventionController.php
│   │   ├── EquipmentController.php
│   │   └── StatsController.php
│   ├── Models/
│   │   └── Utilisateur.php
│   ├── Repositories/
│   │   ├── UtilisateurRepository.php
│   │   ├── ClientRepository.php
│   │   ├── InterventionRepository.php
│   │   ├── MaterielRepository.php
│   │   └── ContratRepository.php
│   └── Views/
│       ├── helpers.php          # Fonctions d'affichage partagées
│       └── components/
│           ├── header.php
│           ├── nav_gestionnaire.php
│           └── nav_technicien.php
│
├── migrations/
│   └── scripts/
│       └── create_users.php    # Script de création des utilisateurs de test
│
└── vendor/                     # Dépendances Composer (TCPDF)
```

---

## 🔄 Liste Complète des Modifications

### 1. Restructuration MVC (Architecture)

| Action | Fichier | Description |
|--------|---------|-------------|
| **CRÉÉ** | `public/` | Nouveau dossier Document Root pour isoler les fichiers accessibles |
| **DÉPLACÉ** | `*.php` → `public/*.php` | Toutes les pages accessibles déplacées dans `public/` |
| **DÉPLACÉ** | `assets/` → `public/assets/` | CSS et ressources statiques |
| **DÉPLACÉ** | `auth.php` → `src/Core/auth.php` | Logique d'authentification |
| **DÉPLACÉ** | `utils.php` → `src/Core/utils.php` | Fonctions utilitaires |
| **DÉPLACÉ** | `components/` → `src/Views/components/` | Fragments HTML réutilisables |
| **DÉPLACÉ** | `create_users.php` → `migrations/scripts/` | Script admin sécurisé |
| **CRÉÉ** | `src/Views/helpers.php` | Fonctions d'affichage partagées (badges) |

### 2. Corrections de Bugs

| Fichier | Bug | Correction |
|---------|-----|------------|
| `InterventionRepository.php` | `client_id` absent du SELECT | Ajout de `fi.client_id` dans `getRecent()` |
| `ClientRepository.php` | Méthode `getAll()` inexistante | Ajout de la méthode |
| `generate_pdf.php` | Chemin `vendor/autoload.php` cassé | Corrigé en `/../vendor/autoload.php` |
| `generer_pdf_intervention.php` | Même problème de chemin | Corrigé |
| `UtilisateurRepository.php` | Colonne `active` inexistante dans la BDD | Supprimée de la requête SQL |
| `InterventionController.php` | Même colonne `active` | Supprimée |
| `AuthManager.php` | Boucle de redirection infinie (ERR_TOO_MANY_REDIRECTS) | Ajout de `session_write_close()` et logout automatique pour rôles invalides |

### 3. Améliorations Fonctionnelles

| Fichier | Amélioration |
|---------|-------------|
| **Tous les dashboards** | Affichage du **N° Client** (ex: "N°12 - Entreprise X") |
| `fiche_intervention.php` | N° Client dans les listes déroulantes et les fiches détaillées |
| `generate_xml.php` | **Tableau de clients** avec **recherche en temps réel** au lieu d'un champ numérique |
| `add_equipment.php` | **Refonte complète** : stepper 3 étapes, grille de clients, cartes de contrats, checklist d'équipements |
| `index.php` | **Redirection automatique** si l'utilisateur est déjà connecté |
| `dashboard_gestionnaire.php` | Code DRY : extraction des fonctions badges dans `helpers.php` |
| `dashboard_technicien.php` | Idem |
| `index.php` | Copyright mis à jour (2026) |

### 4. Modifications Electron

| Fichier | Modification |
|---------|-------------|
| `main.js` | Document root changé vers `public/` |
| `package.json` | `asarUnpack` mis à jour pour inclure `public/**/*` et `src/**/*` |

---

## 🖥️ Comment Créer un Exécutable (.exe) avec Electron

### Prérequis

1. **Node.js** installé (v18+ recommandé) → [nodejs.org](https://nodejs.org)
2. **PHP** installé et accessible dans le PATH système
   - Sous Windows : ajouter `C:\xampp\php` au PATH
3. **npm** (inclus avec Node.js)

### Étape 1 : Installer les dépendances

```bash
cd C:\xampp\htdocs\AP2\client-new
npm install
```

### Étape 2 : Tester en mode développement

```bash
npm start
```

Cela lance le serveur PHP embarqué + la fenêtre Electron. Vérifiez que tout fonctionne.

### Étape 3 : Construire l'exécutable

```bash
npm run build
```

> **⚠️ IMPORTANT :** Pour que l'exe fonctionne sur un autre PC, **PHP doit être embarqué** dans le package. Voici comment :

### Étape 4 : Embarquer PHP dans l'exécutable

1. **Télécharger PHP portable** : [windows.php.net/download](https://windows.php.net/download/) (version Thread Safe, ZIP)

2. **Extraire** le ZIP dans un dossier `php/` à la racine du projet :
   ```
   client-new/
   ├── php/              ← PHP portable ici
   │   ├── php.exe
   │   ├── php.ini
   │   └── ext/
   ├── public/
   ├── src/
   └── ...
   ```

3. **Modifier `php-server.js`** pour utiliser le PHP embarqué :
   ```javascript
   // Ligne à modifier dans php-server.js
   const phpPath = path.join(app.getAppPath(), 'php', 'php.exe');
   ```

4. **Modifier `package.json`** pour inclure le dossier PHP :
   ```json
   "build": {
     "extraResources": [
       {
         "from": "php",
         "to": "php",
         "filter": ["**/*"]
       }
     ]
   }
   ```

5. **Rebuild** :
   ```bash
   npm run build
   ```

### Étape 5 : Résultat

L'exécutable sera généré dans :
```
client-new/dist/CashCash Setup x.x.x.exe
```

C'est un **installateur Windows** (NSIS) qui installe l'application comme un logiciel classique.

### Structure de la commande de build

| Commande | Description |
|----------|-------------|
| `npm start` | Lance en mode développement |
| `npm run build` | Construit l'exécutable Windows (.exe) |
| `npm run build -- --dir` | Construit sans installateur (dossier portable) |

---

## 🗄️ Base de Données

### Connexion
- **Hôte :** `localhost`
- **Base :** `cashcashs`
- **Utilisateur :** `root`
- **Mot de passe :** *(vide)*

> Paramètres configurables dans `config.php` → `src/Core/Database.php`

### Créer les utilisateurs de test

```bash
php migrations/scripts/create_users.php
```

| Username | Mot de passe | Rôle |
|----------|-------------|------|
| gestionnaire | password123 | Gestionnaire |
| technicien1 | password123 | Technicien |
| technicien2 | password123 | Technicien |

---

## 🏗️ Diagramme d'Architecture MVC

```
┌─────────────────────────────────────────────┐
│                 NAVIGATEUR                   │
│         (ou fenêtre Electron)                │
└──────────────────┬──────────────────────────┘
                   │ HTTP Request
                   ▼
┌─────────────────────────────────────────────┐
│              public/                         │
│  index.php │ login.php │ dashboard_*.php     │
│  ┌──────────────────────────────────────┐   │
│  │      Vue (HTML + CSS + JS)           │   │
│  └──────────────┬───────────────────────┘   │
└─────────────────┼───────────────────────────┘
                  │ require_once
                  ▼
┌─────────────────────────────────────────────┐
│              src/Controllers/                │
│  AuthController │ DashboardController        │
│  InterventionController │ EquipmentController│
│  StatsController                             │
│  ┌──────────────────────────────────────┐   │
│  │      Contrôleur (logique métier)     │   │
│  └──────────────┬───────────────────────┘   │
└─────────────────┼───────────────────────────┘
                  │ new Repository()
                  ▼
┌─────────────────────────────────────────────┐
│              src/Repositories/               │
│  ClientRepository │ InterventionRepository   │
│  MaterielRepository │ ContratRepository      │
│  UtilisateurRepository                       │
│  ┌──────────────────────────────────────┐   │
│  │      Modèle (accès base de données)  │   │
│  └──────────────┬───────────────────────┘   │
└─────────────────┼───────────────────────────┘
                  │ PDO
                  ▼
┌─────────────────────────────────────────────┐
│              MySQL (cashcashs)                │
│  Client │ Materiel │ Contrat_de_maintenance  │
│  Utilisateur │ Fiche_Intervention │ ...      │
└─────────────────────────────────────────────┘
```

---

## ✅ Checklist Avant Mise en Production

- [ ] Changer le mot de passe de la base de données dans `Database.php`
- [ ] Désactiver l'affichage des erreurs PHP (`display_errors = Off`)
- [ ] Supprimer `migrations/scripts/create_users.php` après utilisation
- [ ] Tester `npm start` pour valider le fonctionnement complet
- [ ] Construire l'exe avec `npm run build`
- [ ] Tester l'installateur sur un PC vierge
