<?php

require_once 'auth.php';
requireRole('gestionnaire');

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Client");
    $total_clients = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Fiche_Intervention");
    $total_interventions = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Fiche_Intervention WHERE statut = 'en_attente'");
    $interventions_en_attente = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM Materiel m
        INNER JOIN Contrat_de_maintenance c ON m.Numero_de_Contrat = c.Numero_de_Contrat
        WHERE c.Date_echeance >= CURDATE()
    ");
    $equipements_sous_contrat = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("
        SELECT 
            fi.id,
            fi.numero_intervention,
            fi.titre,
            fi.statut,
            fi.priorite,
            fi.date_intervention,
            fi.alerte_active,
            c.Raison_Sociale as client_nom,
            CONCAT(u.prenom, ' ', u.nom) as technicien_nom
        FROM Fiche_Intervention fi
        LEFT JOIN Client c ON fi.client_id = c.Numero_Client
        LEFT JOIN Utilisateur u ON fi.technicien_id = u.id
        ORDER BY fi.date_creation DESC
        LIMIT 10
    ");
    $interventions_recentes = $stmt->fetchAll();
    
    $stmt = $pdo->query("
        SELECT statut, COUNT(*) as count
        FROM Fiche_Intervention
        GROUP BY statut
    ");
    $stats_by_status = $stmt->fetchAll();
    
    $statut_counts = [
        'en_attente' => 0,
        'en_cours' => 0,
        'terminee' => 0,
        'annulee' => 0
    ];
    foreach ($stats_by_status as $stat) {
        $statut_counts[$stat['statut']] = $stat['count'];
    }
    
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des données: " . $e->getMessage());
    $total_clients = 0;
    $total_interventions = 0;
    $interventions_en_attente = 0;
    $equipements_sous_contrat = 0;
    $interventions_recentes = [];
    $statut_counts = [
        'en_attente' => 0,
        'en_cours' => 0,
        'terminee' => 0,
        'annulee' => 0
    ];
}

function getStatutBadge($statut) {
    $badges = [
        'en_attente' => 'badge-warning',
        'en_cours' => 'badge-info',
        'terminee' => 'badge-success',
        'annulee' => 'badge-secondary'
    ];
    return $badges[$statut] ?? 'badge-secondary';
}

function getPrioriteBadge($priorite) {
    $badges = [
        'basse' => 'badge-secondary',
        'normale' => 'badge-info',
        'haute' => 'badge-warning',
        'urgente' => 'badge-danger'
    ];
    return $badges[$priorite] ?? 'badge-secondary';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gestionnaire - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-page">
    <?php include 'components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>Tableau de bord Gestionnaire</h2>
                <p class="dashboard-subtitle">Vue d'ensemble des activités et statistiques</p>
            </div>
            
            <?php include 'components/nav_gestionnaire.php'; ?>
            
            <!-- Statistiques -->
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Clients</span>
                        <div class="card-icon" style="background: rgba(0, 102, 204, 0.1); color: var(--primary-color);">
                            👥
                        </div>
                    </div>
                    <div class="card-value"><?= $total_clients ?></div>
                    <div class="card-label">Clients totaux</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Interventions</span>
                        <div class="card-icon" style="background: rgba(39, 174, 96, 0.1); color: var(--secondary-color);">
                            📋
                        </div>
                    </div>
                    <div class="card-value"><?= $total_interventions ?></div>
                    <div class="card-label">Interventions totales</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">En attente</span>
                        <div class="card-icon" style="background: rgba(243, 156, 18, 0.1); color: var(--warning-color);">
                            ⏱️
                        </div>
                    </div>
                    <div class="card-value"><?= $interventions_en_attente ?></div>
                    <div class="card-label">Interventions en attente</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Contrats</span>
                        <div class="card-icon" style="background: rgba(52, 152, 219, 0.1); color: var(--info-color);">
                            ✅
                        </div>
                    </div>
                    <div class="card-value"><?= $equipements_sous_contrat ?></div>
                    <div class="card-label">Équipements sous contrat</div>
                </div>
            </div>
            
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Interventions récentes</h3>
                    <div class="table-actions">
                        <a href="fiche_intervention.php?action=create" class="btn btn-primary btn-sm">
                            + Nouvelle intervention
                        </a>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Titre</th>
                                <th>Client</th>
                                <th>Technicien</th>
                                <th>Date</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($interventions_recentes)): ?>
                                <tr>
                                    <td colspan="8" class="text-center" style="padding: 2rem; color: var(--text-secondary);">
                                        Aucune intervention enregistrée
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($interventions_recentes as $intervention): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($intervention['numero_intervention']) ?>
                                            <?php if ($intervention['alerte_active']): ?>
                                                <span style="color: var(--danger-color);" title="Alerte active">⚠️</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($intervention['titre']) ?></td>
                                        <td><?= htmlspecialchars($intervention['client_nom'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($intervention['technicien_nom'] ?? 'Non assigné') ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($intervention['date_intervention'])) ?></td>
                                        <td>
                                            <span class="badge <?= getPrioriteBadge($intervention['priorite']) ?>">
                                                <?= ucfirst($intervention['priorite']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= getStatutBadge($intervention['statut']) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $intervention['statut'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="fiche_intervention.php?id=<?= $intervention['id'] ?>" class="btn btn-sm btn-outline">
                                                Voir
                                            </a>
                                            <a href="generer_pdf_intervention.php?id=<?= $intervention['id'] ?>" 
                                               class="btn btn-sm btn-outline" 
                                               title="Télécharger PDF"
                                               target="_blank">
                                                📄
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="cards-grid" style="margin-top: 2rem;">
                <a href="generate_xml.php" class="card" style="text-decoration: none; cursor: pointer;">
                    <div class="card-header">
                        <span class="card-title">Générateur XML</span>
                        <span style="font-size: 2rem;">📄</span>
                    </div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Générer un fichier XML des équipements par client
                    </p>
                </a>
                
                <a href="add_equipment.php" class="card" style="text-decoration: none; cursor: pointer;">
                    <div class="card-header">
                        <span class="card-title">Ajout matériel</span>
                        <span style="font-size: 2rem;">➕</span>
                    </div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Ajouter du matériel à un contrat existant
                    </p>
                </a>
                
                <a href="generate_pdf.php" class="card" style="text-decoration: none; cursor: pointer;">
                    <div class="card-header">
                        <span class="card-title">PDF Relance</span>
                        <span style="font-size: 2rem;">📨</span>
                    </div>
                    <p style="color: var(--text-secondary); margin: 0;">
                        Générer un PDF de relance pour contrats à échéance
                    </p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
