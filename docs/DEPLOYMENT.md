# Guide de déploiement - CashCash Multi-Role Dashboard

## Étape 1: Migration de la base de données

Ouvrez votre terminal MySQL ou phpMyAdmin et exécutez :

```bash
# Via terminal
mysql -u root -p CashCash < /xampp/htdocs/AP/database/migrations/add_users_and_interventions.sql
```

Ou via phpMyAdmin :

1. Ouvrez phpMyAdmin
2. Sélectionnez la base de données `CashCash`
3. Allez dans l'onglet "SQL"
4. Copiez-collez le contenu de `database/migrations/add_users_and_interventions.sql` (ou importez `database/cashcashs.sql`)
5. Cliquez sur "Exécuter"

## Étape 2: Vérification

Vérifiez que les tables ont été créées :

```sql
SHOW TABLES LIKE 'Utilisateur';
SHOW TABLES LIKE 'Fiche_Intervention';

-- Vérifier les utilisateurs de test
SELECT username, role FROM Utilisateur;
```

Vous devriez voir :

- gestionnaire (gestionnaire)
- technicien1 (technicien)
- technicien2 (technicien)

## Étape 3: Accéder à l'application

1. Assurez-vous que XAMPP est démarré
2. Ouvrez votre navigateur
3. Allez à : `http://localhost/AP/public/`
4. Vous verrez la page de sélection de rôle

## Étape 4: Test de connexion

### Test Gestionnaire

- Cliquez sur "Gestionnaire"
- Username: `gestionnaire`
- Password: `password123`
- Vous accédez au dashboard complet avec statistiques

### Test Technicien

- Cliquez sur "Technicien"
- Username: `technicien1`
- Password: `password123`
- Vous accédez au dashboard technicien avec vos interventions

## Étape 5: Créer une intervention de test

En tant que gestionnaire :

1. Cliquez sur "Interventions" dans la navigation
2. Cliquez sur "+ Nouvelle intervention"
3. Remplissez le formulaire
4. Cochez "Activer une alerte" et ajoutez un message
5. Assignez-la à technicien1
6. Enregistrez

Connectez-vous ensuite en tant que technicien1 pour voir l'intervention !

## Comptes disponibles

| Rôle         | Username     | Password    |
| ------------ | ------------ | ----------- |
| Gestionnaire | gestionnaire | password123 |
| Technicien   | technicien1  | password123 |
| Technicien   | technicien2  | password123 |

## En cas de problème

### Erreur de connexion à la base de données

- Vérifiez que la base de données `CashCash` existe
- Vérifiez les credentials dans `src/config.php`

### Page blanche ou erreur PHP

- Vérifiez les logs d'erreur PHP
- Assurez-vous que toutes les tables sont créées
- Vérifiez les permissions des fichiers

### Les styles ne s'affichent pas

- Videz le cache du navigateur (Ctrl+Shift+R)
- Vérifiez que `assets/style.css` existe

## C'est prêt ! 🎉

Votre application multi-rôle est maintenant fonctionnelle avec :

- ✅ Authentification sécurisée
- ✅ Dashboards personnalisés
- ✅ Gestion des interventions
- ✅ Système d'alertes
- ✅ Design moderne et responsive
