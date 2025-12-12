<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CashCash - Sélection du rôle</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="role-selection-page">
    <div class="role-selection-container">
        <div class="app-header">
            <h1>CashCash</h1>
            <p class="subtitle">Gestion du parc matériel</p>
        </div>
        
        <?php if (isset($_GET['logged_out'])): ?>
            <div class="alert alert-success">
                ✓ Vous avez été déconnecté avec succès.
            </div>
        <?php endif; ?>
        
        <div class="role-cards">
            <a href="login.php?role=gestionnaire" class="role-card role-card-gestionnaire">
                <div class="role-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h2>Gestionnaire</h2>
                <p>Accès complet à la gestion du parc, interventions et rapports</p>
                <span class="role-card-arrow">→</span>
            </a>
            
            <a href="login.php?role=technicien" class="role-card role-card-technicien">
                <div class="role-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
                <h2>Technicien</h2>
                <p>Gestion des interventions techniques et suivi des équipements</p>
                <span class="role-card-arrow">→</span>
            </a>
        </div>
        
        <div class="app-footer">
            <p>© 2025 CashCash - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
