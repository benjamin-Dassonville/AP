<?php

require_once 'auth.php';
requireRole('gestionnaire');

try {
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
    
    $stmt = $pdo->query("
        SELECT 
            u.id,
            CONCAT(u.prenom, ' ', u.nom) as technicien_nom,
            COUNT(fi.id) as nb_interventions
        FROM Utilisateur u
        LEFT JOIN Fiche_Intervention fi ON u.id = fi.technicien_id
        WHERE u.role = 'technicien' AND u.active = TRUE
        GROUP BY u.id, u.prenom, u.nom
        ORDER BY nb_interventions DESC
    ");
    $stats_by_technicien = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
    $statut_counts = [
        'en_attente' => 0,
        'en_cours' => 0,
        'terminee' => 0,
        'annulee' => 0
    ];
    $stats_by_technicien = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="dashboard-page">
    <?php include 'components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>📊 Statistiques</h2>
                <p class="dashboard-subtitle">Vue d'ensemble des performances et activités</p>
            </div>
            
            <?php include 'components/nav_gestionnaire.php'; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <div class="chart-container">
                    <h3 class="chart-title">Interventions par technicien</h3>
                    <div style="padding: 2rem; background: white; border-radius: var(--border-radius);">
                        <canvas id="techniciensChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-container">
                    <h3 class="chart-title">Répartition par statut</h3>
                    <div style="padding: 2rem; background: white; border-radius: var(--border-radius);">
                        <canvas id="statutsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <div class="card" style="padding: 2rem;">
                    <h3 style="margin-top: 0;">📈 Résumé</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-secondary); border-radius: 8px;">
                            <span><strong>Total des interventions :</strong></span>
                            <span class="badge badge-info"><?= array_sum($statut_counts) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-secondary); border-radius: 8px;">
                            <span><strong>Techniciens actifs :</strong></span>
                            <span class="badge badge-success"><?= count($stats_by_technicien) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-secondary); border-radius: 8px;">
                            <span><strong>Taux de complétion :</strong></span>
                            <span class="badge badge-success">
                                <?= array_sum($statut_counts) > 0 ? round(($statut_counts['terminee'] / array_sum($statut_counts)) * 100, 1) : 0 ?>%
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card" style="padding: 2rem;">
                    <h3 style="margin-top: 0;">🏆 Top Technicien</h3>
                    <?php if (!empty($stats_by_technicien)): ?>
                        <div style="text-align: center; padding: 1.5rem;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">👤</div>
                            <h2 style="margin: 0; color: var(--primary-color);">
                                <?= htmlspecialchars($stats_by_technicien[0]['technicien_nom']) ?>
                            </h2>
                            <p style="color: var(--text-secondary); margin: 0.5rem 0 0 0;">
                                <?= $stats_by_technicien[0]['nb_interventions'] ?> intervention<?= $stats_by_technicien[0]['nb_interventions'] > 1 ? 's' : '' ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                            Aucune donnée disponible
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const techCtx = document.getElementById('techniciensChart');
        const techData = {
            labels: [
                <?php foreach ($stats_by_technicien as $tech): ?>
                    '<?= addslashes($tech['technicien_nom']) ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Nombre d\'interventions',
                data: [
                    <?php foreach ($stats_by_technicien as $tech): ?>
                        <?= $tech['nb_interventions'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(0, 102, 204, 0.8)',
                borderColor: 'rgba(0, 102, 204, 1)',
                borderWidth: 2
            }]
        };
        
        const techConfig = {
            type: 'bar',
            data: techData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        };
        
        new Chart(techCtx, techConfig);
        
        const statutCtx = document.getElementById('statutsChart');
        const statutData = {
            labels: ['En attente', 'En cours', 'Terminées', 'Annulées'],
            datasets: [{
                data: [
                    <?= $statut_counts['en_attente'] ?>,
                    <?= $statut_counts['en_cours'] ?>,
                    <?= $statut_counts['terminee'] ?>,
                    <?= $statut_counts['annulee'] ?>
                ],
                backgroundColor: [
                    'rgba(243, 156, 18, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(39, 174, 96, 0.8)',
                    'rgba(149, 165, 166, 0.8)'
                ],
                borderColor: [
                    'rgba(243, 156, 18, 1)',
                    'rgba(52, 152, 219, 1)',
                    'rgba(39, 174, 96, 1)',
                    'rgba(149, 165, 166, 1)'
                ],
                borderWidth: 2
            }]
        };
        
        const statutConfig = {
            type: 'pie',
            data: statutData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        };
        
        new Chart(statutCtx, statutConfig);
    </script>
</body>
</html>
