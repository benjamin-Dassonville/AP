<?php

require_once 'auth.php';
requireLogin();

$user_role = getCurrentUserRole();
$user_id = $_SESSION['user_id'];

$action = $_GET['action'] ?? 'list';
$intervention_id = $_GET['id'] ?? null;
$error = '';
$success = '';
$intervention = null;

$search = $_GET['search'] ?? '';
$filter_statut = $_GET['filter_statut'] ?? '';
$filter_priorite = $_GET['filter_priorite'] ?? '';
$filter_technicien = $_GET['filter_technicien'] ?? '';
$filter_client = $_GET['filter_client'] ?? '';
$filter_date_debut = $_GET['filter_date_debut'] ?? '';
$filter_date_fin = $_GET['filter_date_fin'] ?? '';
$filter_alerte = $_GET['filter_alerte'] ?? '';

try {
    $stmt = $pdo->query("SELECT Numero_Client, Raison_Sociale FROM Client ORDER BY Raison_Sociale");
    $clients = $stmt->fetchAll();
    
    $stmt = $pdo->query("SELECT id, CONCAT(prenom, ' ', nom) as nom_complet FROM Utilisateur WHERE role = 'technicien' AND active = TRUE ORDER BY nom");
    $techniciens = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Erreur: " . $e->getMessage());
    $clients = [];
    $techniciens = [];
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_role !== 'gestionnaire') {
        $error = "Seuls les gestionnaires peuvent créer des interventions.";
    } else {
        try {
            $numero = 'INT-' . date('Y') . '-' . sprintf('%04d', rand(1, 9999));
            
            $stmt = $pdo->prepare("
                INSERT INTO Fiche_Intervention 
                (numero_intervention, titre, description, statut, priorite, date_intervention, 
                 technicien_id, client_id, equipement_id, commentaire_interne, 
                 alerte_active, alerte_message, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $numero,
                $_POST['titre'],
                $_POST['description'] ?? null,
                $_POST['statut'] ?? 'en_attente',
                $_POST['priorite'] ?? 'normale',
                $_POST['date_intervention'],
                $_POST['technicien_id'] ?: null,
                $_POST['client_id'],
                $_POST['equipement_id'] ?: null,
                $_POST['commentaire_interne'] ?? null,
                isset($_POST['alerte_active']) ? 1 : 0,
                $_POST['alerte_message'] ?? null,
                $user_id
            ]);
            
            $success = "Intervention créée avec succès!";
            $intervention_id = $pdo->lastInsertId();
            $action = 'view';
            
        } catch (PDOException $e) {
            $error = "Erreur lors de la création: " . $e->getMessage();
        }
    }
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST' && $intervention_id) {
    try {
        $stmt = $pdo->prepare("
            UPDATE Fiche_Intervention 
            SET titre = ?, description = ?, statut = ?, priorite = ?, date_intervention = ?,
                technicien_id = ?, equipement_id = ?, commentaire_interne = ?,
                alerte_active = ?, alerte_message = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $_POST['titre'],
            $_POST['description'] ?? null,
            $_POST['statut'],
            $_POST['priorite'],
            $_POST['date_intervention'],
            $_POST['technicien_id'] ?: null,
            $_POST['equipement_id'] ?: null,
            $_POST['commentaire_interne'] ?? null,
            isset($_POST['alerte_active']) ? 1 : 0,
            $_POST['alerte_message'] ?? null,
            $intervention_id
        ]);
        
        $success = "Intervention mise à jour avec succès!";
        $action = 'view';
        
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}

if (($action === 'view' || $action === 'edit') && $intervention_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT fi.*, 
                   c.Raison_Sociale as client_nom,
                   CONCAT(u.prenom, ' ', u.nom) as technicien_nom,
                   CONCAT(creator.prenom, ' ', creator.nom) as created_by_name
            FROM Fiche_Intervention fi
            LEFT JOIN Client c ON fi.client_id = c.Numero_Client
            LEFT JOIN Utilisateur u ON fi.technicien_id = u.id
            LEFT JOIN Utilisateur creator ON fi.created_by = creator.id
            WHERE fi.id = ?
        ");
        $stmt->execute([$intervention_id]);
        $intervention = $stmt->fetch();
        
        if (!$intervention) {
            $error = "Intervention non trouvée.";
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
        $action = 'list';
    }
}

