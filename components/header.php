<?php
/**
 * components/header.php
 * En-tête réutilisable pour les pages dashboard
 */

// Assurez-vous que l'authentification est vérifiée avant d'inclure ce fichier
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_name = getCurrentUserName();
$user_role = getCurrentUserRole();
$role_label = ($user_role === 'gestionnaire') ? 'Gestionnaire' : 'Technicien';
?>
<header class="dashboard-header">
    <div class="container">
        <div class="dashboard-logo">
            <h1>CashCash</h1>
        </div>
        <div class="dashboard-user">
            <div class="dashboard-user-info">
                <div class="dashboard-user-name"><?= htmlspecialchars($user_name) ?></div>
                <div class="dashboard-user-role"><?= htmlspecialchars($role_label) ?></div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline">Déconnexion</a>
        </div>
    </div>
</header>
