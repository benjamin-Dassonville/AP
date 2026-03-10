# Architecture de l'Application CashCash

Ce document décrit la structure de l'application CashCash Multi-Rôle, qui a été refactorisée pour séparer le code source métier des points d'entrée accessibles publiquement.

## Structure des Dossiers

L'application suit une structure modulaire basique pour isoler la logique métier et sécuriser l'accès aux fichiers sensibles.

```text
/AP
├── public/                 # Document root (accessible via navigateur)
│   ├── index.php           # Page d'accueil / Sélection des rôles
│   ├── login.php           # Page de connexion
│   ├── dashboard_*.php     # Tableaux de bord spécifiques aux rôles
│   ├── fiche_intervention.php
│   ├── add_equipment.php
│   ├── create_users.php
│   ├── statistiques.php
│   ├── generate_*.php      # Générateurs de PDF / XML rendus au client
│   └── assets/             # Fichiers statiques (CSS, images)
│
├── src/                    # Code source de l'application (NON ACCESSIBLE par le serveur web)
│   ├── config.php          # Identifiants de base de données
│   ├── auth.php            # Logique d'authentification et gestion de session
│   ├── utils.php           # Fonctions utilitaires partagées
│   └── components/         # Morceaux d'interface utilisateur (header, navigation)
│
├── database/               # Sauvegardes et scripts de base de données
│   ├── cashcashs.sql       # Structure complète de la base de données
│   └── migrations/         # Scripts d'évolution de la base de données
│
├── clients/                # Clients externes (C#, Java, etc.)
│   ├── ClientLourd/
│   └── ClientWindows/
│
├── docs/                   # Documentation du projet
│   ├── DEPLOYMENT.md       # Guide d'installation et déploiement
│   └── ARCHITECTURE.md     # Le présent document
│
└── vendor/                 # Dépendances externes gérées par Composer (ex: TCPDF)
```

## Principe de Sécurité (Séparation Public / Privé)

1. **Serveur Web Virtuel (`Document Root`)** : Pour une sécurité maximale en production, la configuration du serveur web (Apache/Nginx) doit pointer directement vers le dossier `public/`.
   *Ainsi, les fichiers comme `config.php` (qui contiennent les mots de passe de la base de données) ne peuvent jamais être lus directement.*
2. **Inclusions Relatives** : Les scripts de `public/` accèdent aux données métiers en incluant les fichiers du dossier parent (`../src/`).

## Dépendances

- **TCPDF** : Utilisé pour générer des fichiers PDF (`vendor/tcpdf/`).
- **PHP Data Objects (PDO)** : Utilisé dans `src/config.php` pour la connexion sécurisée à MariaDB/MySQL.

## Extension

Pour ajouter de nouvelles fonctionnalités :
- Placez la **logique métier** et les fonctions partagées dans le dossier `src/`.
- Placez les **pages web** et les actions de formulaire (contrôleurs frontaux) dans le dossier `public/`.
- Mettez à jour les liens de navigation dans `src/components/`.
