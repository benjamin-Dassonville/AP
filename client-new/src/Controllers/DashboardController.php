<?php

class DashboardController {
    public function getGestionnaireData() {
        AuthManager::requireRole('gestionnaire');

        $clientRepo = new ClientRepository();
        $interventionRepo = new InterventionRepository();
        $materielRepo = new MaterielRepository();

        $total_clients = $clientRepo->getTotalCount();
        $total_interventions = $interventionRepo->getTotalCount();
        $interventions_en_attente = $interventionRepo->getCountByStatus('en_attente');
        $equipements_sous_contrat = $materielRepo->getCountSousContrat();
        
        $interventions_recentes = $interventionRepo->getRecent(10);
        $stats_by_status = $interventionRepo->getStatsByStatus();
        
        $statut_counts = [
            'en_attente' => 0,
            'en_cours' => 0,
            'terminee' => 0,
            'annulee' => 0
        ];
        foreach ($stats_by_status as $stat) {
            $statut_counts[$stat['statut']] = $stat['count'];
        }

        return [
            'total_clients' => $total_clients,
            'total_interventions' => $total_interventions,
            'interventions_en_attente' => $interventions_en_attente,
            'equipements_sous_contrat' => $equipements_sous_contrat,
            'interventions_recentes' => $interventions_recentes,
            'statut_counts' => $statut_counts
        ];
    }

    public function getTechnicienData($userId) {
        AuthManager::requireRole('technicien');

        $db = Database::getInstance()->getConnection();

        try {
            // Interventions assignées au technicien
            $stmt = $db->prepare("
                SELECT 
                    fi.id,
                    fi.numero_intervention,
                    fi.titre,
                    fi.description,
                    fi.statut,
                    fi.priorite,
                    fi.date_intervention,
                    fi.alerte_active,
                    fi.alerte_message,
                    fi.client_id,
                    c.Raison_Sociale as client_nom,
                    c.Adresse as client_adresse,
                    c.Telephone_Client as client_telephone,
                    m.Numero_de_Serie as equipement_serie,
                    m.Emplacement as equipement_emplacement,
                    tm.Libelle_Type_materiel as equipement_type
                FROM Fiche_Intervention fi
                LEFT JOIN Client c ON fi.client_id = c.Numero_Client
                LEFT JOIN Materiel m ON fi.equipement_id = m.Numero_de_Serie
                LEFT JOIN Type_Materiel tm ON m.Reference_Interne = tm.Reference_Interne
                WHERE fi.technicien_id = ?
                ORDER BY 
                    CASE fi.statut
                        WHEN 'en_cours' THEN 1
                        WHEN 'en_attente' THEN 2
                        WHEN 'terminee' THEN 3
                        WHEN 'annulee' THEN 4
                    END,
                    fi.date_intervention ASC
            ");
            $stmt->execute([$userId]);
            $mes_interventions = $stmt->fetchAll();
            
            // Statistiques
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM Fiche_Intervention WHERE technicien_id = ?");
            $stmt->execute([$userId]);
            $total_interventions = $stmt->fetch()['total'];
            
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM Fiche_Intervention WHERE technicien_id = ? AND statut = 'en_attente'");
            $stmt->execute([$userId]);
            $interventions_en_attente = $stmt->fetch()['total'];
            
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM Fiche_Intervention WHERE technicien_id = ? AND statut = 'en_cours'");
            $stmt->execute([$userId]);
            $interventions_en_cours = $stmt->fetch()['total'];
            
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM Fiche_Intervention WHERE technicien_id = ? AND statut = 'terminee'");
            $stmt->execute([$userId]);
            $interventions_terminees = $stmt->fetch()['total'];
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des données: " . $e->getMessage());
            $mes_interventions = [];
            $total_interventions = 0;
            $interventions_en_attente = 0;
            $interventions_en_cours = 0;
            $interventions_terminees = 0;
        }

        return [
            'mes_interventions' => $mes_interventions,
            'total_interventions' => $total_interventions,
            'interventions_en_attente' => $interventions_en_attente,
            'interventions_en_cours' => $interventions_en_cours,
            'interventions_terminees' => $interventions_terminees
        ];
    }
}
