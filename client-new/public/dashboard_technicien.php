<?php
/**
 * dashboard_technicien.php
 * Dashboard du technicien
 */

require_once '../src/Core/auth.php';
require_once '../src/Views/helpers.php';

$user_id = $_SESSION['user_id'];

$controller = new DashboardController();
$data = $controller->getTechnicienData($user_id);
extract($data);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Technicien - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-page">
    <?php include '../src/Views/components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>Mes interventions</h2>
                <p class="dashboard-subtitle">Vue d'ensemble de vos interventions assignées</p>
            </div>
            
            <?php include '../src/Views/components/nav_technicien.php'; ?>
            
            <!-- Statistiques -->
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Total</span>
                        <div class="card-icon" style="background: rgba(0, 102, 204, 0.1); color: var(--primary-color);">
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
                    <div class="card-label">À planifier</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">En cours</span>
                        <div class="card-icon" style="background: rgba(52, 152, 219, 0.1); color: var(--info-color);">
                            🔧
                        </div>
                    </div>
                    <div class="card-value"><?= $interventions_en_cours ?></div>
                    <div class="card-label">En cours</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Terminées</span>
                        <div class="card-icon" style="background: rgba(39, 174, 96, 0.1); color: var(--secondary-color);">
                            ✅
                        </div>
                    </div>
                    <div class="card-value"><?= $interventions_terminees ?></div>
                    <div class="card-label">Terminées</div>
                </div>
            </div>
            
            <!-- Liste des interventions -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Interventions assignées</h3>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($mes_interventions)): ?>
                        <div style="padding: 3rem; text-align: center;">
                            <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 1rem;">
                                Aucune intervention assignée pour le moment
                            </p>
                            <p style="color: var(--text-secondary);">
                                Contactez votre gestionnaire pour plus d'informations.
                            </p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($mes_interventions as $intervention): ?>
                            <div class="card" style="margin-bottom: 1.5rem;">
                                <div class="card-header">
                                    <div>
                                        <h4 style="margin: 0; color: var(--text-primary);">
                                            <?= htmlspecialchars($intervention['numero_intervention']) ?> - 
                                            <?= htmlspecialchars($intervention['titre']) ?>
                                        </h4>
                                        <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                            <span class="badge <?= getStatutBadge($intervention['statut']) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $intervention['statut'])) ?>
                                            </span>
                                            <span class="badge <?= getPrioriteBadge($intervention['priorite']) ?>">
                                                Priorité: <?= ucfirst($intervention['priorite']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="fiche_intervention.php?action=view&id=<?= $intervention['id'] ?>" class="btn btn-primary btn-sm">
                                            Détails
                                        </a>
                                        <a href="generer_pdf_intervention.php?id=<?= $intervention['id'] ?>" 
                                           class="btn btn-sm btn-outline" 
                                           title="Télécharger la fiche d'intervention en PDF"
                                           target="_blank">
                                            📄 PDF
                                        </a>
                                    </div>
                                </div>
                                
                                <div style="margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                                    <div>
                                        <p style="margin: 0.5rem 0; color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;">
                                            📅 Date d'intervention
                                        </p>
                                        <p style="margin: 0; color: var(--text-primary);">
                                            <?= date('d/m/Y à H:i', strtotime($intervention['date_intervention'])) ?>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <p style="margin: 0.5rem 0; color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;">
                                            🏢 Client
                                        </p>
                                        <p style="margin: 0; color: var(--text-primary);">
                                            <?= $intervention['client_id'] ? htmlspecialchars('N°' . $intervention['client_id'] . ' - ' . $intervention['client_nom']) : 'N/A' ?>
                                        </p>
                                        <?php if ($intervention['client_telephone']): ?>
                                            <p style="margin: 0.25rem 0 0 0; color: var(--text-secondary); font-size: 0.85rem;">
                                                📞 <?= htmlspecialchars($intervention['client_telephone']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($intervention['equipement_serie']): ?>
                                        <div>
                                            <p style="margin: 0.5rem 0; color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;">
                                                🔧 Équipement
                                            </p>
                                            <p style="margin: 0; color: var(--text-primary);">
                                                <?= htmlspecialchars($intervention['equipement_type'] ?? 'N/A') ?>
                                            </p>
                                            <p style="margin: 0.25rem 0 0 0; color: var(--text-secondary); font-size: 0.85rem;">
                                                SN: <?= htmlspecialchars($intervention['equipement_serie']) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($intervention['description']): ?>
                                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                        <p style="margin: 0.5rem 0; color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;">
                                            📝 Description
                                        </p>
                                        <p style="margin: 0; color: var(--text-primary);">
                                            <?= nl2br(htmlspecialchars($intervention['description'])) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($intervention['alerte_active'] && $intervention['alerte_message']): ?>
                                    <div class="alert alert-warning" style="margin-top: 1rem; margin-bottom: 0;">
                                        <strong>⚠️ Attention:</strong> <?= htmlspecialchars($intervention['alerte_message']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
