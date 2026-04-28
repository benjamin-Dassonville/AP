<?php

class Utilisateur {
    public $id;
    public $username;
    public $password_hash;
    public $role;
    public $nom;
    public $prenom;
    public $email;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->username = $data['username'] ?? null;
            $this->password_hash = $data['password_hash'] ?? null;
            $this->role = $data['role'] ?? null;
            $this->nom = $data['nom'] ?? null;
            $this->prenom = $data['prenom'] ?? null;
            $this->email = $data['email'] ?? null;
        }
    }
}
