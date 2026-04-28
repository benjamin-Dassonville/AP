<?php

class InterventionController {
    public function handleRequest($get, $post, $session) {
        AuthManager::requireLogin();

        $user_role = AuthManager::getCurrentUserRole();
        $user_id = $session['user_id'];

        $action = $get['action'] ?? 'list';
        $intervention_id = $get['id'] ?? null;
        $error = '';
        $success = '';
        $intervention = null;

        $search = $get['search'] ?? '';
        $filter_statut = $get['filter_statut'] ?? '';
        $filter_priorite = $get['filter_priorite'] ?? '';
        $filter_technicien = $get['filter_technicien'] ?? '';
        $filter_client = $get['filter_client'] ?? '';
        $filter_date_debut = $get['filter_date_debut'] ?? '';
        $filter_date_fin = $get['filter_date_fin'] ?? '';
        $filter_alerte = $get['filter_alerte'] ?? '';

        $db = Database::getInstance()->getConnection();

        try {
            $stmt = $db->query("SELECT Numero_Client, Raison_Sociale FROM Client ORDER BY Raison_Sociale");
            $clients = $stmt->fetchAll();
            
            $stmt = $db->query("SELECT id, CONCAT(prenom, ' ', nom) as nom_complet FROM Utilisateur WHERE role = 'technicien' ORDER BY nom");
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
                    
                    $stmt = $db->prepare("
                        INSERT INTO Fiche_Intervention 
                        (numero_intervention, titre, description, statut, priorite, date_intervention, 
                         technicien_id, client_id, equipement_id, commentaire_interne, 
                         alerte_active, alerte_message, created_by)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $stmt->execute([
                        $numero,
                        $post['titre'],
                        $post['description'] ?? null,
                        $post['statut'] ?? 'en_attente',
                        $post['priorite'] ?? 'normale',
                        $post['date_intervention'],
                        $post['technicien_id'] ?: null,
                        $post['client_id'],
                        $post['equipement_id'] ?: null,
                        $post['commentaire_interne'] ?? null,
                        isset($post['alerte_active']) ? 1 : 0,
                        $post['alerte_message'] ?? null,
                        $user_id
                    ]);
                    
                    $success = "Intervention créée avec succès!";
                    $intervention_id = $db->lastInsertId();
                    $action = 'view';
                    
                } catch (PDOException $e) {
                    $error = "Erreur lors de la création: " . $e->getMessage();
                }
            }
        }

        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST' && $intervention_id) {
            try {
                $stmt = $db->prepare("
                    UPDATE Fiche_Intervention 
                    SET titre = ?, description = ?, statut = ?, priorite = ?, date_intervention = ?,
                        technicien_id = ?, equipement_id = ?, commentaire_interne = ?,
                        alerte_active = ?, alerte_message = ?
                    WHERE id = ?
                ");
                
                $stmt->execute([
                    $post['titre'],
                    $post['description'] ?? null,
                    $post['statut'],
                    $post['priorite'],
                    $post['date_intervention'],
                    $post['technicien_id'] ?: null,
                    $post['equipement_id'] ?: null,
                    $post['commentaire_interne'] ?? null,
                    isset($post['alerte_active']) ? 1 : 0,
                    $post['alerte_message'] ?? null,
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
                $stmt = $db->prepare("
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
        if (isset($post['client_id']) || ($intervention && $intervention['client_id'])) {
            $client_id = $post['client_id'] ?? $intervention['client_id'];
            try {
                $stmt = $db->prepare("
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
        
        $all_interventions = [];
        if ($action === 'list') {
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
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                $all_interventions = $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Erreur: " . $e->getMessage());
            }
        }

        return [
            'user_role' => $user_role,
            'user_id' => $user_id,
            'action' => $action,
            'intervention_id' => $intervention_id,
            'error' => $error,
            'success' => $success,
            'intervention' => $intervention,
            'search' => $search,
            'filter_statut' => $filter_statut,
            'filter_priorite' => $filter_priorite,
            'filter_technicien' => $filter_technicien,
            'filter_client' => $filter_client,
            'filter_date_debut' => $filter_date_debut,
            'filter_date_fin' => $filter_date_fin,
            'filter_alerte' => $filter_alerte,
            'clients' => $clients,
            'techniciens' => $techniciens,
            'equipements_client' => $equipements_client,
            'all_interventions' => $all_interventions
        ];
    }
}
