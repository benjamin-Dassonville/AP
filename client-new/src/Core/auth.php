<?php

session_start();
require_once dirname(__DIR__, 2) . '/config.php';

function isLoggedIn() {
    return AuthManager::isLoggedIn();
}

function hasRole($role) {
    return AuthManager::hasRole($role);
}

function requireLogin() {
    AuthManager::requireLogin();
}

function requireRole($role) {
    AuthManager::requireRole($role);
}

function authenticateUser($username, $password) {
    $repo = new UtilisateurRepository();
    $user = $repo->authenticate($username, $password);
    
    // Pour assurer la compatibilité avec l'ancien code qui attend un tableau
    if ($user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email
        ];
    }
    return false;
}

function loginUser($userArray) {
    $user = new Utilisateur($userArray);
    AuthManager::loginUser($user);
}

function logoutUser() {
    AuthManager::logoutUser();
}

function getCurrentUserName() {
    return AuthManager::getCurrentUserName();
}

function getCurrentUserRole() {
    return AuthManager::getCurrentUserRole();
}

function redirectToDashboard() {
    AuthManager::redirectToDashboard();
}
?>
