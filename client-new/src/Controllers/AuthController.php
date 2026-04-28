<?php

class AuthController {
    public function handleLogin($get, $post) {
        if (AuthManager::isLoggedIn()) {
            AuthManager::redirectToDashboard();
        }

        $error = '';
        $role_param = $get['role'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $post['username'] ?? '';
            $password = $post['password'] ?? '';
            $expected_role = $post['role'] ?? '';
            
            if ($username && $password) {
                $userArray = authenticateUser($username, $password); // Utilise la fonction compatible de auth.php
                
                if ($userArray) {
                    if ($expected_role && $userArray['role'] !== $expected_role) {
                        $error = "Ce compte n'est pas un compte " . ucfirst($expected_role) . ".";
                    } else {
                        loginUser($userArray); // Utilise la fonction compatible de auth.php
                        AuthManager::redirectToDashboard();
                    }
                } else {
                    $error = "Nom d'utilisateur ou mot de passe incorrect.";
                }
            } else {
                $error = "Veuillez remplir tous les champs.";
            }
        }

        $page_title = 'Connexion CashCash';
        if ($role_param === 'gestionnaire') {
            $page_title = 'Connexion Gestionnaire';
        } elseif ($role_param === 'technicien') {
            $page_title = 'Connexion Technicien';
        }

        return [
            'error' => $error,
            'role_param' => $role_param,
            'page_title' => $page_title
        ];
    }

    public function handleLogout() {
        AuthManager::logoutUser();
        header('Location: index.php?logged_out=1');
        exit;
    }
}
