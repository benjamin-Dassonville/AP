<?php

class UtilisateurRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function authenticate($username, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, username, password_hash, role, nom, prenom, email 
                FROM Utilisateur 
                WHERE username = ?
            ");
            $stmt->execute([$username]);
            $userData = $stmt->fetch();
            
            if ($userData && password_verify($password, $userData['password_hash'])) {
                $this->updateLastLogin($userData['id']);
                return new Utilisateur($userData);
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur d'authentification: " . $e->getMessage());
            return false;
        }
    }

    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE Utilisateur SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }
}
