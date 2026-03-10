<?php
/**
 * components/nav_technicien.php
 * Navigation pour le technicien
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="nav-tabs">
    <a href="dashboard_technicien.php" class="nav-tab <?= ($current_page === 'dashboard_technicien.php') ? 'active' : '' ?>">
        Mes interventions
    </a>
    <a href="fiche_intervention.php" class="nav-tab <?= ($current_page === 'fiche_intervention.php') ? 'active' : '' ?>">
        Fiches d'intervention
    </a>
</nav>
