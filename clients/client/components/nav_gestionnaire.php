<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="nav-tabs">
    <a href="dashboard_gestionnaire.php" class="nav-tab <?= ($current_page === 'dashboard_gestionnaire.php') ? 'active' : '' ?>">
        Tableau de bord
    </a>
    <a href="statistiques.php" class="nav-tab <?= ($current_page === 'statistiques.php') ? 'active' : '' ?>">
        Statistiques
    </a>
    <a href="fiche_intervention.php" class="nav-tab <?= ($current_page === 'fiche_intervention.php') ? 'active' : '' ?>">
        Interventions
    </a>
    <a href="generate_xml.php" class="nav-tab <?= ($current_page === 'generate_xml.php') ? 'active' : '' ?>">
        Générateur XML
    </a>
    <a href="add_equipment.php" class="nav-tab <?= ($current_page === 'add_equipment.php') ? 'active' : '' ?>">
        Ajout matériel
    </a>
    <a href="generate_pdf.php" class="nav-tab <?= ($current_page === 'generate_pdf.php') ? 'active' : '' ?>">
        PDF relance
    </a>
</nav>
