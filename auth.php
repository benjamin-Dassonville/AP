<?php

session_start();
require_once 'config.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

function hasRole($role) {
    return isLoggedIn() && $_SESSION['user_role'] === $role;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header('Location: login.php?error=access_denied');
        exit;
    }
}

function authenticateUser($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT id, username, password_hash, role, nom, prenom, email 
            FROM Utilisateur 
            WHERE username = ? AND active = TRUE
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $updateStmt = $pdo->prepare("UPDATE Utilisateur SET last_login = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            return $user;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Erreur d'authentification: " . $e->getMessage());
        return false;
    }
}

function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_username'] = $user['username'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['login_time'] = time();
}

function logoutUser() {
    session_unset();
    session_destroy();
}

function getCurrentUserName() {
    if (isLoggedIn()) {
        return $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
    }
    return 'Invité';
}

function getCurrentUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function redirectToDashboard() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    
    if (hasRole('gestionnaire')) {
        header('Location: dashboard_gestionnaire.php');
    } elseif (hasRole('technicien')) {
        header('Location: dashboard_technicien.php');
    } else {
        header('Location: login.php');
    }
    exit;
}
?>
