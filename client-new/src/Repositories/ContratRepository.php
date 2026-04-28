<?php

class ContratRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getClientContracts($clientId) {
        $stmt = $this->db->prepare('SELECT * FROM Contrat_de_maintenance WHERE Numero_Client = ? ORDER BY Date_echeance ASC');
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    public function getContractWithDays($contractId) {
        $stmt = $this->db->prepare('SELECT c.*, DATEDIFF(c.Date_echeance, CURDATE()) AS days_before FROM Contrat_de_maintenance c WHERE c.Numero_de_Contrat = ?');
        $stmt->execute([$contractId]);
        $data = $stmt->fetch();
        return $data ? $data : null;
    }
}
