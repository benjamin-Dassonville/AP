<?php

class MaterielRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUnassignedEquipment($clientId) {
        $stmt = $this->db->prepare('SELECT m.* FROM Materiel m WHERE m.Numero_Client = ? AND (m.Numero_de_Contrat IS NULL OR m.Numero_de_Contrat = 0)');
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    public function getAssignedEquipment($clientId) {
        $stmt = $this->db->prepare('SELECT m.* FROM Materiel m WHERE m.Numero_Client = ? AND m.Numero_de_Contrat IS NOT NULL AND m.Numero_de_Contrat <> 0');
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    public function getCountSousContrat() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM Materiel m
            INNER JOIN Contrat_de_maintenance c ON m.Numero_de_Contrat = c.Numero_de_Contrat
            WHERE c.Date_echeance >= CURDATE()
        ");
        return $stmt->fetch()['total'];
    }
}
