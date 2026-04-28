<?php

class ClientRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($clientId) {
        $stmt = $this->db->prepare('SELECT * FROM Client WHERE Numero_Client = ?');
        $stmt->execute([$clientId]);
        $data = $stmt->fetch();
        return $data ? $data : null; // Return array to maintain compatibility with existing views
    }

    public function getTotalCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM Client");
        return $stmt->fetch()['total'];
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM Client ORDER BY Raison_Sociale");
        return $stmt->fetchAll();
    }
}