$equipements_client = [];
if (isset($_POST['client_id']) || ($intervention && $intervention['client_id'])) {
    $client_id = $_POST['client_id'] ?? $intervention['client_id'];
    try {
        $stmt = $pdo->prepare("
            SELECT m.Numero_de_Serie, tm.Libelle_Type_materiel, m.Emplacement
            FROM Materiel m
            LEFT JOIN Type_Materiel tm ON m.Reference_Interne = tm.Reference_Interne
            WHERE m.Numero_Client = ?
            ORDER BY tm.Libelle_Type_materiel
        ");
        $stmt->execute([$client_id]);
        $equipements_client = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche d'intervention - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-page">
    <?php include 'components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <?php if ($action === 'create' || $action === 'edit'): ?>
                <div class="dashboard-title">
                    <h2><?= $action === 'create' ? 'Nouvelle intervention' : 'Modifier l\'intervention' ?></h2>
                    <p class="dashboard-subtitle">
                        <?= $action === 'create' ? 'Créer une nouvelle fiche d\'intervention' : 'Modifier les informations de l\'intervention' ?>
                    </p>
                </div>
                
                <?php if ($user_role === 'gestionnaire'): ?>
                    <?php include 'components/nav_gestionnaire.php'; ?>
                <?php else: ?>
                    <?php include 'components/nav_technicien.php'; ?>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <div class="table-container">
                    <form method="POST" action="?action=<?= $action === 'create' ? 'create' : 'update' ?>&id=<?= $intervention_id ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="titre">Titre de l'intervention *</label>
                                <input type="text" id="titre" name="titre" required 
                                       value="<?= htmlspecialchars($intervention['titre'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description"><?= htmlspecialchars($intervention['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div class="form-group">
                                    <label for="statut">Statut *</label>
                                    <select id="statut" name="statut" required>
                                        <option value="en_attente" <?= ($intervention['statut'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="en_cours" <?= ($intervention['statut'] ?? '') === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                        <option value="terminee" <?= ($intervention['statut'] ?? '') === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                        <option value="annulee" <?= ($intervention['statut'] ?? '') === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="priorite">Priorité *</label>
                                    <select id="priorite" name="priorite" required>
                                        <option value="basse" <?= ($intervention['priorite'] ?? '') === 'basse' ? 'selected' : '' ?>>Basse</option>
                                        <option value="normale" <?= ($intervention['priorite'] ?? 'normale') === 'normale' ? 'selected' : '' ?>>Normale</option>
                                        <option value="haute" <?= ($intervention['priorite'] ?? '') === 'haute' ? 'selected' : '' ?>>Haute</option>
                                        <option value="urgente" <?= ($intervention['priorite'] ?? '') === 'urgente' ? 'selected' : '' ?>>Urgente</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="date_intervention">Date et heure d'intervention *</label>
                                <input type="datetime-local" id="date_intervention" name="date_intervention" required
                                       value="<?= $intervention ? date('Y-m-d\TH:i', strtotime($intervention['date_intervention'])) : '' ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="client_id">Client *</label>
                                <select id="client_id" name="client_id" required <?= $action === 'edit' ? 'disabled' : '' ?>>
                                    <option value="">Sélectionner un client</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= $client['Numero_Client'] ?>" 
                                                <?= ($intervention['client_id'] ?? '') == $client['Numero_Client'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($client['Raison_Sociale']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($action === 'edit'): ?>
                                    <input type="hidden" name="client_id" value="<?= $intervention['client_id'] ?>">
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="equipement_id">Équipement (optionnel)</label>
                                <select id="equipement_id" name="equipement_id">
                                    <option value="">Aucun équipement spécifique</option>
                                    <?php foreach ($equipements_client as $eq): ?>
                                        <option value="<?= htmlspecialchars($eq['Numero_de_Serie']) ?>"
                                                <?= ($intervention['equipement_id'] ?? '') === $eq['Numero_de_Serie'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($eq['Libelle_Type_materiel']) ?> 
                                            (SN: <?= htmlspecialchars($eq['Numero_de_Serie']) ?>)
                                            <?= $eq['Emplacement'] ? ' - ' . htmlspecialchars($eq['Emplacement']) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="technicien_id">Technicien assigné</label>
                                <select id="technicien_id" name="technicien_id">
                                    <option value="">Non assigné</option>
                                    <?php foreach ($techniciens as $tech): ?>
                                        <option value="<?= $tech['id'] ?>"
                                                <?= ($intervention['technicien_id'] ?? '') == $tech['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tech['nom_complet']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="commentaire_interne">Commentaire interne (visible uniquement par les gestionnaires)</label>
                                <textarea id="commentaire_interne" name="commentaire_interne"><?= htmlspecialchars($intervention['commentaire_interne'] ?? '') ?></textarea>
                            </div>
                            
                            <div style="background: #fff3cd; border: 2px solid var(--warning-color); border-radius: var(--border-radius); padding: 1.5rem; margin-top: 1rem;">
                                <div class="form-group" style="margin-bottom: 0.5rem;">
                                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                        <input type="checkbox" id="alerte_active" name="alerte_active" 
                                               <?= ($intervention['alerte_active'] ?? false) ? 'checked' : '' ?>
                                               style="width: auto; cursor: pointer;">
                                        <span style="font-weight: 600; color: var(--warning-color);">
                                            ⚠️ Activer une alerte pour cette intervention
                                        </span>
                                    </label>
                                </div>
                                
                                <div class="form-group" id="alerte_message_group" 
                                     style="display: <?= ($intervention['alerte_active'] ?? false) ? 'block' : 'none' ?>;">
                                    <label for="alerte_message">Message d'alerte</label>
                                    <textarea id="alerte_message" name="alerte_message" 
                                              placeholder="Ex: Attention - matériel hors contrat, facturation à prévoir"><?= htmlspecialchars($intervention['alerte_message'] ?? '') ?></textarea>
                                    <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">
                                        Ce message sera affiché en évidence sur la fiche d'intervention
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <a href="<?= $user_role === 'gestionnaire' ? 'dashboard_gestionnaire.php' : 'dashboard_technicien.php' ?>" 
                               class="btn btn-outline">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-secondary">
                                ✓ <?= $action === 'create' ? 'Créer l\'intervention' : 'Enregistrer les modifications' ?>
                            </button>
                        </div>
                    </form>
                </div>
                
            <?php elseif ($action === 'view' && $intervention): ?>
                <div class="dashboard-title">
                    <h2><?= htmlspecialchars($intervention['numero_intervention']) ?></h2>
                    <p class="dashboard-subtitle"><?= htmlspecialchars($intervention['titre']) ?></p>
                </div>
                
                <?php if ($user_role === 'gestionnaire'): ?>
                    <?php include 'components/nav_gestionnaire.php'; ?>
                <?php else: ?>
                    <?php include 'components/nav_technicien.php'; ?>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <?php if ($intervention['alerte_active'] && $intervention['alerte_message']): ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Attention:</strong> <?= htmlspecialchars($intervention['alerte_message']) ?>
                    </div>
                <?php endif; ?>
                
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Détails de l'intervention</h3>
                        <div class="table-actions">
                            <a href="<?= $user_role === 'gestionnaire' ? 'dashboard_gestionnaire.php' : 'dashboard_technicien.php' ?>" 
                               class="btn btn-sm btn-outline">
                                ← Retour
                            </a>
                            <?php if ($user_role === 'gestionnaire'): ?>
                                <a href="?action=edit&id=<?= $intervention['id'] ?>" class="btn btn-sm btn-primary">
                                    Modifier
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div style="padding: 2rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                            <div>
                                <h4>Informations générales</h4>
                                <p><strong>Statut:</strong> 
                                    <span class="badge <?= getStatutBadge($intervention['statut']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $intervention['statut'])) ?>
                                    </span>
                                </p>
                                <p><strong>Priorité:</strong>
                                    <span class="badge <?= getPrioriteBadge($intervention['priorite']) ?>">
                                        <?= ucfirst($intervention['priorite']) ?>
                                    </span>
                                </p>
                                <p><strong>Date d'intervention:</strong> <?= date('d/m/Y à H:i', strtotime($intervention['date_intervention'])) ?></p>
                                <p><strong>Créé par:</strong> <?= htmlspecialchars($intervention['created_by_name']) ?></p>
                                <p><strong>Date de création:</strong> <?= date('d/m/Y à H:i', strtotime($intervention['date_creation'])) ?></p>
                            </div>
                            
                            <div>
                                <h4>Client et équipement</h4>
                                <p><strong>Client:</strong> <?= htmlspecialchars($intervention['client_nom']) ?></p>
                                <p><strong>Technicien:</strong> <?= htmlspecialchars($intervention['technicien_nom'] ?? 'Non assigné') ?></p>
                                <p><strong>Équipement:</strong> <?= htmlspecialchars($intervention['equipement_id'] ?? 'Non spécifié') ?></p>
                            </div>
                        </div>
                        
                        <?php if ($intervention['description']): ?>
                            <div style="margin-top: 2rem;">
                                <h4>Description</h4>
                                <p><?= nl2br(htmlspecialchars($intervention['description'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($user_role === 'gestionnaire' && $intervention['commentaire_interne']): ?>
                            <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--border-radius);">
                                <h4>Commentaire interne</h4>
                                <p><?= nl2br(htmlspecialchars($intervention['commentaire_interne'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="dashboard-title">
                    <h2>Fiches d'intervention</h2>
                    <p class="dashboard-subtitle">Liste de toutes les fiches d'intervention</p>
                </div>
                
                <?php if ($user_role === 'gestionnaire'): ?>
                    <?php include 'components/nav_gestionnaire.php'; ?>
                <?php else: ?>
                    <?php include 'components/nav_technicien.php'; ?>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <!-- Barre de recherche et filtres -->
                <div class="card" style="margin-bottom: 2rem; padding: 1.5rem;">
                    <h3 style="margin-top: 0; margin-bottom: 1rem;">🔍 Recherche et filtres</h3>
                    <form method="GET" action="" style="display: flex; flex-direction: column; gap: 1rem;">
                        <input type="hidden" name="action" value="list">
                        
                        <!-- Champ de recherche -->
                        <div class="form-group" style="margin: 0;">
                            <label for="search">Recherche (numéro, titre, description)</label>
                            <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>" 
                                   placeholder="Rechercher une intervention..." class="form-control">
                        </div>
                        
                        <!-- Filtres -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_statut">Statut</label>
                                <select id="filter_statut" name="filter_statut" class="form-control">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente" <?= $filter_statut === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="en_cours" <?= $filter_statut === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                    <option value="terminee" <?= $filter_statut === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                    <option value="annulee" <?= $filter_statut === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                </select>
                            </div>
                            
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_priorite">Priorité</label>
                                <select id="filter_priorite" name="filter_priorite" class="form-control">
                                    <option value="">Toutes les priorités</option>
                                    <option value="basse" <?= $filter_priorite === 'basse' ? 'selected' : '' ?>>Basse</option>
                                    <option value="normale" <?= $filter_priorite === 'normale' ? 'selected' : '' ?>>Normale</option>
                                    <option value="haute" <?= $filter_priorite === 'haute' ? 'selected' : '' ?>>Haute</option>
                                    <option value="urgente" <?= $filter_priorite === 'urgente' ? 'selected' : '' ?>>Urgente</option>
                                </select>
                            </div>
                            
                            <?php if ($user_role === 'gestionnaire'): ?>
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_technicien">Technicien</label>
                                <select id="filter_technicien" name="filter_technicien" class="form-control">
                                    <option value="">Tous les techniciens</option>
                                    <?php foreach ($techniciens as $tech): ?>
                                        <option value="<?= $tech['id'] ?>" <?= $filter_technicien == $tech['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tech['nom_complet']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_client">Client</label>
                                <select id="filter_client" name="filter_client" class="form-control">
                                    <option value="">Tous les clients</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= $client['Numero_Client'] ?>" <?= $filter_client == $client['Numero_Client'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($client['Raison_Sociale']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_date_debut">Date début</label>
                                <input type="date" id="filter_date_debut" name="filter_date_debut" 
                                       value="<?= htmlspecialchars($filter_date_debut) ?>" class="form-control">
                            </div>
                            
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_date_fin">Date fin</label>
                                <input type="date" id="filter_date_fin" name="filter_date_fin" 
                                       value="<?= htmlspecialchars($filter_date_fin) ?>" class="form-control">
                            </div>
                            
                            <div class="form-group" style="margin: 0;">
                                <label for="filter_alerte">Alerte</label>
                                <select id="filter_alerte" name="filter_alerte" class="form-control">
                                    <option value="">Toutes</option>
                                    <option value="oui" <?= $filter_alerte === 'oui' ? 'selected' : '' ?>>Avec alerte</option>
                                    <option value="non" <?= $filter_alerte === 'non' ? 'selected' : '' ?>>Sans alerte</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                            <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                            <a href="?action=list" class="btn btn-outline">🔄 Réinitialiser</a>
                        </div>
                    </form>
                </div>
                
                <?php
                try {
                    $sql = "
                        SELECT fi.*,
                               c.Raison_Sociale as client_nom,
                               CONCAT(u.prenom, ' ', u.nom) as technicien_nom
                        FROM Fiche_Intervention fi
                        LEFT JOIN Client c ON fi.client_id = c.Numero_Client
                        LEFT JOIN Utilisateur u ON fi.technicien_id = u.id
                        WHERE 1=1
                    ";
                    
                    $params = [];
                    
                    if ($user_role !== 'gestionnaire') {
                        $sql .= " AND fi.technicien_id = ?";
                        $params[] = $user_id;
                    }
                    
                    if (!empty($search)) {
                        $sql .= " AND (fi.numero_intervention LIKE ? OR fi.titre LIKE ? OR fi.description LIKE ?)";
                        $search_param = '%' . $search . '%';
                        $params[] = $search_param;
                        $params[] = $search_param;
                        $params[] = $search_param;
                    }
                    
                    if (!empty($filter_statut)) {
                        $sql .= " AND fi.statut = ?";
                        $params[] = $filter_statut;
                    }
                    
                    if (!empty($filter_priorite)) {
                        $sql .= " AND fi.priorite = ?";
                        $params[] = $filter_priorite;
                    }
                    
                    if (!empty($filter_technicien)) {
                        $sql .= " AND fi.technicien_id = ?";
                        $params[] = $filter_technicien;
                    }
                    
                    if (!empty($filter_client)) {
                        $sql .= " AND fi.client_id = ?";
                        $params[] = $filter_client;
                    }
                    
                    if (!empty($filter_date_debut)) {
                        $sql .= " AND DATE(fi.date_intervention) >= ?";
                        $params[] = $filter_date_debut;
                    }
                    
                    if (!empty($filter_date_fin)) {
                        $sql .= " AND DATE(fi.date_intervention) <= ?";
                        $params[] = $filter_date_fin;
                    }
                    
                    if ($filter_alerte === 'oui') {
                        $sql .= " AND fi.alerte_active = 1";
                    } elseif ($filter_alerte === 'non') {
                        $sql .= " AND fi.alerte_active = 0";
                    }
                    
                    $sql .= " ORDER BY fi.date_intervention DESC";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $all_interventions = $stmt->fetchAll();
                } catch (PDOException $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $all_interventions = [];
                }
                ?>
                
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">
                            <?= $user_role === 'gestionnaire' ? 'Toutes les interventions' : 'Mes interventions' ?>
                        </h3>
                        <?php if ($user_role === 'gestionnaire'): ?>
                            <div class="table-actions">
                                <a href="?action=create" class="btn btn-primary btn-sm">
                                    + Nouvelle intervention
                                </a>
                            </div>
                        <?php endif; ?>
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
                                <?php if (empty($all_interventions)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center" style="padding: 2rem; color: var(--text-secondary);">
                                            Aucune intervention enregistrée
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($all_interventions as $interv): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($interv['numero_intervention']) ?>
                                                <?php if ($interv['alerte_active']): ?>
                                                    <span style="color: var(--danger-color);" title="Alerte active">⚠️</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($interv['titre']) ?></td>
                                            <td><?= htmlspecialchars($interv['client_nom'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($interv['technicien_nom'] ?? 'Non assigné') ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($interv['date_intervention'])) ?></td>
                                            <td>
                                                <span class="badge <?= getPrioriteBadge($interv['priorite']) ?>">
                                                    <?= ucfirst($interv['priorite']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= getStatutBadge($interv['statut']) ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $interv['statut'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?action=view&id=<?= $interv['id'] ?>" class="btn btn-sm btn-outline">
                                                    Voir
                                                </a>
                                                <?php if ($user_role === 'gestionnaire'): ?>
                                                    <a href="?action=edit&id=<?= $interv['id'] ?>" class="btn btn-sm btn-primary">
                                                        Modifier
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        const alerteCheckbox = document.getElementById('alerte_active');
        const alerteMessageGroup = document.getElementById('alerte_message_group');
        
        if (alerteCheckbox && alerteMessageGroup) {
            alerteCheckbox.addEventListener('change', function() {
                alerteMessageGroup.style.display = this.checked ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>

<?php
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
