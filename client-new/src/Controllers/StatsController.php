<?php

class StatsController {
    public function getStats() {
        AuthManager::requireRole('gestionnaire');

        $db = Database::getInstance()->getConnection();

        try {
            $stmt = $db->query("
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
            
            $stmt = $db->query("
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

        return [
            'statut_counts' => $statut_counts,
            'stats_by_technicien' => $stats_by_technicien
        ];
    }
}
