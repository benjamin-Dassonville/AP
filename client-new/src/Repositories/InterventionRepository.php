<?php

class InterventionRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTotalCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM Fiche_Intervention");
        return $stmt->fetch()['total'];
    }

    public function getCountByStatus($status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Fiche_Intervention WHERE statut = ?");
        $stmt->execute([$status]);
        return $stmt->fetch()['total'];
    }

    public function getRecent($limit = 10) {
        $stmt = $this->db->query("
            SELECT 
                fi.id,
                fi.numero_intervention,
                fi.titre,
                fi.statut,
                fi.priorite,
                fi.date_intervention,
                fi.alerte_active,
                fi.client_id,
                c.Raison_Sociale as client_nom,
                CONCAT(u.prenom, ' ', u.nom) as technicien_nom
            FROM Fiche_Intervention fi
            LEFT JOIN Client c ON fi.client_id = c.Numero_Client
            LEFT JOIN Utilisateur u ON fi.technicien_id = u.id
            ORDER BY fi.date_creation DESC
            LIMIT " . (int)$limit
        );
        return $stmt->fetchAll();
    }

    public function getStatsByStatus() {
        $stmt = $this->db->query("
            SELECT statut, COUNT(*) as count
            FROM Fiche_Intervention
            GROUP BY statut
        ");
        return $stmt->fetchAll();
    }
}
